<?php

namespace Modules\Permission\Enums;

final class SystemDefaultRoles
{
    const ROOT_ROLE         = 'ROOT';
    const SYSTEM_ADMIN_ROLE = 'SYSTEM_ADMIN';

    public static function all()
    {
        return [
            self::ROOT_ROLE,
            self::SYSTEM_ADMIN_ROLE,
        ];
    }
}
