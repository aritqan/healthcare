<?php

namespace Modules\Zms\Models;

use Modules\Base\Models\BaseModel;

class StateTranslation extends BaseModel
{
    protected $fillable = [
        'state_id',
        'locale',
        'name',
    ];

    public $timestamps = false;
}
