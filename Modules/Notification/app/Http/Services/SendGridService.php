<?php

namespace Modules\Notification\Http\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Modules\Log\Classes\ServiceName;
use Modules\Log\Http\Services\ApiLogService;
use Modules\Base\Http\Services\BaseApiService;

class SendGridService extends BaseApiService
{
    protected $apiKey;

    protected $url;

    public function __construct()
    {
        $this->apiKey   = config('notification.sendgrid.api_key');
        $this->url      = config('notification.sendgrid.url');
    }

    /**
     * Send an email using SendGrid
     *
     * @param string $toEmail
     * @param string $toName
     * @param string $subject
     * @param string $htmlTemplate
     * @param array $extraData
     * @return mixed
     */
    public function sendEmail(string $toEmail, string $toName, string $subject, string $htmlTemplate, array $extraData = []): mixed
    {
        try {
            $fromEmail  = config('notification.sendgrid.from_email');
            $fromName   = config('notification.sendgrid.from_name');
            $content    = view('admin::emails.templates.template', [
                'content' => $htmlTemplate,
            ])->render();

            $emailData = [
                'personalizations' => [
                    [
                        'to'            => [
                            [
                                'email' => $toEmail,
                                'name'  => $toName,
                            ],
                        ],
                        'subject'   => $subject,
                    ],
                ],

                'from'              => [
                    'email'         => $fromEmail,
                    'name'          => $fromName,
                ],
                'content'           => [
                    [
                        'type'      => 'text/html',
                        'value'     => $content,
                    ],
                ],
            ];

            // Send the request to SendGrid API
            $response = Http::withToken($this->apiKey)->post($this->url, $emailData);

            ApiLogService::log(ServiceName::SENDGRID, POST_METHOD, $this->url, $emailData, $response);

            if ($response->successful()) {
                return sendSuccessInternalResponse('email_sent_successfully');
            }

            return sendFailInternalResponse('email_sent_failed', errors: $response->json());
        } catch (Exception $e) {
            Log::error('Error while sending email using SendGrid', [
                'error' => $e->getMessage(),
            ]);
            return sendFailInternalResponse(customMessage: $e->getMessage());
        }
    }
}
