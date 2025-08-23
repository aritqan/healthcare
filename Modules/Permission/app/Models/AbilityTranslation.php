<?php

namespace Modules\Permission\Models;

use Modules\Base\Models\BaseModel;

class AbilityTranslation extends BaseModel
{
    protected $fillable = [
        'ability_id',
        'locale',
        'title',
        'description',
    ];

    public $timestamps = false;
}
