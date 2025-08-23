<?php

namespace Modules\Permission\Models;

use Modules\Base\Models\BaseModel;

class AbilityGroupTranslation extends BaseModel
{
    protected $fillable = [
        'ability_groub_id',
        'locale',
        'title',
        'description',
    ];

    public $timestamps = false;
}
