<?php

namespace Modules\Base\Events;

use Illuminate\Queue\SerializesModels;

class UpdateTranslationEvent
{
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public mixed $model, public array $oldTranslations, public array $modifiedTranslations)
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
