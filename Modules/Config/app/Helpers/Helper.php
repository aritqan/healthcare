<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Modules\Config\Enums\SettingTypes;
use Modules\Config\Models\Setting;

if(! function_exists('getSetting')) {

    /**
     * Get setting value from cache or database
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function getSetting(string $key, mixed $default = null): mixed
    {
        if(! Schema::hasTable('settings')) {
            return $default;
        }

        $setting = Cache::get($key);

        if(! $setting) {
            $data = Setting::where('key', $key)->first();
            if($data && ! is_null($data->value)) {

                if($data->translatable) {
                    $setting = $data->smartTrans('trans_value');
                } elseif($data->type == SettingTypes::IMAGE || $data->type == SettingTypes::FILE) {
                    $setting = asset('storage/' . $data->value);
                } else {
                    $setting = $data->value;
                }

                Cache::forever($key, $setting);
            } else {
                $setting = $default;
            }
        }

        return $setting;
    }
}
