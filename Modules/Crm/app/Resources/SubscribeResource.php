<?php

namespace Modules\Crm\Resources;

use Modules\Base\Resources\BaseJsonResource;

class SubscribeResource extends BaseJsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => (string) $this->id,
        ];
    }
}
