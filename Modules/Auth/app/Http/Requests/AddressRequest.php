<?php

namespace Modules\Auth\Http\Requests;

use Modules\Base\Http\Requests\BaseRequest;

class AddressRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'user_id'       => ['required', 'integer', 'exists:users,id'],
            'country_id'    => ['required', 'integer', 'exists:countries,id'],
            'state_id'      => ['required', 'integer', 'exists:states,id'],
            'city_id'       => ['required', 'integer', 'exists:cities,id'],
            'is_default'    => ['boolean'],
            'building'      => ['required', 'string', 'max:255'],
            'street'        => ['required', 'string', 'max:255'],
            'floor'         => ['required', 'string', 'max:3'],
            'apartment'     => ['required', 'string', 'max:5'],
            'address'       => ['required', 'string', 'max:500'],
        ];

        return $rules;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'user_id'       => request()->user()->id,
        ]);
    }


    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
