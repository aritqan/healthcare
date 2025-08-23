<?php

namespace Modules\Permission\Http\Services;

use Illuminate\Support\Facades\DB;
use Modules\Base\Http\Services\BaseCrudService;
use Modules\Permission\Models\Role as CrudModel;

class RoleService extends BaseCrudService
{
    public function createModel(array $data) : CrudModel
    {
        $translations = $this->createTranslations($data, 'title', ['description']);

        $model = DB::transaction(function () use($data, $translations){
            $model = CrudModel::create([
                'name' => $data['code'],
            ]);

            $model->update($translations);

            $this->syncRolePermissions($model, $data);

            return $model;
        });

        return $model;
    }

    public function updateModel(CrudModel $model, array $data) : CrudModel
    {
        DB::transaction(function () use($data, $model){
            $this->updateTranslations($model, $data, 'title', ['description']);
            $this->syncRolePermissions($model, $data);
        });

        return $model;
    }

    private function syncRolePermissions(CrudModel $model, array $data) : void
    {
        isset($data['permissions']) ? $model->abilities()->sync($data['permissions']) : $model->abilities()->detach();
    }
}
