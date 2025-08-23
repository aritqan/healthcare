<?php

return [
    'ability_group'                         => [
        'role_management'                   => 'Roles Management',
        'user_management'                   => 'Users Management',
        'permission_management'             => 'Permissions Management',
        'country_management'                => 'Country Management',
        'admin_management'                  => 'Admin Management',
        'setting_management'                => 'Setting Management',
        'content_category_management'       => 'Content Category Management',
        'content_management'                => 'Content Management',
        'sliders_management'                => 'Content Management [Sliders]',
        'pages_management'                  => 'Content Management [Pages]',
        'blogs_management'                  => 'Content Management [Blogs]',
        'category_management'               => 'Category Management',
        'categories_management'             => 'Category Management',
        'content_tag_management'            => 'Content Tag Management',
        'notification_template_management'  => 'Notification Template Management',
        'notification_management'           => 'Notification Management',
        'contactus_management'              => 'Contact Us Management',
        'subscribe_management'              => 'Subscribe Management',
        'api_log_management'                => 'API Log Management',
    ],

    'roles'                                 => [
        'root'                              => [
            'name'                          => 'ROOT',
            'title'                         => 'Root',
            'description'                   => 'All permissions are added by default to this group',
        ],
        'system_admin'                      => [
            'name'                          => 'SYSTEM_ADMIN',
            'title'                         => 'System Admin',
            'description'                   => 'All safe permissions are added by default to this group',
        ],
        'user'                              => [
            'name'                          => 'USER',
            'title'                         => 'Client / Customer',
            'description'                   => 'There are no default permissions for this group',
        ],
        'employee'                          => [
            'name'                          => 'EMPLOYEE',
            'title'                         => 'Employee',
            'description'                   => 'There are no default permissions for this group',
        ],
    ],

    'main_roles'                            => [
        'admins'                            => 'Admins',
        'users'                             => 'Users',
    ],
];
