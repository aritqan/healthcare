<?php

namespace Modules\Notification\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Auth\Authenticatable;
use Modules\Notification\Models\Notification;
use Modules\Notification\Events\FcmNotificationFailed;
use Modules\Notification\Http\Services\FirebaseService;
use Modules\Notification\Events\FcmNotificationSentSuccessfully;
use Illuminate\Queue\Attributes\WithoutRelations;

class SendFcmPushNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(#[WithoutRelations] private Authenticatable $user, #[WithoutRelations] private Notification $notification, private bool $forMobile, private string $title, private string $body, private array $extraData = [])
    {

    }

    /**
     * Execute the job.
     */
    public function handle(FirebaseService $FirebaseService): void
    {
        $result = $FirebaseService->pushFcmNotificationToUser($this->user, $this->title, $this->body, $this->forMobile, $this->extraData);

        if($result['success']) {
            event(new FcmNotificationSentSuccessfully($this->notification, $this->forMobile));
        } else {
            event(new FcmNotificationFailed($this->notification, $this->forMobile));
        }
    }
}
