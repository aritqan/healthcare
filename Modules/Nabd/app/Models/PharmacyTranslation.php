<?php

namespace Modules\Nabd\Models;

use Modules\Base\Models\BaseModel;

class PharmacyTranslation extends BaseModel
{
    protected $fillable = [
        'pharmacy_id',
        'locale',
        'name',
    ];

    public $timestamps = false;

}
