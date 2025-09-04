<?php

namespace Modules\Notification\Http\Services;

use Modules\Auth\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Admin\Models\Admin;
use Illuminate\Contracts\Auth\Authenticatable;
use Modules\Notification\Enums\FirebaseTopics;
use Modules\Base\Http\Services\BaseCrudService;
use Modules\Notification\Enums\NotificationAddedBy;
use Modules\Notification\Enums\NotificationChannels;
use Modules\Notification\Enums\NotificationPriority;
use Modules\Notification\Enums\NotificationStatuses;
use Modules\Notification\Models\NotificationTemplate;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Modules\Notification\Jobs\SendEmailNotificationJob;
use Modules\Notification\Jobs\SendFcmPushNotificationJob;
use Modules\Notification\Jobs\SendFcmTopicPushNotificationJob;
use Modules\Notification\Models\Notification as CrudModel;

class NotificationService extends BaseCrudService
{
    public function __construct(private FirebaseService $FirebaseService)
    {

    }

    /**
     * The unnecessary fields for crud.
     * Example: if the data has translation fields, you can add them here. As a ('title', 'description')
     */
    protected $unnecessaryFieldsForCrud = [
        'user',
        'users_id',
        'channels',
        'priority',
        'group',
        'title',
        'body',
        'long_template',
    ];

    /**
     * Create a new Model instance.
     *
     * @param array $data
     * @return CrudModel
     */
    public function createModel(array $data): CrudModel
    {
        $modelData    = $this->prepareModelData($data);
        $translations = $this->createTranslations($data, 'title', ['body', 'long_template']);

        $model = DB::transaction(function () use($modelData, $translations, $data){
            $model = CrudModel::create($modelData);

            $model->update($translations);

            $this->createNotificationChannels($model, $data);

            return $model;
        });

        return $model;
    }

    /**
     * Create Notification Channels
     *
     * @param CrudModel $model
     * @param array $data
     * @return void
     */
    private function createNotificationChannels($model, $data)
    {
        foreach($data['channels'] as $channel)
        {
            $model->notificationChannels()->create([
                'status'        => NotificationStatuses::PENDING,
                'is_fcm_mobile' => $channel == NotificationChannels::FCM_MOBILE,
                'is_fcm_web'    => $channel == NotificationChannels::FCM_WEB,
                'is_email'      => $channel == NotificationChannels::MAIL,
                'is_sms'        => $channel == NotificationChannels::SMS,
            ]);
        }
    }

    /**
     * Send To Specific Users
     *
     * @param array $usersId
     * @param array $data
     * @return void
     */
    public function sendToUsers($usersId, $data)
    {
        foreach($usersId as $userId)
        {
            $preparedData   = $this->prepareDataForWithoutTempalteNotificationModel($data['group'], $userId, $data['channels']);
            $modelData      = array_merge($preparedData, $data);
            $model          = $this->createModel($modelData);

            $this->sendWithoutTemplate($model, $preparedData['user'], $data['priority']);
        }
    }

    /**
     * Send To All Users
     * This function is used to send notification to all users by topic
     * Topic is the type of user or admin with locale code (user_en, user_ar, admin_en, admin_ar)
     */
    public function sendToAll($data)
    {
        foreach($data['title'] as $locale => $title)
        {
            if(empty($title)) continue;

            $preparedData   = $this->prepareTopicDataForWithoutTempalteNotificationModel($data['group'], $data['channels'], $locale);
            $modelData      = array_merge($preparedData, $data);
            $model          = $this->createModel($modelData);

            $this->sendByTopic($model, $preparedData['topic'], $locale, $data['priority']);
        }
    }

    public function getAllUsersIds($group)
    {
        if($group == config('admin.main_roles.users')) {
            return User::active()->pluck('id')->toArray();
        } else {
            return Admin::active()->pluck('id')->toArray();
        }
    }

