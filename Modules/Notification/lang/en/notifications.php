<?php

return [
    'notifications'                                             => 'Notifications',
    'mark_all_as_read'                                          => 'Mark all as read',
    'notification_templates'                                    => [
        'welcome_in_our_platform'                               => [
            'title'                                             => 'Welcome in our platform',
            'description'                                       => 'This is a welcome message for the user',
            'short_template'                                    => 'Welcome {{username}} in our platform',
            'long_template'                                     => 'Welcome {{username}} in our platform',
        ],
        'priority'                                              => [
            'low'                                               => 'Low',
            'medium'                                            => 'Medium',
            'high'                                              => 'High',
            'default'                                           => 'Default',
        ],
    ],
    'statuses'                                                  => [
        'delivered'                                             => 'Delivered',
        'pending'                                               => 'Pending',
        'seen'                                                  => 'Seen',
        'failed'                                                => 'Failed',
        'read'                                                  => 'Read',
    ],
    'added_by'                                                  => [
        'system'                                                => 'System',
        'admin'                                                 => 'Admin',
    ],
];
