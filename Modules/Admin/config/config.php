<?php

return [
    'name'                      => 'Admin',
    'style_version'             => isDev() ? rand(1, 1000) : '1.0.0',
    'defalut_locale_key'        => 'ar',
    'avatar'                    => [
        'default'               => asset('images/default/avatars/blank.svg'),
    ],
    'main_roles'                => [
        'users'                 => 'users',
        'admins'                => 'admins',
    ],
];
