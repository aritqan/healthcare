<?php

namespace Modules\Admin\Listeners;

use Silber\Bouncer\BouncerFacade;
use Illuminate\Support\Facades\Event;
use OwenIt\Auditing\Events\AuditCustom;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Admin\Models\Admin;

class RoleChangedListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the name of the listener's queue.
     */
    public function viaQueue(): string
    {
        return config('audit.queue.queue', 'default');
    }

    /**
     * Handle the event.
     */
    public function handle($event): void
    {
        $admin      = $event->admin;
        $oldRole    = $event->oldRole;

        // Refresh Admin model
        $admin->refresh();

        // Refresh Bouncer permissions
        BouncerFacade::refresh($admin);

        $admin->auditEvent    = 'role_changed';
        $admin->isCustomEvent = true;

        $admin->auditCustomOld = [
            'role' => $oldRole
        ];

        $admin->auditCustomNew = [
            'role' => $admin->roles->first()?->name
        ];

        $admin->preloadedResolverData = [
            'user' => Admin::find($event->userId)
        ];

        Event::dispatch(AuditCustom::class, [$admin]);
    }
}
