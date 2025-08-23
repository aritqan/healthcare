<?php

namespace Modules\Auth\Resources;

use Modules\Base\Resources\BaseJsonResource;
class AddressResource extends BaseJsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id'            => (string) $this->id,
            'is_default'    => $this->is_default,
            // 'title'         => $this->title,
            // 'first_name'    => $this->first_name,
            // 'last_name'     => $this->last_name,
            // 'phone_number'  => $this->phone_number,
            // 'email'         => $this->email,
            'building'      => $this->building,
            'street'        => $this->street,
            'floor'         => $this->floor,
            'apartment'     => $this->apartment,
            'address'       => $this->address,
            'country'       => $this->whenLoaded('country', function () {
                return $this->country->name;
            }),
            'state'         => $this->whenLoaded('state', function () {
                return $this->state->name;
            }),
            'city'          => $this->whenLoaded('city', function () {
                return $this->city->name;
            }),
        ];
    }
}