    /**
     * Prepare Notification Data
     *
     * @param string $templateName
     * @param array $variables
     * @param string $locale
     * @return array
     */
    private function prepareNotificationData(string $templateName, array $variables, string $locale): array
    {
        $response = $this->checkIfTempalteAndVariablesAreValid($templateName, $variables);

        if(!$response['success']){
            return sendFailInternalResponse(customMessage: $response['message']);
        }

        $template = $response['data']['template'];

        $title          = $template->smartTrans('title', $locale);
        $shortTemplate  = $template->smartTrans('short_template', $locale);
        $longTemplate   = $template->smartTrans('long_template', $locale);

        foreach($variables as $key => $value){
            $title          = str_replace("{{{$key}}}", $value, $title);
            $shortTemplate  = str_replace("{{{$key}}}", $value, $shortTemplate);
            $longTemplate   = str_replace("{{{$key}}}", $value, $longTemplate);
        }

        if(isDev()) {
            Log::info("Notification Data Prepared", [
                'templateName'  => $templateName,
                'title'         => $title,
                'variables'     => $variables,
                'body'          => $shortTemplate,
                'htmlTemplate'  => $longTemplate,
                'channels'      => $template->channels,
            ]);
        }

        return sendSuccessInternalResponse(data: [
            'template'      => $template,
            'title'         => $title,
            'body'          => $shortTemplate,
            'htmlTemplate'  => $longTemplate,
        ]);
    }

    /**
     * Check If Tempalte And Variables Are Valid
     *
     * @param string $templateName
     * @param array $variables
     * @return array
     */
    private function checkIfTempalteAndVariablesAreValid(string $templateName, array $variables): array
    {
        // Validate Variables must be string or number
        foreach($variables as $key => $value){
            if(!is_string($value) && !is_numeric($value) && !is_bool($value)){
                Log::error("Notification Variables must be string or number. Key: $key, Value: $value");

                return sendFailInternalResponse('variables_must_be_string_or_number');
            }
        }

        // Get Notification Template
        $template = NotificationTemplate::withTrashed()->where('name', $templateName)->first();

        if(!$template){
            Log::error("Notification Template not found. Template Name: $templateName");

            return sendFailInternalResponse('notification_template_not_found');
        }

        return sendSuccessInternalResponse(data: [
            'template'  => $template,
        ]);
    }

    /**
     * Send Notification By Template Name
     *
     * @param mixed $templateName
     * @param array $variables
     * @param Authenticatable $user
     * @param array $extraData
     * @param mixed $locale
     * @return mixed
     */
    public function send(Authenticatable $user, mixed $templateName, array $variables, array $extraData = [], mixed $locale = null): mixed
    {
        $locale ??= $user->lang ?? config('notification.fallback_locale');
        $response = $this->prepareNotificationData($templateName, $variables, $locale);

        if(! $response['success']){
            return $response;
        }

        $data       = $response['data'];
        $template   = $data['template'];
        $modelData  = $this->prepareDataForNotificationModel($user, $template, $variables);
        $model      = $this->createModel($modelData);

        if(in_array(NotificationChannels::FCM_MOBILE, $template->channels)){
            $this->sendFcmPushNotification($user, $model, true, $template->priority, $data['title'], $data['body'], $extraData);
        }

        if(in_array(NotificationChannels::FCM_WEB, $template->channels)){
            $this->sendFcmPushNotification($user, $model, false, $template->priority, $data['title'], $data['body'], $extraData);
        }

        if(in_array(NotificationChannels::MAIL, $template->channels)){
            $this->sendEmailNotification($user->email, $user->username, $model, $template->priority, $data['title'], $data['body'], $extraData);
        }

        return sendSuccessInternalResponse();
    }

