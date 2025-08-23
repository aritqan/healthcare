<?php

namespace Modules\Notification\Enums;

final class NotificationAddedBy
{
    const SYSTEM = 'system';
    const ADMIN  = 'admin';

    public static function all()
    {
        return [
            self::SYSTEM,
            self::ADMIN,
        ];
    }

    public static function getAddedBy()
    {
        $addedBy = [];

        foreach (self::all() as $added) {
            $addedBy[$added] = trans('notification::notifications.added_by.' . $added);
        }

        return $addedBy;
    }
}
