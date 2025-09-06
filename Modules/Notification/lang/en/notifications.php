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
            'long_template' => '
                <div class="container">
                    <div class="header">
                        <h1>Welcome to NABD Platform</h1>
                        <p>An integrated platform to simplify your work and manage your projects</p>
                    </div>

                    <div class="content">
                        <div class="welcome">
                            <p>Dear <strong>{{username}}</strong>,</p>
                            <p>We welcome you to <strong>NABD Platform</strong> and we are pleased to have you with us. Your account has been successfully created, and here are your login details:</p>
                        </div>

                        <div class="credentials">
                            <div class="credential-item">
                                <span class="credential-label">Username:</span>
                                <span class="credential-value">{{email}}</span>
                            </div>
                            <div class="credential-item">
                                <span class="credential-label">Temporary Password:</span>
                                <span class="credential-value password">{{password}}</span>
                            </div>
                        </div>

                        <a href="{{loginUrl}}" class="login-button">Login to Your Account</a>

                        <p style="text-align: center; margin-bottom: 30px;">
                            Or you can copy the following link into your browser:<br>
                            <span style="color: #4a6fdc; word-break: break-all;">{{loginUrl}}</span>
                        </p>

                        <div class="note">
                            <h3>Important Note</h3>
                            <p>Please change your password upon first login to ensure the security of your account.</p>
                        </div>

                        <div class="support">
                            <p>We look forward to providing you with a unique and beneficial experience on our platform.</p>
                            <p>If you have any questions, don’t hesitate to contact the support team.</p>
                        </div>
                    </div>
                </div>
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