    /**
     * Send Notification Without Template
     *
     * @param CrudModel $notification
     * @param Authenticatable $user
     * @param mixed $locale
     * @return void
     */
    public function sendWithoutTemplate(CrudModel $notification, Authenticatable $user, mixed $queue = NotificationPriority::DEFAULT, mixed $locale = null): void
    {
        $locale ??= $user->lang ?? config('notification.fallback_locale');
        $title      = $notification->smartTrans('title', $locale);
        $body       = $notification->smartTrans('body' , $locale);
        $htmlBody   = $notification->smartTrans('long_template', $locale);
        $fcmWeb     = $notification->notificationChannels()->isWeb()->exists();
        $fcmMobile  = $notification->notificationChannels()->isMobile()->exists();
        $email      = $notification->notificationChannels()->isEmail()->exists();

        if($fcmMobile){
            $this->sendFcmPushNotification($user, $notification, true, $queue, $title, $body);
        }

        if($fcmWeb){
            $this->sendFcmPushNotification($user, $notification, false, $queue, $title, $body);
        }

        if($email){
            $this->sendEmailNotification($user->email, $user->full_name, $notification, $queue, $title, $htmlBody);
        }

        return;
    }

    /**
     * Send Notification By Topic
     *
     * @param CrudModel $notification
     * @param string $topic
     * @param string $locale
     * @return void
     */
    public function sendByTopic(CrudModel $notification, string $topic, string $locale, mixed $queue = NotificationPriority::DEFAULT) : void
    {
        $title      = $notification->smartTrans('title', $locale, force: true);
        $body       = $notification->smartTrans('body', $locale, force: true);
        $fcmWeb     = $notification->notificationChannels()->isWeb()->exists();
        $fcmMobile  = $notification->notificationChannels()->isMobile()->exists();

        if(empty($title) || empty($body)){
            return;
        }

        if($fcmWeb || $fcmMobile){
            $this->sendFcmPushNotificationByTopic($notification, $topic, $queue, $title, $body);
        }

        return;
    }

    /**
     * Prepare Data For Notification Model
     *
     * @param Authenticatable $user
     * @param NotificationTemplate $template
     * @param array $variables
     * @return array
     */
    private function prepareDataForNotificationModel(Authenticatable $user, NotificationTemplate $template, array $variables): array
    {
        $modelData = [
            'notifiable_id'     => $user->id,
            'notifiable_type'   => get_class($user),
            'added_by'          => NotificationAddedBy::SYSTEM,
            'channels'          => $template->channels,
        ];

        foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties){
            $modelData['title'][$localeCode]           = $template->smartTrans('title', $localeCode, force: true);
            $modelData['body'][$localeCode]            = $template->smartTrans('short_template', $localeCode, force: true);
            $modelData['long_template'][$localeCode]   = $template->smartTrans('long_template', $localeCode, force: true);

            foreach($variables as $key => $value){
                $modelData['title'][$localeCode]            = str_replace("{{{$key}}}", $value, $modelData['title'][$localeCode]);
                $modelData['body'][$localeCode]             = str_replace("{{{$key}}}", $value, $modelData['body'][$localeCode]);
                $modelData['long_template'][$localeCode]    = str_replace("{{{$key}}}", $value, $modelData['long_template'][$localeCode]);
            }
        }

