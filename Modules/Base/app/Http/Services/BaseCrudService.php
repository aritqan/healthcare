<?php

namespace Modules\Base\Http\Services;

use OwenIt\Auditing\Auditable;
use Modules\Base\Events\UpdateTranslationEvent;

class BaseCrudService extends BaseService
{
    /**
     * Fields that are not necessary for creating or updating the model.
     */
    protected $unnecessaryFieldsForCrud = [];

    public function __construct() {
        parent::__construct();
    }

    /**
     * Remove unnecessary fields from the data array before creating or updating the model.
     *
     * @param array $data
     * @return array
     */
    protected function prepareModelData(array $data) : array
    {
        $data = array_diff_key($data, array_flip($this->unnecessaryFieldsForCrud));

        return $data;
    }

    /**
     * Create translations array for the model.
     *
     * @param array $data
     * @param string $titleField
     * @param string|null $descriptionField
     * @param string|null $longDescriptionField
     * @return array
     */
    protected function createTranslations(array $data, string $titleField, array $otherFields = []) : array
    {
        if(!isset($data[$titleField]) || empty($data[$titleField])) {
            return [];
        }

        $translations = [];

        foreach($data[$titleField] as $locale => $value) {
            if(!empty($value)) {
                $translations[$titleField . ':' . $locale] = $value;

                foreach($otherFields as $field) {
                    $translations[$field . ':' . $locale] = $data[$field][$locale] ?? null;
                }
            }
        }

        return $translations;
    }

    /**
     * Update translations for the model.
     *
     * @param mixed $model
     * @param array $data
     * @param string $titleField
     * @param string|null $descriptionField
     * @return void
     */
    protected function updateTranslations(mixed $model, array $data, string $titleField, array $otherFields = []): void
    {
        if (!isset($data[$titleField]) || empty($data[$titleField])) {
            return;
        }

        $locales            = [];
        $oldTranslations    = $model->getTranslationsArray();
        $modifiedOldValues  = [];
        $modifiedNewValues  = [];

        foreach ($data[$titleField] as $locale => $value) {
            if (!empty($value)) {
                $locales[] = $locale;
                $model->{$titleField . ':' . $locale} = $value;

                foreach (array_merge([$titleField], $otherFields) as $field) {
                    $newValue = $data[$field][$locale] ?? null;
                    $oldValue = $oldTranslations[$locale][$field] ?? null;

                    if ($oldValue !== $newValue) {
                        $modifiedOldValues[$locale][$field] = $oldValue;
                        $modifiedNewValues[$locale][$field] = $newValue;
                    }

                    $model->{$field . ':' . $locale} = $newValue;
                }
            }
        }

        // Remove locales not present in the current data
        $model->translations()->whereNotIn('locale', $locales)->delete();

        $model->save();

        if (!empty($modifiedOldValues) && config('audit.enabled') && in_array(Auditable::class, class_uses($model))) {
            event(new UpdateTranslationEvent($model, $modifiedOldValues, $modifiedNewValues));
        }
    }

    /**
     * Upload image for the model.
     *
     * @param array $data
     * @param mixed $model
     * @param string $collection
     * @param string $field
     * @return mixed
     */
    protected function uploadImageForModel(mixed $model, array $data, string $collection, string $field = 'image'): mixed
    {
        if(isset($data[$field])) {
            $media = $model->getFirstMedia($collection);

            if($media) {
                $media->delete();
            }

            $model->addMedia($data[$field])
                ->toMediaCollection($collection);
        }

        return $model;
    }

    /**
     * Upload image for the translation model.
     *
     * @param array $data
     * @param mixed $model
     * @param string $collection
     * @param string $field
     *
     * @return mixed
     */
    protected function uploadImageForTransModel(mixed $model, array $data, string $collection, string $field = 'image'): mixed
    {
        if(isset($data[$field]) && is_array($data[$field])) {
            foreach($data[$field] as $locale => $image) {
                $translationModel = $model->translations()
                ->where('locale', $locale)
                ->first();

                if($translationModel) {
                    $media = $translationModel->getFirstMedia($collection);

                    if($media) {
                        $media->delete();
                    }

                    $translationModel->addMedia($image)
                    ->withCustomProperties(['locale' => $locale])
                    ->toMediaCollection($collection);
                }
            }
        }

        return $model;
    }
}
