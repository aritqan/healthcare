<?php

namespace Modules\Config\Http\Requests;

use Modules\Config\Enums\SettingTypes;
use Modules\Config\Enums\SettingGroups;
use Modules\Base\Http\Requests\BaseRequest;

class CreateSettingRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'key'           => ['required', 'string', 'min:2', 'max:255', 'regex:/^[a-z0-9_]+$/', 'unique:settings,key'],
            'order'         => ['required', 'integer', 'min:1'],
            'group'         => ['required', 'string', 'in:' . implode(',', array_keys(SettingGroups::getGroups()))],
            'type'          => ['required', 'string', 'in:' . implode(',', SettingTypes::all())],
            'is_required'   => ['required', 'boolean'],
            'translatable'  => ['required', 'boolean'],
            'options'       => ['required_if:type,' . SettingTypes::SELECT, 'json'],
            'title'         => ['required', 'array'],
            'description'   => ['nullable', 'array'],
        ];

        return $rules;
    }

    public function after(): array
    {
        return [
            function ($validator) {
                $this->validateBaseInput(validator:$validator, data:$this->title, inputName: 'title', atLeastOneLocaleWithSize:true);
                $this->validateBaseInput(validator:$validator, data:$this->description, inputName:'description', atLeastOneLocaleWithSize:false, textarea:true);
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
