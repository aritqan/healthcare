<?php

return [
    'datatable'                         => asset('lang/ar/datatable.json'),
    'base_columns'                      => [
        'id'                            => 'ID',
        'image'                         => 'الصورة',
        'name'                          => 'الاسم',
        'slug'                          => 'الاسم الظاهري',
        'username'                      => 'اسم المستخدم',
        'gender'                        => 'الجنس',
        'title'                         => 'العنوان',
        'type'                          => 'النوع',
        'status'                        => 'الحالة',
        'description'                   => 'الوصف',
        'email'                         => 'البريد الإلكتروني',
        'phone_number'                  => 'رقم الهاتف',
        'created_at'                    => 'تاريخ الإنشاء',
        'updated_at'                    => 'تاريخ التعديل',
        'deleted_at'                    => 'تاريخ الحذف',
        'created_by'                    => 'تم الإنشاء بواسطة',
        'actions'                       => 'العمليات',
        'user_agent'                    => 'وكيل المستخدم',
        'ip_address'                    => 'عنوان ال IP',
    ],
    'buttons'                           => [
        'export'                        => 'تصدير',
        'refresh'                       => 'تحديث',
        'add_new'                       => 'إضافة جديد',
        'select_action'                 => 'اختر العملية',
    ],
    'roles'                             => [
        'list_title'                    => 'قائمة الأدوار',
        'columns'                       => [
            'code'                      => 'الرمز',
            'permissions'               => 'الصلاحيات',
        ]
    ],
    'permissions'                       => [
        'list_title'                    => 'قائمة الصلاحيات',
        'columns'                       => [
            'code'                      => 'الرمز',
        ]
    ],
    'countries'                         => [
        'list_title'                    => 'قائمة الدول',
        'columns'                       => [
            'native_name'               => 'الاسم الأصلي',
            'phone_code'                => 'رمز الهاتف',
            'currency'                  => 'العملة',
            'states_count'              => 'عدد الولايات',
            'cities_count'              => 'عدد المدن',
        ]
    ],
    'admins'                            => [
        'list_title'                    => 'قائمة المشرفين',
        'columns'                       => [
            'user'                      => 'المستخدم',
            'role'                      => 'الدور',
            'lang'                      => 'اللغة',
            'last_login_at'             => 'تاريخ آخر تسجيل دخول',
            'joined_date'               => 'تاريخ الانضمام',
        ]
    ],
    'content_categories'                => [
        'list_title'                    => 'قائمة تصنيفات المحتوى',
        'columns'                       => [
            'slug'                      => 'الاسم الظاهري',
            'parent'                    => 'التصنيف الاب',
            'can_be_deleted'            => 'يمكن حذفه',
        ]
    ],
    'contents'                          => [
        'list_title'                    => 'قائمة المحتوى',
        'columns'                       => [
            'category'                  => 'التصنيف',
            'updated_by'                => 'تم التعديل بواسطة',
            'published_at'              => 'تاريخ النشر',
        ],
        'sliders'                       => [
            'list_title'                => 'قائمة الشرائح',
            'columns'                   => [
                'placement'             => 'مكان ظهور الشريحة',
            ],
        ],
        'blogs'                         => [
            'list_title'                => 'قائمة المدونات',
            'columns'                   => [],
        ],
        'pages'                         => [
            'list_title'                => 'قائمة الصفحات',
            'columns'                   => [],
        ],
        'categories'                    => [
            'list_title'                => 'قائمة التصنيفات',
        ],
        'brands'                        => [
            'list_title'                => 'قائمة العلامات التجارية',
        ],
        'shapes'                        => [
            'list_title'                => 'قائمة الأشكال',
        ],
        'types_of_tiries'               => [
            'list_title'                => 'قائمة أنواع الإطارات',
        ],
        'colors'                        => [
            'list_title'                => 'قائمة الألوان',
        ],
        'materials'                     => [
            'list_title'                => 'قائمة المواد',
        ],
        'proportions'                   => [
            'list_title'                => 'قائمة التناسبات',
        ],
        'gender'                        => [
            'list_title'                => 'قائمة الأجناس',
        ],
        'home_page'                     => [
            'list_title'                => 'قائمة الصفحة الرئيسية',
        ],
    ],
    'categories'                        => [
        'list_title'                    => 'قائمة التصنيفات',
        'columns'                       => [
            'parent'                    => 'التصنيف الاب',
            'can_be_deleted'            => 'يمكن حذفه',
        ]
    ],
    'users'                             => [
        'list_title'                    => 'قائمة المستخدمين',
        'columns'                       => [
        ],
    ],
    'notification_templates'            => [
        'list_title'                    => 'قائمة قوالب الإشعارات',
        'columns'                       => [
            'priority'                  => 'الأولوية',
            'channels'                  => 'القنوات',
            'variables'                 => 'المتغيرات',
        ],
    ],
    'notifications'                     => [
        'list_title'                    => 'قائمة الإشعارات',
        'columns'                       => [
            'recipient'                 => 'المستلم',
            'added_by'                  => 'تمت الإضافة بواسطة',
            'link'                      => 'الرابط',
        ],
    ],
    'contactuses'                       => [
        'list_title'                    => 'قائمة طلبات التواصل',
        'columns'                       => [
            'message'                   => 'الرسالة',
            'reply'                     => 'الرد',
            'submission_date'           => 'تاريخ الإرسال',
        ],
    ],
    'subscribes'                        => [
        'list_title'                    => 'قائمة الإشتراكات',
        'columns'                       => [
            'is_active'                 => 'نشط',
            'subscription_date'         => 'تاريخ الإشتراك',
        ],
    ],
    'content_tags'                      => [
        'list_title'                    => 'قائمة وسوم المحتوى',
        'columns'                       => [
        ],
    ],
    'api_logs'                          => [
        'list_title'                    => 'قائمة سجلات ال API',
        'columns'                       => [
            'service_name'              => 'اسم الخدمة',
            'method'                    => 'الطريقة',
            'endpoint'                  => 'النقطة النهائية',
            'request'                   => 'الطلب',
            'response'                  => 'الاستجابة',
            'status_code'               => 'كود الحالة',
            'error'                     => 'خطأ',
        ],
    ],
    'activity_logs'                     => [
        'list_title'                    => 'قائمة سجلات النشاط',
        'columns'                       => [
            'user_made_action'          => 'المستخدم الذي قام بالعملية',
            'user_type'                 => 'نوع المستخدم',
            'event'                     => 'الحدث',
            'old_values'                => 'القيم القديمة',
            'new_values'                => 'القيم الجديدة',
            'action_date'               => 'تاريخ الحدث',
        ],
    ],
];
