<?php

use Modules\Admin\Enums\permissions\AdminPermissions;
use Modules\Config\Enums\permissions\SettingPermissions;
use Modules\Permission\Enums\permissions\RolePermissions;
use Modules\Permission\Enums\permissions\AbilityPermissions;

return [
    'models' => [
        'role'                                      => [
            'name'                                  => 'role',
            'icon'                                  => 'fas fa-user-cog',
            'additionalExcludePermissionsFromAdmin' => [RolePermissions::UPDATE, RolePermissions::HARD_DELETE, RolePermissions::DISABLE, RolePermissions::ENABLE, RolePermissions::RESTORE, RolePermissions::VIEW_TRASH, RolePermissions::SOFT_DELETE],
        ],
        'permission'                                => [
            'name'                                  => 'permission',
            'icon'                                  => 'bi-shield-fill-check',
            'additionalPermissions'                 => [AbilityPermissions::READ, AbilityPermissions::CREATE, AbilityPermissions::UPDATE , AbilityPermissions::HARD_DELETE],
            'additionalExcludePermissionsFromAdmin' => [AbilityPermissions::READ, AbilityPermissions::CREATE, AbilityPermissions::UPDATE],
            'withMainCrudAbility'                   => false
        ],
        'admin'                                     => [
            'name'                                  => 'admin',
            'icon'                                  => 'bi bi-person-fill-lock',
            'additionalPermissions'                 => [AdminPermissions::STATUS_UPDATE, AdminPermissions::LOGIN_TO_ANOTHER_ACCOUNT],
            'additionalExcludePermissionsFromAdmin' => [AdminPermissions::LOGIN_TO_ANOTHER_ACCOUNT],
        ],
        'setting'                                   => [
            'name'                                  => 'setting',
            'icon'                                  => 'fas fa-cog',
            'additionalPermissions'                 => [SettingPermissions::EXECUTE_ACTION],
            'additionalExcludePermissionsFromAdmin' => [SettingPermissions::EXECUTE_ACTION],
        ],
        'country'                                   => [
            'name'                                  => 'country',
            'icon'                                  => 'bi bi-globe-americas',
        ],
        'user'                                      => [
            'name'                                  => 'user',
            'icon'                                  => 'fas fa-users',
        ],
        'notification_template'                     => [
            'name'                                  => 'notification_template',
            'icon'                                  => 'fas fa-envelope',
        ],
        'notification'                              => [
            'name'                                  => 'notification',
            'icon'                                  => 'fas fa-bell',
        ],
        'contactus'                                 => [
            'name'                                  => 'contactus',
            'icon'                                  => 'fa-solid fa-paper-plane',
        ],
        'subscribe'                                 => [
            'name'                                  => 'subscribe',
            'icon'                                  => 'fa-regular fa-newspaper',
        ],
        'content_tag'                               => [
            'name'                                  => 'content_tag',
            'icon'                                  => 'fa-solid fa-tag',
        ],
        'content_category'                          => [
            'name'                                  => 'content_category',
            'icon'                                  => 'bi bi-layers-fill',
        ],
        'api_log'                                   => [
            'name'                                  => 'api_log',
            'icon'                                  => 'fa-solid fa-clock-rotate-left',
        ],
    ],
];
