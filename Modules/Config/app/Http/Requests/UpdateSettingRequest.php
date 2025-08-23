<?php

namespace Modules\Config\Http\Requests;

use Illuminate\Validation\Rules\File;
use Modules\Base\Http\Requests\BaseRequest;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class UpdateSettingRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $generalSettingRules = [
            'app_name'              => ['required', 'array'],
            'app_name.*'            => ['required', 'string', 'max:255'],
            'app_default_language'  => ['required', 'in:' . implode(',', array_values(LaravelLocalization::getSupportedLanguagesKeys()))],
            'maintenance_mode'      => ['required', 'boolean'],
        ];

        $socialSettingRules = [
            'facebook' => ['nullable', 'url', 'max:255'],
            'twitter'  => ['nullable', 'url', 'max:255'],
            'instagram'=> ['nullable', 'url', 'max:255'],
            'linkedin' => ['nullable', 'url', 'max:255'],
            'youtube'  => ['nullable', 'url', 'max:255'],
            'tiktok'   => ['nullable', 'url', 'max:255'],
        ];

        $contactSettingRules = [
            'phone'     => ['nullable', 'string', 'max:255'],
            'email'     => ['nullable', 'email', 'max:255'],
            'address'   => ['nullable', 'string', 'max:255'],
        ];

        $mediaSettingRules = [
            'app_logo'          => ['nullable', 'image', File::image()
                ->types(config('config.app_logo.types'))
                ->max(config('config.app_logo.max_size') . 'mb')
            ],
            'app_mobile_logo'   => ['nullable', 'image', File::image()
                ->types(config('config.app_logo.types'))
                ->max(config('config.app_logo.max_size') . 'mb')
            ],
            'app_favicon'       => ['nullable', 'image', File::image()
                ->types(config('config.app_favicon.types'))
                ->max(config('config.app_favicon.max_size') . 'mb')
            ],
            'email_logo'    => ['nullable', 'image', File::image()
                ->types(config('config.app_logo.types'))
                ->max(config('config.app_logo.max_size') . 'mb')
            ],
            'app_placeholder'   => ['nullable', 'image', File::image()->types(['png', 'jpg', 'jpeg', 'webp'])->max('2mb')],
        ];

        $developerSettingRules = app('owner') ? [
            'session_lifetime'          => ['required', 'integer', 'min:60'],
            'allow_debug_for_custom_ip' => ['required', 'boolean'],
            'custom_ips'                => ['nullable', 'string', 'regex:/^(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})(,\s*\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})*$/'],
        ] : [];

        return array_merge($generalSettingRules, $socialSettingRules, $contactSettingRules, $mediaSettingRules, $developerSettingRules);
    }

    public function after(): array
    {
        return [
            function ($validator) {
                if(isset($this->custom_ip) && !empty($this->custom_ip))
                {
                    $ips = explode(',', $this->custom_ip);
                    foreach($ips as $ip)
                    {
                        if(!filter_var(trim($ip), FILTER_VALIDATE_IP))
                        {
                            $validator->errors()->add($this->custom_ip, trans('validation.ip', ['attribute' => $this->custom_ip]));
                        }
                    }
                }
            }
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
