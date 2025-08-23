<?php

namespace Modules\Notification\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Notification\Events\EmailSentFailedEvent;
use Modules\Notification\Events\EmailSentSuccessfullyEvent;
use Modules\Notification\Models\Notification;
use Modules\Notification\Http\Services\SendGridService;
use Illuminate\Queue\Attributes\WithoutRelations;

class SendEmailNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private string $email, private string $name, #[WithoutRelations] private Notification $notification, private string $title, private string $body, private array $extraData = [])
    {

    }

    /**
     * Execute the job.
     */
    public function handle(SendGridService $sendGridService): void
    {
        $result = $sendGridService->sendEmail($this->email, $this->name, $this->title, $this->body, $this->extraData);

        if($result['success']) {
            event(new EmailSentSuccessfullyEvent($this->notification));
        } else {
            event(new EmailSentFailedEvent($this->notification));
        }
    }
}
