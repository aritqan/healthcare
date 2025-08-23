<?php

namespace Modules\Zms\Http\Services;

use Illuminate\Support\Facades\DB;
use Modules\Base\Http\Services\BaseCrudService;
use Modules\Zms\Models\State as CrudModel;

class StateService extends BaseCrudService
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
