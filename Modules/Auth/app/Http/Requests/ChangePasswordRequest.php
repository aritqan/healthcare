<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Validation\Rules\Password;
use Modules\Base\Http\Requests\BaseRequest;

class ChangePasswordRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'old_password'  => ['required', 'string'],
            'new_password'  => ['required', 'confirmed', Password::defaults()],
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
