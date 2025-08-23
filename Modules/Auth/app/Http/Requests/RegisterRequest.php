<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Validation\Rules\Password;
use Modules\Base\Http\Requests\BaseRequest;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class RegisterRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'first_name'    => ['required', 'string', 'max:255'],
            'last_name'     => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone_number'  => ['required', 'string', 'min:9', 'max:20', 'unique:users,phone_number'],
            'password'      => ['required', 'confirmed', Password::defaults()],
            'lang'          => ['required', 'in:' . implode(',', LaravelLocalization::getSupportedLanguagesKeys())],
        ];

        return $rules;
    }

    protected function prepareForValidation()
    {
        if(!empty($this->phone_number)) {
            // check if getCountryPhoneCode() starts with +
            if (strpos(getCountryPhoneCode(), '+') === 0) {
                $this->merge([
                    'phone_number' => getCountryPhoneCode() . $this->phone_number,
                ]);
            } else {
                $this->merge([
                    'phone_number' => '+' . getCountryPhoneCode() . $this->phone_number,
                ]);
            }
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
