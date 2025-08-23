<?php

return [
    'datatable'                         => asset('lang/en/datatable.json'),
    'base_columns'                      => [
        'id'                            => 'ID',
        'image'                         => 'Image',
        'name'                          => 'Name',
        'slug'                          => 'Slug',
        'username'                      => 'Username',
        'gender'                        => 'Gender',
        'title'                         => 'Title',
        'type'                          => 'Type',
        'status'                        => 'Status',
        'description'                   => 'Description',
        'email'                         => 'Email',
        'phone_number'                  => 'Phone Number',
        'created_at'                    => 'Created At',
        'updated_at'                    => 'Updated At',
        'deleted_at'                    => 'Deleted At',
        'created_by'                    => 'Created By',
        'actions'                       => 'Actions',
        'user_agent'                    => 'User Agent',
        'ip_address'                    => 'IP Address',
    ],
    'buttons'                           => [
        'export'                        => 'Export',
        'refresh'                       => 'Refresh',
        'add_new'                       => 'Add New',
        'select_action'                 => 'Select Action',
    ],
    'roles'                             => [
        'list_title'                    => 'Roles List',
        'columns'                       => [
            'code'                      => 'Code',
            'permissions'               => 'Permissions',
        ]
    ],
    'permissions'                       => [
        'list_title'                    => 'Permissions List',
        'columns'                       => [
            'code'                      => 'Code',
        ]
    ],
    'countries'                         => [
        'list_title'                    => 'Countries List',
        'columns'                       => [
            'native_name'               => 'Native Name',
            'phone_code'                => 'Phone Code',
            'currency'                  => 'Currency',
            'states_count'              => 'States Count',
            'cities_count'              => 'Cities Count',
        ]
    ],
    'admins'                            => [
        'list_title'                    => 'Admins List',
        'columns'                       => [
            'user'                      => 'User',
            'role'                      => 'Role',
            'lang'                      => 'Language',
            'last_login_at'             => 'Last Login At',
            'joined_date'               => 'Joined Date',
        ]
    ],
    'content_categories'                => [
        'list_title'                    => 'Content Categories List',
        'columns'                       => [
            'slug'                      => 'Slug',
            'parent'                    => 'Parent Category',
            'can_be_deleted'            => 'Can Be Deleted',
        ]
    ],
    'contents'                          => [
        'list_title'                    => 'Contents List',
        'columns'                       => [
            'category'                  => 'Category',
            'updated_by'                => 'Updated By',
            'published_at'              => 'Published At',
        ],
        'sliders'                       => [
            'list_title'                => 'Sliders List',
            'columns'                   => [
                'placement'             => 'Placement of Slider',
            ]
        ],
        'blogs'                         => [
            'list_title'                => 'Blogs List',
        ],
        'pages'                         => [
            'list_title'                => 'Pages List',
        ],
        'categories'                    => [
            'list_title'                => 'Categories List',
        ],
        'brands'                        => [
            'list_title'                => 'Brands List',
        ],
        'shapes'                        => [
            'list_title'                => 'Shapes List',
        ],
        'types_of_tires'                => [
            'list_title'                => 'Types of Tires List',
        ],
        'colors'                        => [
            'list_title'                => 'Colors List',
        ],
        'materials'                     => [
            'list_title'                => 'Materials List',
        ],
        'proportions'                   => [
            'list_title'                => 'Proportions List',
        ],
        'gender'                        => [
            'list_title'                => 'Gender List',
        ],
        'home_page'                     => [
            'list_title'                => 'Home Page List',
        ],
    ],
    'categories'                        => [
        'list_title'                    => 'Categories List',
        'columns'                       => [
            'parent'                    => 'Parent Category',
            'can_be_deleted'            => 'Can Be Deleted',
        ]
    ],
    'users'                             => [
        'list_title'                    => 'Users List',
        'columns'                       => [
        ],
    ],
    'notification_templates'            => [
        'list_title'                    => 'Notification Templates List',
        'columns'                       => [
            'priority'                  => 'Priority',
            'channels'                  => 'Channels',
            'variables'                 => 'Variables',
        ],
    ],
    'notifications'                     => [
        'list_title'                    => 'Notifications List',
        'columns'                       => [
            'recipient'                 => 'Recipient',
            'added_by'                  => 'Added By',
            'link'                      => 'Link',
            'sent_at'                   => 'Sent At',
        ],
    ],
    'contactuses'                       => [
        'list_title'                    => 'Contact Us Requests List',
        'columns'                       => [
            'message'                   => 'Message',
            'reply'                     => 'Reply',
            'submission_date'           => 'Submission date',
        ],
    ],
    'subscribes'                        => [
        'list_title'                    => 'Subscriptions List',
        'columns'                       => [
            'is_active'                 => 'Is Active',
            'subscription_date'         => 'Subscription Date',
        ],
    ],
    'content_tags'                      => [
        'list_title'                    => 'Content Tags List',
        'columns'                       => [
        ],
    ],
    'api_logs'                          => [
        'list_title'                    => 'Api Logs List',
        'columns'                       => [
            'service_name'              => 'Service Name',
            'method'                    => 'Method',
            'endpoint'                  => 'Endpoint',
            'status_code'               => 'Status Code',
            'error'                     => 'Error',
            'request'                   => 'Request',
            'response'                  => 'Response',
        ],
    ],
    'activity_logs'                     => [
        'list_title'                    => 'Activity Logs List',
        'columns'                       => [
            'user_made_action'          => 'User Made Action',
            'user_type'                 => 'User Type',
            'event'                     => 'event',
            'old_values'                => 'Old Values',
            'new_values'                => 'New Values',
            'action_date'               => 'Action Date',
        ],
    ],
];
