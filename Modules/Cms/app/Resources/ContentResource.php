<?php

namespace Modules\Cms\Resources;

use Modules\Cms\Models\Content;
use Illuminate\Http\Resources\Json\JsonResource;
class ContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id'                    => (string) $this->id,
            'type'                  => $this->type,
            'sub_type'              => $this->sub_type,
            'slug'                  => $this->slug,
            $this->mergeWhen(Content::typeHasField($this->type, 'title'), [
                // 'title'             => $this->smartTrans('title'),
            ]),
            $this->mergeWhen(Content::typeHasField($this->type, 'short_description'), [
                'short_description' => $this->smartTrans('short_description'),
            ]),
            $this->mergeWhen(Content::typeHasField($this->type, 'long_description'), [
                'long_description'              => $this->smartTrans('long_description'),
                'logn_description_without_html' => strip_tags($this->smartTrans('long_description')),
            ]),
            $this->mergeWhen(Content::typeHasField($this->type, 'image'), [
                'image_url'         => $this->transImageUrl('content'),
            ]),
            $this->mergeWhen(Content::typeHasField($this->type, 'link'), [
                'link'              => $this->link,
            ]),
            $this->mergeWhen(Content::typeHasField($this->type, 'placement'), [
                'placement_position' => $this->placement_position,
            ]),
            $this->mergeWhen(Content::typeHasField($this->type, 'published_at'), [
                'published_at'      => $this->published_at_format,
            ]),
            'created_at'            => $this->created_at_format,
        ];
    }
}
