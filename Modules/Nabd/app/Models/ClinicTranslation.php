<?php

namespace Modules\Nabd\Models;

use Modules\Base\Models\BaseModel;

class ClinicTranslation extends BaseModel
{
    protected $fillable = [
        'clinic_id',
        'locale',
        'name'
    ];

    public $timestamps = false;

}
