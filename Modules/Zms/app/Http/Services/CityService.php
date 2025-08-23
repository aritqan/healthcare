<?php

namespace Modules\Zms\Http\Services;

use Illuminate\Support\Facades\DB;
use Modules\Base\Http\Services\BaseCrudService;
use Modules\Zms\Models\City as CrudModel;

class CityService extends BaseCrudService
{
    public function updateModel(CrudModel $model, array $data) : CrudModel
    {
        $transData['name'] = $data['name'] ?? [];

        unset($data['name']);

        DB::transaction(function () use($data, $model, $transData){
            $model->update($data);
            $this->updateTranslations($model, $transData, 'name');
        });

        return $model;
    }
}
