<?php

namespace Modules\Notification\Models;

use Modules\Base\Models\BaseModel;

class NotificationTranslation extends BaseModel
{
    protected $fillable = [
        'notification_id',
        'locale',
        'title',
        'body',
        'long_template',
    ];

    public $timestamps = false;

}
