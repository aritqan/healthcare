<?php

namespace Modules\Notification\Enums;

final class NotificationStatuses
{
    const DELIVERED = 'delivered';
    const PENDING   = 'pending';
    const SEEN      = 'seen';
    const FAILED    = 'failed';
    const READ      = 'read';

    public static function all()
    {
        return [
            self::DELIVERED,
            self::PENDING,
            self::SEEN,
            self::FAILED,
            self::READ,
        ];
    }

    public static function getStatuses()
    {
        $statuses = [];

        foreach (self::all() as $status) {
            $statuses[$status] = trans('notification::notifications.statuses.' . $status);
        }

        return $statuses;
    }

    public static function getColors()
    {
        return [
            self::DELIVERED => 'success',
            self::PENDING   => 'warning',
            self::SEEN      => 'info',
            self::FAILED    => 'danger',
            self::READ      => 'primary',
        ];
    }
}
