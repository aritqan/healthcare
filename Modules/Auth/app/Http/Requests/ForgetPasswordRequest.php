<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Validation\Rules\Password;
use Modules\Base\Http\Requests\BaseRequest;

class ForgetPasswordRequest extends BaseRequest
{
    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'phone_number' => getCountryPhoneCode() . $this->phone_number,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'phone_number'  =>  ['required', 'string', 'exists:users,phone_number'],
            'new_password'  =>  ['required', 'confirmed', Password::defaults()],
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
