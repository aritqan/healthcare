<?php

namespace Modules\Auth\Enums\permissions;

final class UserPermissions
{
    const PERMISSION_NAMESPACE      = 'USER';
    const CREATE                    = 'CREATE_'                     . self::PERMISSION_NAMESPACE;
    const READ                      = 'READ_'                       . self::PERMISSION_NAMESPACE;
    const VIEW                      = 'VIEW_'                       . self::PERMISSION_NAMESPACE;
    const UPDATE                    = 'UPDATE_'                     . self::PERMISSION_NAMESPACE;
    const STATUS_UPDATE             = 'STATUS_UPDATE_'              . self::PERMISSION_NAMESPACE;
    const SOFT_DELETE               = 'SOFT_DELETE_'                . self::PERMISSION_NAMESPACE;
    const HARD_DELETE               = 'HARD_DELETE_'                . self::PERMISSION_NAMESPACE;
    const RESTORE                   = 'RESTORE_'                    . self::PERMISSION_NAMESPACE;
    const VIEW_TRASH                = 'VIEW_TRASH_'                 . self::PERMISSION_NAMESPACE;
    const DISABLE                   = 'DISABLE_'                    . self::PERMISSION_NAMESPACE;
    const ENABLE                    = 'ENABLE_'                     . self::PERMISSION_NAMESPACE;
    const LOGIN_TO_ANOTHER_ACCOUNT  = 'LOGIN_TO_ANOTHER_ACCOUNT_'   . self::PERMISSION_NAMESPACE;
    const SHOW_LOG                  = 'SHOW_LOG_'                   . self::PERMISSION_NAMESPACE;
}
