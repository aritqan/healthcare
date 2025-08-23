<?php

namespace Modules\Zms\Http\Requests;

use Modules\Base\Http\Requests\BaseRequest;

class UpdateCountry extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'native_name'       => ['required', 'string', 'max:255'],
            'phone_code'        => ['required', 'regex:/^\+?\d{1,3}(-\d{1,4})?$/', 'unique:countries,phone_code,' . $this->model],
            'iso2'              => ['required', 'string', 'regex:/^[A-Z]{2}$/', 'unique:countries,iso2,' . $this->model],
            'iso3'              => ['required', 'string', 'regex:/^[A-Z]{3}$/', 'unique:countries,iso3,' . $this->model],
            'currency'          => ['required', 'string', 'max:255'],
            'currency_symbol'   => ['required', 'string', 'max:255'],
            'lat'               => ['required', 'numeric', 'min:-90', 'max:90'],
            'lng'               => ['required', 'numeric', 'min:-180', 'max:180'],
            'name'              => ['required', 'array'],
        ];

        return $rules;
    }

    public function after(): array
    {
        return [
            function ($validator) {
                $this->validateBaseInput(validator:$validator, data:$this->name, inputName: 'name', atLeastOneLocaleWithSize:true);
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

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'phone_code.regex'  => trans('admin::validation.phone_code.regex'),
            'iso2.regex'        => trans('admin::validation.iso2.regex'),
            'iso3.regex'        => trans('admin::validation.iso3.regex'),
        ];
    }
}
