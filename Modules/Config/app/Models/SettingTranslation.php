<?php

namespace Modules\Config\Models;

use Modules\Base\Models\BaseModel;

class SettingTranslation extends BaseModel
{
    protected $fillable = [
        'setting_id',
        'locale',
        'title',
        'description',
        'trans_value',
    ];

    public $timestamps = false;
}
