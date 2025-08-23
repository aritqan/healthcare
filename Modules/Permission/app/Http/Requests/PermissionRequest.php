<?php

namespace Modules\Permission\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Modules\Base\Http\Requests\BaseRequest;

class PermissionRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        if($this->permission_type == 'group_permission') {
            $rules = [
                'ability_group_icon'    => ['required', 'string', 'min:5', 'max:255'],
                'ability_group_code'    => ['required', 'string', 'min:2', 'max:255', 'regex:/^[A-Z_]+$/', Rule::unique('ability_groups', 'code')->ignore($this->model)],
                'abilities'             => ['required', 'array'],
                'abilities.*'           => ['required', 'string', 'min:2', 'max:255', 'regex:/^[A-Z_]+$/', Rule::unique('abilities', 'name')->ignore($this->model, 'ability_group_id')],
                'ability_types'         => ['required', 'array'],
                'ability_types.*'       => ['required', 'string'],
            ];
        } else {
            $rules = [
                'permission_group_code' => ['required', 'exists:ability_groups,code'],
                'permission_name'       => ['required', 'string', 'min:2', 'max:255', Rule::unique('abilities', 'name')->ignore($this->model)],
            ];
        }

        $rules['title']       = ['required', 'array'];
        $rules['description'] = ['array'];

        return $rules;
    }

    public function after(): array
    {
        return [
            function ($validator) {
                $this->validateBaseInput(validator: $validator, data: $this->title, inputName: 'title', atLeastOneLocaleWithSize: true);
                $this->validateBaseInput(validator: $validator, data: $this->description, inputName:'description', atLeastOneLocaleWithSize: false, textarea: true);

                if($this->permission_type == 'sigle_permission') {
                    $permissionName = $this->permission_name;
                    $permissionGroupCode = $this->permission_group_code;

                    if(! Str::endsWith($permissionName, '_' . $permissionGroupCode)) {
                        $validator->errors()->add('permission_name', trans('admin::validation.permission_name_end_with_group_code', ['group_code' => '_' . $permissionGroupCode]));
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
