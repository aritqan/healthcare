<?php

namespace Modules\Notification\Enums;

final class NotificationPriority
{
    const LOW           = 'low';
    const DEFAULT       = 'default';
    const HIGH          = 'high';

    public static function all()
    {
        return [
            self::LOW,
            self::DEFAULT,
            self::HIGH,
        ];
    }

    public static function getPriorities()
    {
        $priorities = [];

        foreach (self::all() as $priority) {
            $priorities[$priority] = trans('notification::notifications.notification_templates.priority.' . $priority);
        }

        return $priorities;
    }

    public static function getColors()
    {
        return [
            self::LOW       => 'success',
            self::DEFAULT   => 'warning',
            self::HIGH      => 'danger',
        ];
    }
}
