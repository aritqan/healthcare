<?php

namespace Modules\Admin\Enums;

final class AdminStatus
{
    const ACTIVE    = 'active';
    const INACTIVE  = 'inactive';
    const SUSPENDED = 'suspended';
    const PENDING   = 'pending';

    public static function all()
    {
        return [
            self::ACTIVE,
            self::INACTIVE,
            self::SUSPENDED,
            self::PENDING,
        ];
    }

    public static function getStatuses()
    {
        return [
            self::ACTIVE    => trans('admin::statuses.admin.' . self::ACTIVE),
            self::INACTIVE  => trans('admin::statuses.admin.' . self::INACTIVE),
            self::SUSPENDED => trans('admin::statuses.admin.' . self::SUSPENDED),
            self::PENDING   => trans('admin::statuses.admin.' . self::PENDING)
        ];
    }

    public static function getStatusColor(string $status) : string
    {
        $colors = [
            self::ACTIVE    => 'success',
            self::INACTIVE  => 'secondary',
            self::SUSPENDED => 'danger',
            self::PENDING   => 'warning',
        ];

        return $colors[$status];
    }
}
