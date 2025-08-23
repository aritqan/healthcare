<?php

namespace Modules\Notification\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Notification\Models\Notification;
use Modules\Notification\Events\FcmNotificationFailed;
use Modules\Notification\Http\Services\FirebaseService;
use Modules\Notification\Events\FcmNotificationSentSuccessfully;
use Illuminate\Queue\Attributes\WithoutRelations;

class SendFcmTopicPushNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private string $topic, #[WithoutRelations] private Notification $notification, private string $title, private string $body, private array $extraData = [])
    {

    }

    /**
     * Execute the job.
     */
    public function handle(FirebaseService $FirebaseService): void
    {
        $result = $FirebaseService->pushFcmNotificationToTopic($this->topic, $this->title, $this->body, $this->extraData);

        if($result['success']) {
            event(new FcmNotificationSentSuccessfully($this->notification, isTopic: true));
        } else {
            event(new FcmNotificationFailed($this->notification, isTopic: true));
        }
    }
}
