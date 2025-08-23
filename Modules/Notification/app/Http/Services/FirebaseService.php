<?php

namespace Modules\Notification\Http\Services;

use Exception;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Auth\Authenticatable;
use Modules\Base\Http\Services\BaseApiService;
use Modules\Log\Classes\ServiceName;
use Modules\Log\Http\Services\ApiLogService;
use Modules\Notification\Enums\FirebaseTopics;
use Modules\Notification\Enums\NotificationChannels;
use Modules\Notification\Resources\FirebaseTokenResource;

class FirebaseService extends BaseApiService
{
    private $firebaseUrl;

    private $subscripeToTopicUrl;

    private $unsubscribeFromTopicUrl;

    private $tokenInfoUrl;

    public function __construct()
    {
        $this->firebaseUrl              = config('notification.firebase_url');
        $this->subscripeToTopicUrl      = config('notification.firebase_subscribe_to_topic_url');
        $this->unsubscribeFromTopicUrl  = config('notification.firebase_unsubscribe_from_topic_url');
        $this->tokenInfoUrl             = config('notification.firebase_token_info_url');
    }

    /**
     * Create FCM token
     *
     * @param
     * @param array $data
     * @return array
     */
    public function saveFcmToken(Authenticatable $user, array $data, string $topic = FirebaseTopics::USER): array
    {
        $data['extra_data']['topic'] = $topic . '_' . $user->lang;
        $type = $topic === FirebaseTopics::USER ? NotificationChannels::FCM_MOBILE : NotificationChannels::FCM_WEB;

        $user->fcmTokens()->updateOrCreate(
            [
                'type'          => $type,
            ],
            [
                'token'         => $data['token'],
                'extra_data'    => $data['extra_data'],
            ]
        );

        return sendSuccessInternalResponse('firebase_token_saved_successfully');
    }

    /**
     * Manage topic subscription
     *
     * @param Authenticatable $user
     * @param array $data
     * @return array
     */
    public function manageTopicSubscription(Authenticatable $user, array $data): array
    {
        $token          = $data['token'];
        $topic          = $user->fcmTokens()->first()?->extra_data['topic'];
        $header         = [
            'Content-Type'      => 'application/json',
            'access_token_auth' => 'true',
        ];
        $payload                    = [
            'to'                    => "/topics/{$topic}",
            'registration_tokens'   => [$token],
        ];

        $this->unsubscribeFromAllTopics($token);

        $accessToken    = self::getAccessToken();

        if(! $accessToken['success']) {
            return sendFailInternalResponse('failed_to_obtain_access_token');
        }

        $response = Http::withToken($accessToken['data']['access_token'])->withHeaders($header)->post($this->subscripeToTopicUrl, $payload);

        ApiLogService::log(ServiceName::FIREBASE, POST_METHOD, $this->subscripeToTopicUrl, $payload, $response);

        if($response->ok()) {
            return sendSuccessInternalResponse('subscribed_to_topic_successfully');
        } else {
            return sendFailInternalResponse('operation_faield', errors: $response->json());
        }
    }

    /**
     * Get FCM token info
     *
     * @param Authenticatable $user
     * @return array
     */
    public function getFcmTokenInfo($token): array
    {
        $header         = [
            'Content-Type'      => 'application/json',
            'access_token_auth' => 'true',
        ];
        $accessToken    = self::getAccessToken();

        if(! $accessToken['success']) {
            return sendFailInternalResponse('failed_to_obtain_access_token');
        }

        $url = $this->tokenInfoUrl . $token . '?details=true';

        $response = Http::withToken($accessToken['data']['access_token'])->withHeaders($header)->get($url);

        ApiLogService::log(ServiceName::FIREBASE, GET_METHOD, $url, [], $response);

        if($response->ok()) {
            return sendSuccessInternalResponse('token_info', data: $response->json());
        } else {
            return sendFailInternalResponse('operation_faield', errors: $response->json());
        }
    }

    /**
     * Unsubscribe from all topics
     *
     * @param string $token
     * @return array
     */
    public function unsubscribeFromAllTopics($token): array
    {
        $fcmTokenInfo = $this->getFcmTokenInfo($token);

        if(! $fcmTokenInfo['success']) {
            return $fcmTokenInfo;
        }

        $topics = $fcmTokenInfo['data']['rel']['topics'] ?? [];

        $accessToken    = self::getAccessToken();

        if(! $accessToken['success']) {
            return sendFailInternalResponse('failed_to_obtain_access_token');
        }

        foreach($topics as $topic => $topicInfo) {
            Http::withToken($accessToken['data']['access_token'])
            ->withHeaders([
                'Content-Type'      => 'application/json',
                'access_token_auth' => 'true',
            ])->post($this->unsubscribeFromTopicUrl, [
                'to'                    => "/topics/{$topic}",
                'registration_tokens'   => [$token],
            ]);
        }

        return sendSuccessInternalResponse('unsubscribed_from_all_topics_successfully');
    }

