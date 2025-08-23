<?php

namespace Modules\Base\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BaseJsonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [];
    }
}
