<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Validation\Rules\Password;
use Modules\Base\Http\Requests\BaseRequest;

class UpdatePasswordRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'password'   => ['confirmed', Password::defaults()],
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
