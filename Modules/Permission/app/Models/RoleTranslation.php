<?php

namespace Modules\Permission\Models;

use Modules\Base\Models\BaseModel;

class RoleTranslation extends BaseModel
{
    protected $fillable = [
        'role_id',
        'locale',
        'title',
        'description',
    ];

    public $timestamps = false;
}
