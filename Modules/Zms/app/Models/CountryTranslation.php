<?php

namespace Modules\Zms\Models;

use Modules\Base\Models\BaseModel;

class CountryTranslation extends BaseModel
{
    protected $fillable = [
        'country_id',
        'locale',
        'name',
    ];

    public $timestamps = false;
}
