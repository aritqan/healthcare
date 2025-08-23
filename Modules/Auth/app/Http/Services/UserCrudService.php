<?php

namespace Modules\Auth\Http\Services;

use Illuminate\Support\Facades\DB;
use Modules\Admin\Enums\AdminStatus;
use Modules\Auth\Models\User as CrudModel;
use Modules\Base\Http\Services\BaseCrudService;

class UserCrudService extends BaseCrudService
{
    protected $unnecessaryFieldsForCrud = [];

    public function createModel(array $data) : CrudModel
    {
        $modelData = $this->prepareModelData($data);

        $model = DB::transaction(function () use($data, $modelData){
            $model = CrudModel::create($modelData);

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

            if($data['status'] != AdminStatus::ACTIVE){
                $this->removeFcmToken($model);
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
}
