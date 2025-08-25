<?php

namespace Modules\Nabd\Models;

use Modules\Base\Models\BaseModel;

class MedicalSpecialtyTranslation extends BaseModel
{
    protected $fillable = [
        'medical_specialty_id',
        'locale',
        'name',
    ];

    public $timestamps = false;

}
