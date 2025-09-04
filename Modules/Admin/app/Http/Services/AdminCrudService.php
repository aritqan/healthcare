<?php

namespace Modules\Admin\Http\Services;

use Modules\Admin\Models\Admin;
use Silber\Bouncer\BouncerFacade;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Enums\AdminStatus;
use Modules\Admin\Events\RoleChangedEvent;
use Modules\Admin\Models\Admin as CrudModel;
use Modules\Base\Http\Services\BaseCrudService;
use Modules\Nabd\Enums\MedicalFacilitesTypes;
use Modules\Permission\Enums\SystemDefaultRoles;
use Modules\Permission\Models\Role;

class AdminCrudService extends BaseCrudService
{
    protected $unnecessaryFieldsForCrud = [
        'avatar',
        'avatar_remove',
        // 'current_password',
        'role_id',
        'state_id',
        'medical_facility_id',
    ];

    public function createModel(array $data) : CrudModel
    {
        $modelData = $this->prepareModelData($data);

        if(!isset($data['password'])) {
            $modelData['password']         = $this->generateTempRandomPassword();
            $modelData['password_is_temp'] = true;
        }

        $roleName = Role::where('id', $data['role_id'])->value('name');

        $model = DB::transaction(function () use($data, $modelData, $roleName){
            $model = CrudModel::create($modelData);

            BouncerFacade::assign($data['role_id'])->to($model);

            $this->uploadImageForModel($model, $data, Admin::MEDIA_COLLECTION, 'avatar');

            if($roleName != SystemDefaultRoles::SYSTEM_ADMIN_ROLE){
                $this->createOrUpdateProfile($model, $data, $roleName);
            }

            return $model;
        });

        return $model;
    }

    public function updateModel(CrudModel $model, array $data) : CrudModel
    {
        if(isset($data['password']) && is_null($data['password'])){
            unset($data['password']);
        }

        $modelData = $this->prepareModelData($data);

        $roleName = Role::where('id', $data['role_id'])->value('name');

        DB::transaction(function () use($data, $model, $modelData, $roleName){
            $model->update($modelData);

            if(isset($data['avatar_remove']) && $data['avatar_remove'] == true){
                $this->removeAvatar($model, Admin::MEDIA_COLLECTION);
            }

            $this->uploadImageForModel($model, $data, Admin::MEDIA_COLLECTION, 'avatar');

            if($data['status'] != AdminStatus::ACTIVE){
                $this->removeFcmToken($model);
            }

            if($roleName != SystemDefaultRoles::SYSTEM_ADMIN_ROLE){
                $this->createOrUpdateProfile($model, $data, $roleName);
            }

            // if (isset($data['role'])) {
            //     $this->changeRole($model, $data['role']);
            // }
        });

        return $model;
    }

    private function removeFcmToken(CrudModel $model) : void
    {
        foreach($model->fcmTokens ?? [] as $token){
            $token->delete();
        }
    }

    public function removeAvatar(CrudModel $model, string $collection) : void
    {
        $media = $model->getFirstMedia($collection);

        if($media) {
            $media->delete();
        }
    }

    private function changeRole(CrudModel $model, string $roleId) : void
    {
        $oldRole = $model->roles->first();

        if(is_null($oldRole) || $oldRole->id == $roleId) return;

        // Sync roles with pivot values
        $model->roles()->syncWithPivotValues([$roleId], ['entity_type' => get_class($model)]);

        if(config('audit.enabled')) event(new RoleChangedEvent($model, $oldRole->name, app('admin')->id));
    }

    private function generateTempRandomPassword() : string
    {
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(16 / strlen($x)) )),1,16);
    }

    private function createOrUpdateProfile(CrudModel $model, $data, $roleName) : void
    {
        switch ($roleName) {
            case SystemDefaultRoles::CLINIC:
                $type = MedicalFacilitesTypes::CLINIC->value;
                break;
            case SystemDefaultRoles::PHARMACY:
                $type = MedicalFacilitesTypes::PHARMACY->value;
                break;
            case SystemDefaultRoles::DOCTOR:
                $type = MedicalFacilitesTypes::DOCTOR->value;
                break;
            case SystemDefaultRoles::PHARMACIST:
                $type = MedicalFacilitesTypes::PHARMACIST->value;
                break;
            default:
                $type = null;
        }

        if(!$type) return;

        $model->profile()->updateOrCreate(
            [
                'type' => $type
            ],
            [
                'state_id'              => $data['state_id'] ?? null,
                'medical_facility_id'   => checkIfRoleMedicalFacilityRequired($roleName) ? $data['medical_facility_id'] : null
            ]
        );
    }

}
