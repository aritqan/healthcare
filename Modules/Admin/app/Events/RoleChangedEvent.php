<?php

namespace Modules\Admin\Events;

use Illuminate\Queue\SerializesModels;
use Modules\Admin\Models\Admin;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;

class RoleChangedEvent implements ShouldDispatchAfterCommit
{
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Admin $admin, public string $oldRole, public string $userId)
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
