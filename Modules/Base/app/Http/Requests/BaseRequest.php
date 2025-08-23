<?php

namespace Modules\Base\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Base\Trait\ValidationForInputString;

class BaseRequest extends FormRequest
{
    use ValidationForInputString;

    protected function isUpdate(): bool
    {
        return in_array($this->method(), ['PUT', 'PATCH']);
    }

    protected function isCreate(): bool
    {
        return in_array($this->method(), ['POST']);
    }

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
