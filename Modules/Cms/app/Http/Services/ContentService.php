<?php

namespace Modules\Cms\Http\Services;

use Illuminate\Support\Facades\DB;
use Modules\Base\Http\Services\BaseCrudService;
use Modules\Cms\Models\Content as CrudModel;

class ContentService extends BaseCrudService
{
    /**
     * The unnecessary fields for crud.
     * Example: if the data has translation fields, you can add them here. As a ('title', 'description')
     */
    protected $unnecessaryFieldsForCrud = [
        'title',
        'short_description',
        'long_description',
        'image',
        'placement',
        'tags',
    ];

    /**
     * The custom properties for the model to store in custom_properties attribute.
     */
    protected $customProperties = [
        'placement',
    ];

    /**
     * Create a new Model instance.
     *
     * @param array $data
     * @return CrudModel
     */
    public function createModel(array $data): CrudModel
    {
        $modelData    = $this->prepareModelData($data);
        $translations = $this->createTranslations($data, 'title', ['short_description', 'long_description']);

        $model = DB::transaction(function () use($data, $modelData, $translations){
            // Create the model instance
            $model = CrudModel::create($modelData);

            // Store custom properties
            $this->createOrUpdateCustomProperties($model, $data);

            // Store Content Tags
            $this->storeContentTags($model, $data);

            // Create translations
            $model->update($translations);

            // Upload image
            $this->uploadImageForTransModel($model, $data, CrudModel::MEDIA_COLLECTION);

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

        DB::transaction(function () use($data, $model, $modelData) {
            // Update the model instance
            $model->update($modelData);

            // Update custom properties
            $this->createOrUpdateCustomProperties($model, $data);

            // Store Content Tags
            $this->storeContentTags($model, $data);

            // Update translations
            $this->updateTranslations($model, $data, 'title', ['short_description', 'long_description']);

            // Upload image
            $this->uploadImageForTransModel($model, $data, CrudModel::MEDIA_COLLECTION);
        });

        return $model;
    }

    /**
     * Store the custom properties for the model.
     *
     * @param CrudModel $model
     * @param array $data
     * @return void
     */
    private function createOrUpdateCustomProperties(CrudModel $model, array $data)
    {
        $customProperties = [];

        foreach($this->customProperties as $property) {
            if(isset($data[$property])) {
                $customProperties[$property] = $data[$property];
            }
        }

        if(!empty($customProperties)) {
            $model->custom_properties = $customProperties;
            $model->save();
        }
    }

    /**
     * Store Content Tags for the model.
     *
     * @param CrudModel $model
     * @param array $data
     * @return CrudModel
     */
    private function storeContentTags(CrudModel $model, array $data)
    {
        if(isset($data['tags'])) {
            $model->tags()->sync($data['tags']);
        } else {
            $model->tags()->sync([]);
        }

        return $model;
    }
}
