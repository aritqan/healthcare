<?php

namespace Modules\Crm\Http\Requests\Api;

use Modules\Base\Http\Requests\BaseRequest;

class ContactusRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'first_name'   => ['required', 'string', 'max:255'],
            'last_name'    => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email' , 'max:255'],
            'phone'        => ['required', 'string', 'max:255'],
            'message'      => ['required', 'string', 'min:10', 'max:1000'],
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
