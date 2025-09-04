<?php

return [
    'notifications'                                             => 'Notifications',
    'mark_all_as_read'                                          => 'Mark all as read',
    'notification_templates'                                    => [
        'welcome_in_our_platform'                               => [
            'title'                                             => 'Welcome to NABD',
            'description'                                       => 'This is a welcome message for the user',
            'short_template'                                    => '
                Dear {{username}},

                Welcome to NABD! We are excited to have you with us.
                Your account has been successfully created. Please find your login credentials below:

                Username: {{email}}

                Temporary Password: <b> {{password}} </b>

                You can log in using the following link:
                🔗 {{loginUrl}}

                Please note that you are required to change your password upon your first login to ensure the security of your account.

                We wish you a great experience with our platform.
                If you have any questions, feel free to contact our support team.

                Best regards,
                NABD Team
            ',
            'long_template'                                     => '
                Dear {{username}},

                Welcome to NABD! We are excited to have you with us.
                Your account has been successfully created. Please find your login credentials below:

                Username: {{email}}

                Temporary Password: {{password}}

                You can log in using the following link:
                🔗 {{loginUrl}}

                Please note that you are required to change your password upon your first login to ensure the security of your account.

                We wish you a great experience with our platform.
                If you have any questions, feel free to contact our support team.

                Best regards,
                NABD Team
            ',
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
