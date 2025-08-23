<?php

namespace Modules\Zms\Http\Requests;

use Modules\Base\Http\Requests\BaseRequest;

class UpdateCity extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'native_name'       => ['required', 'string', 'max:255'],
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
}
