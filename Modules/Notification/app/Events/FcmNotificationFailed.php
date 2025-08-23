<?php

namespace Modules\Notification\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Notification\Models\Notification;

class FcmNotificationFailed
{
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Notification $notification, public bool $forMobile = true, public bool $isTopic = false)
    {
        //
    }

    /**
     * Get the channels the event should be broadcast on.
     */
    public function broadcastOn(): array
    {
        return [];
    }
}
