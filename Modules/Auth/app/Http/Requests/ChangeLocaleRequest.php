<?php

namespace Modules\Auth\Http\Requests;

use Modules\Base\Http\Requests\BaseRequest;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ChangeLocaleRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'lang' => ['required', 'in:' . implode(',', LaravelLocalization::getSupportedLanguagesKeys())],
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
