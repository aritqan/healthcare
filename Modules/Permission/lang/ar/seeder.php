<?php

return [
    'ability_group'                         => [
        'role_management'                   => 'إدارة الأدوار',
        'user_management'                   => 'إدارة المستخدمين',
        'permission_management'             => 'إدارة الصلاحيات',
        'country_management'                => 'إدارة الدول',
        'admin_management'                  => 'إدارة المديرين',
        'setting_management'                => 'إدارة الإعدادات',
        'content_category_management'       => 'إدارة تصنيفات المحتوى',
        'content_management'                => 'إدارة المحتوى',
        'sliders_management'                => 'إدارة المحتوى [السلايدر]',
        'pages_management'                  => 'إدارة المحتوى [الصفحات]',
        'blogs_management'                  => 'إدارة المحتوى [المدونات]',
        'category_management'               => 'إدارة التصنيفات',
        'categories_management'             => 'إدارة التصنيفات',
        'content_tag_management'            => 'إدارة وسوم المحتوى',
        'notification_template_management'  => 'إدارة قوالب الإشعارات',
        'notification_management'           => 'إدارة الإشعارات',
        'contactus_management'              => 'إدارة رسائل التواصل',
        'subscribe_management'              => 'إدارة الإشتراكات',
        'api_log_management'                => 'إدارة سجلات الAPI',
    ],

    'roles'                                 => [
        'root'                              => [
            'name'                          => 'ROOT',
            'title'                         => 'مطور',
            'description'                   => 'جميع الصلاحيات مضافة إفتراضياً الى هذه المجموعة',
        ],
        'system_admin'                      => [
            'name'                          => 'SYSTEM_ADMIN',
            'title'                         => 'مدير النظام',
            'description'                   => 'جميع الصلاحيات الآمنة مضافة إفتراضياً الى هذه المجموعة',
        ],
        'user'                              => [
            'name'                          => 'USER',
            'title'                         => 'العميل / الزبون',
            'description'                   => 'لا توجد صلاحيات إفتراضية لهذه المجموعة',
        ],
        'employee'                          => [
            'name'                          => 'EMPLOYEE',
            'title'                         => 'الموظف',
            'description'                   => 'لا توجد صلاحيات إفتراضية لهذه المجموعة',
        ],
    ],

    'main_roles'                            => [
        'admins'                            => 'المشرفين',
        'users'                             => 'المستخدمين',
    ],
];
