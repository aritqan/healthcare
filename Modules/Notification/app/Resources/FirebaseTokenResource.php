<?php

namespace Modules\Notification\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FirebaseTokenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id'            => (string) $this->id,
            'token'         => $this->token,
            'type'          => $this->type,
            'extra_data'    => $this->extra_data,
        ];
    }
}
