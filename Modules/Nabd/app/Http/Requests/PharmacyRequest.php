<?php

namespace Modules\Nabd\Http\Requests;

use Modules\Base\Http\Requests\BaseRequest;

class PharmacyRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'state_id'  => ['required', 'integer', 'exists:states,id'],
            'name'      => ['required', 'array'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function after(): array
    {
        return [
            function ($validator) {
                $this->validateBaseInput(validator:$validator, data:$this->name , inputName:'name', atLeastOneLocaleWithSize:true);
            }
        ];
    }
}
