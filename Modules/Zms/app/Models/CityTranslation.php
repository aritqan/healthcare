<?php

namespace Modules\Zms\Models;

use Modules\Base\Models\BaseModel;


class CityTranslation extends BaseModel
{
    protected $fillable = [
        'city_id',
        'locale',
        'name',
    ];

    public $timestamps = false;
}
