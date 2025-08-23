<?php

namespace Modules\Permission\Enums;

final class SystemDefaultRoles
{
    const ROOT_ROLE         = 'ROOT';
    const SYSTEM_ADMIN_ROLE = 'SYSTEM_ADMIN';
    const CLINIC            = 'CLINIC';
    const PHARMACY          = 'PHARMACY';
    const DOCTOR            = 'DOCTOR';
    const PHARMACIST        = 'PHARMACIST';

    public static function all()
    {
        return [
            self::ROOT_ROLE,
            self::SYSTEM_ADMIN_ROLE,
            self::CLINIC,
            self::PHARMACY,
            self::DOCTOR,
            self::PHARMACIST
        ];
    }
}
