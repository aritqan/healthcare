<?php

namespace Modules\Auth\Resources;

use Modules\Admin\Enums\AdminStatus;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id'            => (string) $this->id,
            'status'        => (string) AdminStatus::getStatuses()[$this->status],
            'first_name'    => (string) $this->first_name,
            'last_name'     => (string) $this->last_name,
            'email'         => (string) $this->email,
            'phone_number'  => (string) $this->phone_number,
            'created_at'    => (string) $this->created_at,
        ];
    }
}
