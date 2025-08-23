<?php

namespace Modules\Cms\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Cms\Models\Content;
use Illuminate\Validation\Rules\File;
use Modules\Base\Http\Requests\BaseRequest;

class ContentRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return $this->handleTypeFieldsValidationRules();
    }

    public function after(): array
    {
        return [
            function ($validator) {
                $fields = $this->getContentFields();

                foreach ($fields as $fieldName => $field) {
                    if($fieldName == 'title') {
                        $this->validateBaseInput(validator:$validator, data:$this->title, inputName: 'title', atLeastOneLocaleWithSize:true);
                    } else if($fieldName == 'short_description') {
                        $this->validateBaseInput(validator:$validator, data:$this->short_description, inputName:'short_description', atLeastOneLocaleWithSize:true, textarea:true);
                    } else if($fieldName == 'long_description') {
                        $this->validateAtLeastOneLocale(validator:$validator, data:$this->long_description ?? [], inputName:'long_description');
                    } else if($fieldName == 'image' && $this->isCreate()) {
                        $this->validateAtLeastOneLocale(validator:$validator, data:$this->image ?? [], inputName:'image');
                    }
                }

                $cantAcceptFieldsWithoutTitle = [
                    'short_description',
                    'long_description',
                    'image',
                ];

                $this->validateFieldsWithoutTitle($validator, $this->all(), 'title', $cantAcceptFieldsWithoutTitle);
            }
        ];
    }

    private function getContentFields(): array
    {
        return Content::getTypeFields($this->getType());
    }

    private function getType(): string
    {
        return $this->input('type');
    }

    private function handleTypeFieldsValidationRules(): array
    {
        $rules = [
            'type'      => ['required', 'string', Rule::in(Content::types()->keys())],
            'sub_type'  => ['nullable', 'string'],
        ];

        if(Content::typeHasField($this->getType(), 'title')) {
            $rules['title'] = ['required', 'array'];
        }

        if(Content::typeHasField($this->getType(), 'short_description')) {
            $rules['short_description'] = ['required', 'array'];
        }

        if(Content::typeHasField($this->getType(), 'long_description')) {
            $rules['long_description'] = ['required', 'array'];
        }

        if(Content::typeHasField($this->getType(), 'image') && $this->isCreate()) {
            $rules['image'] = ['required', 'array'];
        }

        if(Content::typeHasField($this->getType(), 'image')) {
            $rules['image.*'] = [File::image()->types(config('base.file.image.accepted_types'))->max(config('base.file.image.max_size') . 'mb')];
        }

        if(Content::typeHasField($this->getType(), 'can_be_deleted')) {
            $rules['can_be_deleted'] = ['required', 'boolean'];
        }

        if(Content::typeHasField($this->getType(), 'slug') && $this->isCreate()) { // If it's create, slug is required.Because when updating, slug is cant be updated.
            $rules['slug'] = ['required', 'string', 'regex:/^[a-z0-9-]+$/', Rule::unique('contents', 'slug')->ignore($this->model)];
        }

        if(Content::typeHasField($this->getType(), 'link')) {
            $rules['link'] = [Content::isFieldRequired($this->getType(), 'link') ? 'required' : 'nullable', 'string', 'url'];
        }

        if(Content::typeHasSelectField($this->getType())) {
            $fields = Content::getSelectField($this->getType());

            foreach ($fields as $key => $field) {
                switch($key) {
                    case 'placement':
                        $rules['placement'] = ['required', 'string', 'in:home'];
                        break;
                    case 'tags':
                        $rules['tags']      = ['nullable', 'array'];
                        $rules['tags.*']    = ['required', 'integer', 'exists:content_tags,id'];
                        break;
                }
            }
        }

        if(Content::typeHasField($this->getType(), 'published_at')) {
            $rules['published_at'] = ['required', 'date'];
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
