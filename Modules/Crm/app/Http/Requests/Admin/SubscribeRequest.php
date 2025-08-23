<?php

namespace Modules\Crm\Http\Requests\Admin;

use Modules\Base\Http\Requests\BaseRequest;

class SubscribeRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            //
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
