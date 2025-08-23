<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Base\Enums\Gender;
use Illuminate\Validation\Rules\File;
use Modules\Admin\Enums\AdminStatus;
use Illuminate\Validation\Rules\Password;
use Modules\Base\Http\Requests\BaseRequest;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class CreateOrUpdateAdminRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'avatar'                => ['nullable', 'image', File::image()->types(config('base.file.image.accepted_types'))->max(config('base.file.image.max_size') . 'mb')],
            'avatar_remove'         => ['nullable', 'boolean'],
            'status'                => ['required', 'in:' . implode(',', AdminStatus::all())],
            'lang'                  => ['required', 'in:' . implode(',', LaravelLocalization::getSupportedLanguagesKeys())],
            'full_name'             => ['required', 'string', 'min:5', 'max:255'],
            'username'              => ['required', 'string', 'min:5', 'max:255', 'regex:/^[a-z0-9_]+$/', Rule::unique('admins', 'username')->ignore($this->model)],
            'email'                 => ['required', 'email', 'max:255', Rule::unique('admins', 'email')->ignore($this->model)],
            'phone_number'          => ['required', 'string', 'min:5', 'max:255', Rule::unique('admins', 'phone_number')->ignore($this->model)],
            'password'              => ['confirmed', Password::defaults()],
            'gender'                => ['required', 'in:' . implode(',', Gender::all())],
            'role'                  => ['required', 'exists:roles,id'],
        ];

        if($this->isUpdate()) {
            array_push($rules['password'], 'nullable');
        } else {
            array_push($rules['password'], 'required');
        }

        return $rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
