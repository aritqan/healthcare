<?php

namespace Modules\Log\Enums;

final class ApiLogStatuses
{
    const SUCCESS = 'success';
    const FAILED  = 'failed';

    public static function all()
    {
        return [
            self::SUCCESS,
            self::FAILED,
        ];
    }

    public static function getStatuses()
    {
        $statuses = [];

        foreach (self::all() as $status) {
            $statuses[$status] = trans('log::strings.statuses.' . $status);
        }

        return $statuses;
    }

    public static function getStatusColor(string $status) : string
    {
        $colors = [
            self::SUCCESS => 'success',
            self::FAILED  => 'danger',
        ];

        return $colors[$status] ?? 'secondary';
    }
}
