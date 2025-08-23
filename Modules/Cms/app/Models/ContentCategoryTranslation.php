<?php

namespace Modules\Cms\Models;

use Spatie\Sluggable\HasSlug;
use Modules\Base\Models\BaseModel;
use Spatie\Sluggable\SlugOptions;

class ContentCategoryTranslation extends BaseModel
{
    use HasSlug;

    protected $fillable = [
        'content_category_id',
        'locale',
        'title',
        'slug',
    ];

    public $timestamps = false;

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->startSlugSuffixFrom(2);
    }

}
