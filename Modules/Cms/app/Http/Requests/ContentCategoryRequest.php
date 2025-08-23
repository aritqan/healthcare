<?php

namespace Modules\Cms\Http\Requests;

use Modules\Base\Http\Requests\BaseRequest;

class ContentCategoryRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'parent_id'         => ['nullable', 'integer', 'exists:content_categories,id'],
            'can_be_deleted'    => ['required', 'boolean'],
            'title'             => ['required', 'array'],
        ];

        return $rules;
    }

    public function after(): array
    {
        return [
            function ($validator) {
                $this->validateBaseInput(validator:$validator, data:$this->title, inputName: 'title', atLeastOneLocaleWithSize:true);
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
