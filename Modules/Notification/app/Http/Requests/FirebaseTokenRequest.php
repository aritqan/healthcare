<?php

namespace Modules\Notification\Http\Requests;

use Modules\Base\Http\Requests\BaseRequest;
use Modules\Notification\Enums\NotificationChannels;

class FirebaseTokenRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'token'         => ['required', 'string'],
        ];

        if(request()->is('api/*')) {
            $rules['extra_data'] = ['required', 'array'];
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
