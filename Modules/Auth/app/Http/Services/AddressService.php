<?php

namespace Modules\Auth\Http\Services;

use Illuminate\Support\Facades\DB;
use Modules\Base\Http\Services\BaseApiService;
use Modules\Auth\Models\Address as CrudModel;

class AddressService extends BaseApiService
{
    protected $unnecessaryFieldsForCrud = [
    ];

    /**
     * Create a new Model instance.
     *
     * @param array $data
     * @return CrudModel
     */
    public function createModel(array $data): CrudModel
    {
        $modelData = $this->prepareModelData($data);

        $model = DB::transaction(function () use($modelData) {
            // Create the model
            $model = CrudModel::create($modelData);

            $model->load('country', 'state', 'city');

            return $model;
        });

        return $model;
    }

    /**
     * Update the Model instance.
     */
    public function updateModel(CrudModel $model, array $data): CrudModel
    {
        $modelData = $this->prepareModelData($data);

        $model = DB::transaction(function () use($model, $modelData) {
            // Update the model
            $model->update($modelData);

            $model->load('country', 'state', 'city');

            return $model;
        });

        return $model;
    }
}
