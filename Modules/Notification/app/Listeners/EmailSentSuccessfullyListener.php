<?php

namespace Modules\Notification\Listeners;

use Modules\Notification\Enums\NotificationStatuses;
use Modules\Notification\Events\EmailSentSuccessfullyEvent;

class EmailSentSuccessfullyListener
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
    public function handle(EmailSentSuccessfullyEvent $event): void
    {
        $notification = $event->notification;

        $notification->notificationChannels()->where('is_email', true)->update(['status' => NotificationStatuses::DELIVERED]);
    }
}
