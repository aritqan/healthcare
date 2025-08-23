<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Base\Enums\Gender;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;
use Modules\Base\Http\Requests\BaseRequest;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class UpdateAuthInfoRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $adminId = auth('admin')->id();

        $rules = [
            'avatar'                => ['nullable', 'image', File::image()->types(config('base.file.image.accepted_types'))->max(config('base.file.image.max_size') . 'mb')],
            'avatar_remove'         => ['nullable', 'boolean'],
            'lang'                  => ['required', 'in:' . implode(',', LaravelLocalization::getSupportedLanguagesKeys())],
            'full_name'             => ['required', 'string', 'min:3', 'max:255'],
            'username'              => ['required', 'string', 'min:3', 'max:255', 'regex:/^[a-z0-9_]+$/', Rule::unique('admins', 'username')->ignore($adminId)],
            'email'                 => ['required', 'email', 'max:255', Rule::unique('admins', 'email')->ignore($adminId)],
            'phone_number'          => ['nullable', 'string', 'min:5', 'max:255', Rule::unique('admins', 'phone_number')->ignore($adminId)],
            'gender'                => ['required', 'in:' . implode(',', Gender::all())],
            'current_password'      => ['required_with:password'],
            'password'              => ['required_with:current_password'],
        ];

        if($this->filled('password')) {
            array_push($rules['current_password'], 'current_password:admin');
            array_push($rules['password'], Password::defaults(), 'confirmed');
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
