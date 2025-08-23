<?php

namespace Modules\Zms\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id'                => (string) $this->id,
            'name'              => $this->smartTrans('name'),
            'native_name'       => $this->native_name,
            'iso2'              => $this->iso2,
            'iso3'              => $this->iso3,
            'phone_code'        => $this->phone_code,
            'currency'          => $this->currency,
            'currency_symbol'   => $this->currency_symbol,
            'states'            => $this->whenLoaded('states', function () {
                return StateResource::collection($this->states);
            }),
        ];
    }
}
