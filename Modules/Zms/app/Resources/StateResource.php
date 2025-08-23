<?php

namespace Modules\Zms\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id'            => (string) $this->id,
            'name'          => $this->smartTrans('name'),
            'native_name'   => $this->native_name,
            'cities'        => $this->whenLoaded('cities', function () {
                return CityResource::collection($this->cities);
            }),
        ];
    }
}
