<?php

namespace Modules\Crm\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Modules\Base\Http\Requests\BaseRequest;
use Modules\Crm\Models\Contactus;

class ContactusRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $model = Contactus::findOrFail($this->route('model'));

        return [
            'reply' => [Rule::requiredIf(fn() => $model->canReply()), 'string', 'min:5', 'max:500'],
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
