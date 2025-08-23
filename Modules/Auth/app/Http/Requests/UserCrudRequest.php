<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Modules\Base\Http\Requests\BaseRequest;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Modules\Admin\Enums\AdminStatus;

class UserCrudRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'first_name'    => ['required', 'string', 'max:255'],
            'last_name'     => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->model)],
            'phone_number'  => ['required', 'string', 'min:9', 'max:20', Rule::unique('users', 'phone_number')->ignore($this->model)],
            'password'      => ['confirmed', Password::defaults()],
            'lang'          => ['required', 'in:' . implode(',', LaravelLocalization::getSupportedLanguagesKeys())],
            'status'        => ['required', 'in:' . implode(',', AdminStatus::all())],
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
