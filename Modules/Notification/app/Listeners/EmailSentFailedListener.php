<?php

namespace Modules\Notification\Listeners;

use Modules\Notification\Enums\NotificationStatuses;
use Modules\Notification\Events\EmailSentFailedEvent;

class EmailSentFailedListener
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
    public function handle(EmailSentFailedEvent $event): void
    {
        $notification = $event->notification;

        $notification->notificationChannels()->where('is_email', true)->update(['status' => NotificationStatuses::FAILED]);
    }
}
