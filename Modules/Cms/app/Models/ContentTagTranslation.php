<?php

namespace Modules\Cms\Models;

use Modules\Base\Models\BaseModel;

class ContentTagTranslation extends BaseModel
{
    protected $fillable = [
        'content_tag_id',
        'locale',
        'title',
    ];

    public $timestamps = false;

}
