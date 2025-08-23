<?php

namespace Modules\Notification\Observers;

use Modules\Notification\Http\Services\FirebaseService;
use Modules\Notification\Models\FirebaseToken;

class FirebaseTokenObserver
{
    public function __construct(private FirebaseService $FirebaseService)
    {

    }

    /**
     * Handle the FirebaseToken "created" event.
     */
    public function created(FirebaseToken $firebasetoken): void
    {
        $this->manageTopicSubscription($firebasetoken);
    }

    /**
     * Handle the FirebaseToken "updated" event.
     */
    public function updated(FirebaseToken $firebasetoken): void
    {
        $this->manageTopicSubscription($firebasetoken);
    }

    /**
     * Handle the FirebaseToken "deleted" event.
     */
    public function deleted(FirebaseToken $firebasetoken): void
    {
        $this->manageTopicSubscription($firebasetoken);
    }

    private function manageTopicSubscription(FirebaseToken $firebasetoken): void
    {
        // \Log::info('FirebaseTokenObserver: ' . $firebasetoken->tokenable_type . ' ' . $firebasetoken->tokenable_id);
        $this->FirebaseService->manageTopicSubscription($firebasetoken->tokenable, [
            'token' => $firebasetoken->token,
        ]);
    }
}
