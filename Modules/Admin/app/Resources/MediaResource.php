<?php

namespace Modules\Admin\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'file_name'     => $this->file_name,
            'mime_type'     => $this->mime_type,
            'size'          => $this->size,
            'url'           => $this->getUrl(),
        ];
    }
}
