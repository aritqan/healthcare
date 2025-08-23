<?php

namespace Modules\Notification\Enums;

final class NotificationChannels
{
    const MAIL          = 'mail';
    const SMS           = 'sms';
    const FCM_WEB       = 'fcm_web';
    const FCM_MOBILE    = 'fcm_mobile';

    public static function all()
    {
        return [
            self::MAIL,
            self::SMS,
            self::FCM_WEB,
            self::FCM_MOBILE,
        ];
    }

    public static function getChannels()
    {
        return [
            self::MAIL,
            self::SMS,
            self::FCM_WEB,
            self::FCM_MOBILE,
        ];
    }
}
