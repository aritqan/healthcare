<?php

return [
    'groups'                                => [
        'general'                           => [
            'title'                         => 'العامة',
            'fields'                        => [
                'app_name'                  => 'اسم التطبيق',
                'app_default_language'      => 'لغة التطبيق الافتراضية',
                'vat_rate'                  => 'نسبة ضريبة القيمة المضافة (%)',
                'maintenance_mode'          => 'وضع الصيانة',
            ],
        ],
        'social_media'                      => [
            'title'                         => 'وسائل التواصل الاجتماعي',
            'fields'                        => [
                'facebook'                  => 'فيسبوك',
                'twitter'                   => 'تويتر',
                'instagram'                 => 'انستجرام',
                'linkedin'                  => 'لينكد إن',
                'youtube'                   => 'يوتيوب',
                'tiktok'                    => 'تيك توك',
            ],
        ],
        'contact_info'                      => [
            'title'                         => 'معلومات الاتصال',
            'fields'                        => [
                'phone'                     => 'الهاتف',
                'email'                     => 'البريد الإلكتروني',
                'address'                   => 'العنوان',
            ],
        ],
        'media'                             => [
            'title'                         => 'وسائط',
            'fields'                        => [
                'app_logo'                  => 'شعار التطبيق',
                'app_mobile_logo'           => 'شعار التطبيق للهاتف المحمول',
                'email_logo'                => 'شعار البريد الإلكتروني',
                'app_favicon'               => 'الصورة المصغرة للموقع',
                'app_placeholder'           => 'الصورة الافتراضية',
            ],
        ],
        'developers'                        => [
            'title'                         => 'المطورين',
            'fields'                        => [
                'clear_cache'               => 'مسح الذاكرة المؤقتة',
                'clear_logs'                => 'مسح السجلات',
                'reset_permissions'         => 'إعادة تعيين الصلاحيات',
                'session_lifetime'          => [
                    'title'                 => 'مدة الجلسة',
                    'description'           => 'مدة الجلسة بالدقائق',
                ],
                'allow_debug_for_custom_ip' => 'تفعيل ال debug لعنوان IP المخصص',
                'custom_ips'                => [
                    'title'                 => 'عناوين IP المخصصة',
                    'description'           => 'أدخل عناوين IP مفصولة بفاصلة مثال : xxx.xxx.xxx.xxx, yyy.yyy.yyy.yyy',
                ],
            ],
        ],
    ],
];
