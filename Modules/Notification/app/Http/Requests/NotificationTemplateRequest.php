<?php

namespace Modules\Notification\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Base\Http\Requests\BaseRequest;
use Modules\Notification\Enums\NotificationChannels;
use Modules\Notification\Enums\NotificationPriority;

class NotificationTemplateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'name'                  => ['required', 'string', 'min:3', 'max:255', 'regex:/^[a-z_\-]+$/', Rule::unique('notification_templates', 'name')->ignore($this->model)],
            'channels'              => ['required', 'array'],
            'channels.*'            => ['required', 'in:' . implode(',', NotificationChannels::all())],
            'variables'             => ['required', 'string', 'max:255', 'regex:/^[a-z_]+(,[a-z_]+)*$/'],
            'priority'              => ['required', 'in:' . implode(',', NotificationPriority::all())],
            'title'                 => ['required', 'array'],
            'description'           => ['required', 'array'],
            'short_template'        => ['required', 'array'],
            'long_template'         => ['required', 'array'],
        ];

        return $rules;
    }

    public function after(): array
    {
        return [
            function ($validator) {
                $this->validateBaseInput(validator:$validator, data:$this->title, inputName: 'title', atLeastOneLocaleWithSize:true);
                $this->validateBaseInput(validator:$validator, data:$this->description, inputName:'description', atLeastOneLocaleWithSize:true, textarea:true);
                $this->validateBaseInput(validator:$validator, data:$this->short_template, inputName:'short_template', atLeastOneLocaleWithSize:true, textarea:true);
                $this->validateBaseInput(validator:$validator, data:$this->long_template, inputName:'long_template', atLeastOneLocaleWithSize:true, longText:true);

                $cantAcceptFieldsWithoutTitle = [
                    'description',
                    'short_template',
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
