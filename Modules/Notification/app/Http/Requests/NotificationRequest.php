<?php

namespace Modules\Notification\Http\Requests;

use Illuminate\Support\Facades\DB;
use Modules\Base\Http\Requests\BaseRequest;
use Modules\Notification\Enums\NotificationChannels;
use Modules\Notification\Enums\NotificationPriority;

class NotificationRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'group'             => ['required', 'string'],
            'users_id'          => ['array'],
            'channels'          => ['required', 'array'],
            'channels.*'        => ['required', 'in:' . implode(',', NotificationChannels::all())],
            'priority'          => ['required', 'in:' . implode(',', NotificationPriority::all())],
            'link'              => ['nullable', 'url'],
            'title'             => ['required', 'array'],
            'body'              => ['required', 'array'],
            'long_template'     => ['required', 'array'],
        ];

        if (isset($this->users_id) && is_array($this->users_id)) {
            foreach ($this->users_id as $key => $userId) {
                $rules["users_id.$key"] = [
                    'required',
                    'integer',
                    function ($attribute, $value, $fail) {
                        $existsInUsers  = DB::table('users')->where('id', $value)->exists();
                        $existsInAdmins = DB::table('admins')->where('id', $value)->exists();

                        if (!$existsInUsers && !$existsInAdmins) {
                            $fail('The ' . $attribute . ' must exist in either users or admins table.');
                        }
                    },
                ];
            }
        }

        if (isset($this->channels) && is_array($this->channels)) {
            $fcmChannels        = [NotificationChannels::FCM_MOBILE, NotificationChannels::FCM_WEB];
            $hasFcmChannel      = count(array_intersect($fcmChannels, $this->channels)) > 0;
            $hasUsersSelected   = isset($this->users_id) && is_array($this->users_id) && count($this->users_id) > 0;

            if ($hasFcmChannel && !$hasUsersSelected) {
                $rules['channels'] = [
                    'required',
                    'array',
                    function ($attribute, $value, $fail) use ($fcmChannels) {
                        if (array_diff($value, $fcmChannels)) {
                            $fail('When selecting FCM_MOBILE or FCM_WEB, you cannot choose other channels without selecting users.');
                        }
                    },
                ];
            }
        }

        return $rules;
    }

    public function after(): array
    {
        $channels            = is_array($this->channels) ? $this->channels : [];
        $bodyChannels        = [NotificationChannels::FCM_MOBILE, NotificationChannels::FCM_WEB, NotificationChannels::SMS];
        $hasBodyChannel      = count(array_intersect($bodyChannels, $channels)) > 0;
        $longTemplateChannel = [NotificationChannels::MAIL];
        $hasLongTemplate     = count(array_intersect($longTemplateChannel, $channels)) > 0;

        return [
            function ($validator) use($hasBodyChannel, $hasLongTemplate) {
                $this->validateBaseInput(validator:$validator, data:$this->title        , inputName:'title'         , atLeastOneLocaleWithSize:true);
                $this->validateBaseInput(validator:$validator, data:$this->body         , inputName:'body'          , atLeastOneLocaleWithSize:$hasBodyChannel , textarea:true);
                $this->validateBaseInput(validator:$validator, data:$this->long_template, inputName:'long_template' , atLeastOneLocaleWithSize:$hasLongTemplate , longText:true);

                $cantAcceptFieldsWithoutTitle = [
                    'body',
                    'long_template',
                ];

                $this->validateFieldsWithoutTitle($validator, $this->all(), 'title', $cantAcceptFieldsWithoutTitle);
            }
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
