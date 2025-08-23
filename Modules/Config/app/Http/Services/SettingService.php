<?php

namespace Modules\Config\Http\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Modules\Base\Http\Services\BaseCrudService;
use Modules\Config\Models\Setting as CrudModel;

class SettingService extends BaseCrudService
{
    protected $unnecessaryFieldsForCrud = ['title', 'description'];

    private $settingsInEnv = [
        'app_name',
        'app_default_language',
        'session_lifetime',
    ];

    public function createModel(array $data): CrudModel
    {
        $modelData = $this->prepareModelData($data);

        $translations = $this->createTranslations($data, 'title', ['description']);

        $model = DB::transaction(function () use($modelData, $translations){
            $model = CrudModel::create($modelData);

            $model->update($translations);

            return $model;
        });

        return $model;
    }

    public function updateSetting(array $data) : void
    {
        DB::transaction(function () use($data){
            foreach($data as $key => $value)
            {
                // If the value is an object, it means it's a media file
                if(gettype($value) === 'object')
                {
                    $this->updateSettingMedia($key, $value);
                    continue;
                }

                // If the value is an array, it means it's a translatable setting
                if(is_array($value))
                {
                    foreach($value as $lang => $val) {
                        $this->updateSettingTranslation($key, $lang, $val);
                    }
                    continue;
                }

                CrudModel::where('key', $key)->where('translatable', false)->update(['value' => $value]);
            }
        });

        // Update .env file
        $envSettings = CrudModel::whereIn('key', $this->settingsInEnv)->pluck('value', 'key')->toArray();
        $this->updateEnvFile($envSettings);

        // Clear Cache
        Cache::flush();
    }

    public function updateSettingTranslation(string $key, string $lang, string $value): void
    {
        $setting = CrudModel::where('key', $key)->where('translatable', true)->first();

        if($lang == 'en')
        {
            $setting->update(['value' => $value]);
        }

        $setting->translations()->updateOrCreate(
            [
                'locale'     => $lang,
                'setting_id' => $setting->id
            ],
            [
                'trans_value'=> $value
            ]
        );
    }

    public function updateSettingMedia(string $key, UploadedFile $value): void
    {
        $setting = CrudModel::where('key', $key)->first();

        if(!empty($setting->value) && Storage::disk('public')->exists($setting->value))
        {
            Storage::disk('public')->delete($setting->value);
        }

        $mediaPath = $value->store('settings/media', 'public');

        $setting->update(['value' => $mediaPath]);
    }

    /**
     * Update .env file
     *
     * @param array $values
     * @return bool
     */
    private function updateEnvFile(array $values): bool
    {
        if(empty($values)) return false;

        $envFile = app()->environmentFilePath();
        $str     = file_get_contents($envFile);
        $updated = false;

        foreach ($values as $envKey => $envValue) {
            $envValue = '"' . trim($envValue) . '"';
            $envKey = strtoupper($envKey);

            if (strpos($str, "{$envKey}=") !== false) {
                $str = preg_replace("/^{$envKey}=.*$/m", "{$envKey}={$envValue}", $str);
            } else {
                $str .= "\n{$envKey}={$envValue}";
            }

            $updated = true;
        }

        if ($updated) {
            file_put_contents($envFile, $str);
        }

        return $updated;
    }
}