    /**
     * Get access token for firebase
     *
     * @return array
     */
    public static function getAccessToken() : array
    {
        // Load Firebase credentials JSON
        $credentials = json_decode(file_get_contents(config('notification.firebase_credentials')), true);

        // Define JWT claim set
        $now = time();

        $jwtPayload = [
            'iss'   => $credentials['client_email'],          // Issuer
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging', // Scope for Firebase
            'aud'   => $credentials['token_uri'],             // Audience
            'exp'   => $now + 3600,                           // Token expiration (1 hour)
            'iat'   => $now,                                  // Issued at time
        ];

        // Sign the JWT with your private key
        $privateKey = $credentials['private_key'];
        $jwt        = JWT::encode($jwtPayload, $privateKey, 'RS256');

        $response = Http::asForm()->post($credentials['token_uri'], [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion'  => $jwt,
        ]);

        ApiLogService::log(ServiceName::FIREBASE, POST_METHOD, $credentials['token_uri'], [], $response);

        if ($response->ok()) {
            return sendSuccessInternalResponse('access_token_obtained_successfully', data: [
                'access_token' => $response->json()['access_token'],
            ]);
        } else {
            return sendFailInternalResponse('failed_to_obtain_access_token', errors: $response->json());
        }
    }

    /**
     * Push FCM notification to specific user
     *
     * @param Authenticatable $user
     * @param bool $forMobile
     * @param string $title
     * @param string $body
     * @param array $extraData
     * @return array
     */
    public function pushFcmNotificationToUser(Authenticatable $user, string $title, string $body, bool $forMobile = true, array $extraData = []) : array
    {
        $tokenResource = $this->checkIfUserHasToken($user, $forMobile);

        if(! $tokenResource['success']) {
            return $tokenResource;
        }

        $accessToken = self::getAccessToken();

        if(! $accessToken['success']) {
            return sendFailInternalResponse('failed_to_obtain_access_token');
        }

        $fields = $this->preparePayloadForFcmNotification($tokenResource['data']['token'], $title, $body, $extraData);

        try{
            $response       = Http::withToken($accessToken['data']['access_token'])->post($this->firebaseUrl, $fields);
            $responseBody   = $response->json();

            ApiLogService::log(ServiceName::FIREBASE, POST_METHOD, $this->firebaseUrl, $fields, $response);

            if($response->ok()) {
                return sendSuccessInternalResponse('notification_sent_successfully', data: $responseBody);
            } else {
                return sendFailInternalResponse('notification_failed', errors: $responseBody);
            }
        }catch(Exception $e) {
            return sendFailInternalResponse(customMessage: $e->getMessage());
        }
    }

    /**
     * Push FCM notification to specific topic
     *
     * @param string $topic
     * @param string $title
     * @param string $body
     * @param array $extraData
     * @return array
     */
    public function pushFcmNotificationToTopic(string $topic, string $title, string $body, array $extraData = []) : array
    {
        $fields = $this->preparePayloadForFcmTopicNotification($topic, $title, $body, $extraData);

        try{
            $accessToken    = self::getAccessToken();

            if(! $accessToken['success']) {
                return sendFailInternalResponse('failed_to_obtain_access_token');
            }

            $response       = Http::withToken($accessToken['data']['access_token'])->post($this->firebaseUrl, $fields);
            $responseBody   = $response->json();

            ApiLogService::log(ServiceName::FIREBASE, POST_METHOD, $this->firebaseUrl, $fields, $response);

            if($response->ok()) {
                return sendSuccessInternalResponse('notification_sent_successfully', data: $responseBody);
            } else {
                return sendFailInternalResponse('notification_failed', errors: $responseBody);
            }
        }catch(Exception $e) {
            return sendFailInternalResponse(customMessage: $e->getMessage());
        }
    }

    /**
     * Check if user has token
     *
     * @param Authenticatable $user
     * @param bool $forMobile
     * @return array
     */
    private function checkIfUserHasToken(Authenticatable $user, bool $forMobile = true) : array
    {
        if($forMobile) {
            $tokenResource = $user->mobileTokens()->first();
        } else {
            $tokenResource = $user->webTokens()->first();
        }

        if (!$tokenResource || ! $tokenResource->token) {
            return sendFailInternalResponse('user_has_no_firebase_token');
        }

        return sendSuccessInternalResponse(data: [
            'token' => new FirebaseTokenResource($tokenResource),
        ]);
    }

    /**
     * Prepare payload for FCM notification
     *
     * @param mixed $tokenResource
     * @param string $title
     * @param string $body
     * @param array $extraData
     * @return array
     */
    private function preparePayloadForFcmNotification(mixed $tokenResource, string $title, string $body, array $extraData = []) : array
    {
        return [
            'message'           => [
                'token'         => $tokenResource->token,
                'notification'  => [
                    'title'     => $title,
                    'body'      => $body,
                ],
                'data'          => (object) $extraData,
            ]
        ];
    }

    /**
     * Prepare payload for FCM topic notification
     *
     * @param string $topic
     * @param string $title
     * @param string $body
     * @param array $extraData
     * @return array
     */
    private function preparePayloadForFcmTopicNotification(string $topic, string $title, string $body, array $extraData = []) : array
    {
        return [
            'message' => [
                'topic'         => $topic,
                'notification'  => [
                    'title'     => $title,
                    'body'      => $body,
                ],
                'data'          => (object) $extraData,
            ],
        ];
    }
}
