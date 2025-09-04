<?php

namespace Modules\Notification\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailTemplate extends Mailable
{
    use Queueable, SerializesModels;

    public $htmlTemplate;

    public $subject;

    /**
     * Create a new message instance.
     */
    public function __construct($subject, $htmlTemplate)
    {
        $this->subject = $subject;
        $this->htmlTemplate = $htmlTemplate;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this
        ->subject($this->subject)
        ->view('admin::emails.templates.template')
        ->with('content', $this->htmlTemplate);
    }
}
