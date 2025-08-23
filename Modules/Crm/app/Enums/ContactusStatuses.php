<?php

namespace Modules\Crm\Enums;

final class ContactusStatuses
{
    const PENDING = 'pending';
    const SEEN    = 'seen';
    const REPLIED = 'replied';
    const CLOSED  = 'closed';

    public static function all()
    {
        return [
            self::PENDING,
            self::SEEN,
            self::REPLIED,
            self::CLOSED,
        ];
    }

    public static function getStatuses()
    {
        $statuses = [];

        foreach (self::all() as $status) {
            $statuses[$status] = trans('crm::contactus.statuses.' . $status);
        }

        return $statuses;
    }

    public static function getStatusColor(string $status) : string
    {
        $colors = [
            self::PENDING => 'warning',
            self::SEEN    => 'success',
            self::REPLIED => 'info',
            self::CLOSED  => 'danger',
        ];

        return $colors[$status] ?? 'secondary';
    }
}
