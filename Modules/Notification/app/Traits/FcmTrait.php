<?php

namespace Modules\Notification\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Modules\Notification\Models\Notification;
use Modules\Notification\Models\FirebaseToken;
use Modules\Notification\Enums\NotificationChannels;

trait FcmTrait
{
    public function fcmTokens()
    {
        return $this->morphMany(FirebaseToken::class, 'tokenable');
    }

    public function mobileTokens()
    {
        return $this->fcmTokens()->where('type', NotificationChannels::FCM_MOBILE);
    }

    public function webTokens()
    {
        return $this->fcmTokens()->where('type', NotificationChannels::FCM_WEB);
    }

    public function allNotifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }


    public function webNotifications()
    {
        return $this->allNotifications()->whereRelation('notificationChannels', 'is_fcm_web', true);
    }

    public function fcmTopic() : Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->fcmTokens()->whereNotNull('extra_data->topic')->first()?->extra_data['topic'] ?? null,
        );
    }

    public function unReadWebNotificationsCount() : Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->webNotifications()->whereRelation('notificationChannels', fn ($query) => $query->unRead())->count(),
        );
    }

    public function deliveredWebNotificationsCount() : Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->webNotifications()->whereRelation('notificationChannels', fn ($query) => $query->delivered())->count(),
        );
    }
}
