<?php

namespace Modules\Admin\Http\Services;

use Silber\Bouncer\BouncerFacade;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Models\Admin;
use Modules\Admin\Enums\AdminStatus;
use Modules\Admin\Events\RoleChangedEvent;
use Modules\Admin\Models\Admin as CrudModel;
use Modules\Base\Http\Services\BaseCrudService;
class AdminCrudService extends BaseCrudService
{
    protected $unnecessaryFieldsForCrud = [
        'avatar',
        'avatar_remove',
        'current_password',
        'role'
    ];

    public function createModel(array $data) : CrudModel
    {
        $modelData = $this->prepareModelData($data);

        $model = DB::transaction(function () use($data, $modelData){
            $model = CrudModel::create($modelData);

            BouncerFacade::assign($data['role'])->to($model);

            $this->uploadImageForModel($model, $data, Admin::MEDIA_COLLECTION, 'avatar');

            return $model;
        });

        return $model;
    }

    public function updateModel(CrudModel $model, array $data) : CrudModel
    {
        if(is_null($data['password'])){
            unset($data['password']);
        }

        $modelData = $this->prepareModelData($data);

        DB::transaction(function () use($data, $model, $modelData){
            $model->update($modelData);

            if(isset($data['avatar_remove']) && $data['avatar_remove'] == true){
                $this->removeAvatar($model, Admin::MEDIA_COLLECTION);
            }

            $this->uploadImageForModel($model, $data, Admin::MEDIA_COLLECTION, 'avatar');

            if($data['status'] != AdminStatus::ACTIVE){
                $this->removeFcmToken($model);
            }

            if (isset($data['role'])) {
                $this->changeRole($model, $data['role']);
            }
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
}
