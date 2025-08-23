<?php

namespace Modules\Base\Listeners;

use Illuminate\Support\Facades\Event;
use OwenIt\Auditing\Events\AuditCustom;

class UpdateTranslationListener
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
    public function handle($event): void
    {
        $model                  = $event->model;
        $oldTranslations        = $event->oldTranslations;
        $modifiedTranslations   = $event->modifiedTranslations;

        // Refresh model
        $model->refresh();

        $model->auditEvent    = 'update_translation';
        $model->isCustomEvent = true;

        $model->auditCustomOld = [
            'translations' => $oldTranslations
        ];

        $model->auditCustomNew = [
            'translations' => $modifiedTranslations
        ];

        Event::dispatch(AuditCustom::class, [$model]);
    }
}
