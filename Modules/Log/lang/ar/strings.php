<?php

return [
    'services'                      => [
        'firebase'                  => 'فايربيس',
        'google'                    => 'جوجل',
        'sendgrid'                  => 'سندجريد',
        'aws'                       => 'أمازون',
    ],

    'statuses'                      => [
        'success'                   => 'نجاح',
        'failed'                    => 'فشل',
    ],

    'system'                        => 'النظام',
    'details'                       => 'التفاصيل',
    'view_modal_title'              => [
        'api_log'                   => 'تفاصيل سجل API',
        'activity_log'              => 'تفاصيل سجل النشاط',
    ],
    'audit'                         => [
        'events'                    => [
            'created'               => 'بإضافة',
            'updated'               => 'بتعديل',
            'deleted'               => 'بحذف',
            'restored'              => 'باستعادة',
            'role_changed'          => 'بتغيير الدور',
            'update_translation'    => 'تحديث الترجمة',
        ],
        'metadata'                  => 'في :audit_created_at, :user_full_name [:audit_ip_address] قام <strong class="text-decoration-underline">:audit_event</strong> هذا السجل عبر <a class="text-decoration-underline" href=":audit_url" target="_blank">:audit_url</a>',
        'modified'                  => 'تم تعديل <strong class="text-decoration-underline text-warning">:attribute</strong> من ',
    ],
];
