<?php

namespace Modules\Crm\Http\Services;

use Illuminate\Support\Facades\DB;
use Modules\Base\Http\Services\BaseCrudService;
use Modules\Crm\Models\Subscribe as CrudModel;

class SubscribeService extends BaseCrudService
{
    /**
     * The unnecessary fields for crud.
     * Example: if the data has translation fields, you can add them here. As a ('title', 'description')
     */
    protected $unnecessaryFieldsForCrud = [];

    /**
     * Create a new Model instance.
     *
     * @param array $data
     * @return CrudModel
     */
    public function createModel(array $data): CrudModel
    {
        $modelData = $this->prepareModelData($data);

        $model = DB::transaction(function () use($modelData){
            $model = CrudModel::create($modelData);

            return $model;
        });

        return $model;
    }
}
