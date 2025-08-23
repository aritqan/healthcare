<?php

namespace Modules\Crm\Http\Requests\Api;

use Modules\Base\Http\Requests\BaseRequest;

class SubscribeRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'email'     => ['required', 'email', 'unique:subscribes,email'],
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
