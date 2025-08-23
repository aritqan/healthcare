<?php

namespace Modules\Notification\Listeners;

use Modules\Notification\Enums\NotificationStatuses;
use Modules\Notification\Events\FcmNotificationFailed;

class UpdateFcmNotificationStatusFailed
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(FcmNotificationFailed $event): void
    {
        $notification   = $event->notification;
        $forMobile      = $event->forMobile;
        $isTopic        = $event->isTopic;

        if($isTopic) {
            $notification->notificationChannels()->where(function($q) {
                $q->where('is_fcm_mobile', true)->orWhere('is_fcm_web', true);
            })->update(['status' => NotificationStatuses::DELIVERED]);

            return;
        }

        if($forMobile) {
            $notification->notificationChannels()->where('is_fcm_mobile', true)->update(['status' => NotificationStatuses::FAILED]);
        } else {
            $notification->notificationChannels()->where('is_fcm_web', true)->update(['status' => NotificationStatuses::FAILED]);
        }
    }
}
