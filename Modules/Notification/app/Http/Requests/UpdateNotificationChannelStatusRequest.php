<?php

namespace Modules\Notification\Http\Requests;

use Modules\Base\Http\Requests\BaseRequest;
use Modules\Notification\Enums\NotificationChannels;
use Modules\Notification\Enums\NotificationStatuses;

class UpdateNotificationChannelStatusRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'status'                => ['required', 'in:' . implode(',', NotificationStatuses::all())],
            'channel'               => ['required', 'in:' . implode(',', NotificationChannels::all())],
            'updateAll'             => ['required_without:notificationChannelId', 'boolean'],
            'notificationChannelId' => ['required_without:updateAll', 'integer', 'exists:notification_channels,id'],
        ];

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
