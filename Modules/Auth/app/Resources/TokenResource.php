<?php

namespace Modules\Auth\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TokenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'access_token'  => $this->plainTextToken,
            'expires_at'    => Carbon::parse($this->accessToken?->expires_at)->toDateTimeString(),
        ];
    }
}
