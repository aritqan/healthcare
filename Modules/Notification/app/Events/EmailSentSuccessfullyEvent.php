<?php

namespace Modules\Notification\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Notification\Models\Notification;

class EmailSentSuccessfullyEvent
{
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Notification $notification)
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
