<?php

return [
    'notifications'                                             => 'الإشعارات',
    'mark_all_as_read'                                          => 'وضع علامة على الكل كمقروء',
    'notification_templates'                                    => [
        'welcome_in_our_platform'                               => [
            'title'                                             => 'مرحبا بك في منصتنا',
            'description'                                       => 'هذه رسالة ترحيب للمستخدم',
            'short_template'                                    => 'مرحبا {{username}} في منصتنا',
            'long_template'                                     => 'مرحبا {{username}} في منصتنا',
        ],
        'priority'                                              => [
            'low'                                               => 'منخفض',
            'medium'                                            => 'متوسط',
            'high'                                              => 'عالي',
            'default'                                           => 'افتراضي',
        ],
    ],
    'statuses'                                                  => [
        'delivered'                                             => 'تم الإستلام',
        'pending'                                               => 'قيد الإنتظار',
        'seen'                                                  => 'تمت مشاهدته',
        'failed'                                                => 'فشل',
        'read'                                                  => 'تمت القراءة',
    ],
    'added_by'                                                  => [
        'system'                                                => 'النظام',
        'admin'                                                 => 'المشرف',
    ],
];
