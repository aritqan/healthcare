<?php

namespace Modules\Notification\Models;

use Modules\Base\Models\BaseModel;

class NotificationTemplateTranslation extends BaseModel
{
    protected $fillable = [
        'notification_template_id',
        'locale',
        'title',
        'description',
        'short_template',
        'long_template',
    ];

    public $timestamps = false;

}
