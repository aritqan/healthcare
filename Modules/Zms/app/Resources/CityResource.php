<?php

namespace Modules\Zms\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
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
        ];
    }
}
