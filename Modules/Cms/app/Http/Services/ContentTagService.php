<?php

namespace Modules\Cms\Http\Services;

use Illuminate\Support\Facades\DB;
use Modules\Base\Http\Services\BaseCrudService;
use Modules\Cms\Models\ContentTag as CrudModel;

class ContentTagService extends BaseCrudService
{
    /**
     * The unnecessary fields for crud.
     * Example: if the data has translation fields, you can add them here. As a ('title', 'description')
     */
    protected $unnecessaryFieldsForCrud = [
        'title',
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

        $translations = $this->createTranslations($data, 'title');

        $model = DB::transaction(function () use($modelData, $translations){
            $model = CrudModel::create($modelData);

            $model->update($translations);

            return $model;
        });

        return $model;
    }

    /**
     * Update a Model instance.
     *
     * @param CrudModel $model
     * @param array $data
     * @return CrudModel
     */
    public function updateModel(CrudModel $model, array $data) : CrudModel
    {
        $modelData = $this->prepareModelData($data);

        DB::transaction(function () use($data, $model, $modelData){
            $model->update($modelData);
            $this->updateTranslations($model, $data, 'title');
        });

        return $model;
    }
}
