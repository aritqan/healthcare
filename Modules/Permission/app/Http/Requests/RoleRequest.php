<?php

namespace Modules\Permission\Http\Requests;

use Modules\Base\Http\Requests\BaseRequest;

class RoleRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'permissions'   => ['nullable', 'array'],
            'permissions.*' => ['nullable', 'integer', 'exists:abilities,id'],
            'code'          => ['required', 'string', 'min:2', 'max:50', 'regex:/^[A-Z_]+$/', 'unique:roles,name'],
            'title'         => ['required', 'array'],
            'description'   => ['required', 'array'],
        ];

        if($this->isUpdate()) {
            unset($rules['code']);
        }

        return $rules;
    }

    public function after(): array
    {
        return [
            function ($validator) {
                $this->validateBaseInput(validator:$validator, data:$this->title, inputName: 'title', atLeastOneLocaleWithSize:true);
                $this->validateBaseInput(validator:$validator, data:$this->description, inputName:'description', atLeastOneLocaleWithSize:true, textarea:true);
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