        return $modelData;
    }

    /**
     * Prepare Data For Without Tempalte Notification Model
     *
     * @param string $group
     * @param int $userId
     * @param array $channels
     * @return array
     */
    public function prepareDataForWithoutTempalteNotificationModel(string $group, int $userId, array $channels): array
    {
        if($group == config('admin.main_roles.users')) {
            $user = User::find($userId);
        } else {
            $user = Admin::find($userId);
        }

        $modelData = [
            'user'              => $user,
            'notifiable_id'     => $user->id,
            'notifiable_type'   => get_class($user),
            'added_by'          => NotificationAddedBy::ADMIN,
            'channels'          => $channels,
        ];

        return $modelData;
    }

    /**
     * Prepare Data For Without Tempalte Notification Model
     *
     * @param string $group
     * @param int $userId
     * @param array $channels
     * @return array
     */
    public function prepareTopicDataForWithoutTempalteNotificationModel(string $group, array $channels, string $locale): array
    {
        if($group == config('admin.main_roles.users')) {
            $type = FirebaseTopics::USER;
        } else {
            $type = FirebaseTopics::ADMIN;
        }

        $modelData = [
            'topic'             => $type . '_' . $locale,
            'added_by'          => NotificationAddedBy::ADMIN,
            'channels'          => $channels,
        ];

        return $modelData;
    }

    /**
     * Send Fcm Push Notification With Job
     *
     * @param Authenticatable $user
     * @param CrudModel $notification
     * @param bool $forMobile
     * @param mixed $queue
     * @param string $title
     * @param string $body
     * @param array $extraData
     * @return void
     */
    public function sendFcmPushNotification(Authenticatable $user, CrudModel $notification, bool $forMobile, mixed $queue = NotificationPriority::DEFAULT, string $title, string $body, array $extraData = [])
    {
        SendFcmPushNotificationJob::dispatch($user, $notification, $forMobile, $title, $body, $extraData)->onQueue($queue);
    }

    /**
     * Send Fcm Push Notification By Topic
     *
     * @param CrudModel $notification
     * @param string $topic
     * @param mixed $queue
     * @param string $title
     * @param string $body
     * @param array $extraData
     * @return void
     */
    public function sendFcmPushNotificationByTopic(CrudModel $notification, string $topic, mixed $queue = NotificationPriority::DEFAULT, string $title, string $body, array $extraData = [])
    {
        SendFcmTopicPushNotificationJob::dispatch($topic, $notification, $title, $body, $extraData)->onQueue($queue);
    }

    /**
     * Send Email Notification With Job
     *
     * @param string $email
     * @param string $name
     * @param CrudModel $notification
     * @param mixed $queue
     * @param string $title
     * @param string $body
     * @param array $extraData
     * @return void
     */
    public function sendEmailNotification(string $email, string $name, CrudModel $notification, mixed $queue = NotificationPriority::DEFAULT, string $title, string $body, array $extraData = [])
    {
        SendEmailNotificationJob::dispatch($email, $name, $notification, $title, $body, $extraData)->onQueue($queue);
    }

    /**
     * Get Admin Web Notifications
     *
     * @param Authenticatable $user
     * @return mixed
     */
    public function adminWebNotifications($user) : mixed
    {
        $this->data['webNotifications']     = $user->webNotifications()->successfully();
        $this->data['topicNotifications']   = CrudModel::where('topic', $user->fcm_topic)->successfully();
        $this->data['model']                = $this->data['webNotifications']->union($this->data['topicNotifications'])->latest();

        return $this->data['model'];
    }

    /**
     * Mark As Seen Notifications
     *
     * @param Authenticatable $user
     * @return void
     */
    public function updateNotificationChannelStatus($user, $data)
    {
        $status                 = $data['status'];
        $channel                = $data['channel'];
        $oldStatus              = NotificationStatuses::DELIVERED;
        $updateAll              = $data['updateAll'] ?? false;
        $notificationChannelId  = $data['notificationChannelId'] ?? null;

        switch($channel)
        {
            case NotificationChannels::FCM_MOBILE:
                $channel = 'is_fcm_mobile';
                break;
            case NotificationChannels::FCM_WEB:
                $channel = 'is_fcm_web';
                break;
            case NotificationChannels::MAIL:
                $channel = 'is_email';
                break;
            case NotificationChannels::SMS:
                $channel = 'is_sms';
                break;
        }

        switch($status)
        {
            case NotificationStatuses::SEEN:
                $oldStatus = NotificationStatuses::DELIVERED;
                break;
            case NotificationStatuses::READ:
                $oldStatus = NotificationStatuses::SEEN;
                break;
        }

        $user->allNotifications()->whereHas('notificationChannels', function($query) use($oldStatus, $channel, $updateAll, $notificationChannelId) {
            if($updateAll) {
                $query->where($channel, true)->where('status', $oldStatus);
            } else {
                $query->where('id', $notificationChannelId)->where('status', $oldStatus);
            }
        })->each(function($notification) use($status) {
            $notification->notificationChannels()->update(['status' => $status]);
        });
    }
}
