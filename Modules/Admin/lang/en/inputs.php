<?php

return [
    'base_crud'                     => [
        'title'                     => [
            'label'                 => 'Title [:locale]',
            'placeholder'           => 'Please enter a title',
            'help'                  => 'Enter a title (required)',
        ],
        'name'                      => [
            'label'                 => 'Name [:locale]',
            'placeholder'           => 'Please enter a name',
            'help'                  => 'Enter a name (required)',
        ],
        'description'               => [
            'label'                 => 'Description [:locale]',
            'placeholder'           => 'Please enter a description',
            'help'                  => 'Enter a description',
        ],
        'long_description'          => [
            'label'                 => 'General Description [:locale]',
            'placeholder'           => 'Please enter a general description',
            'help'                  => 'Enter a general description',
            'subText'               => 'Set a general description to improve the overall visibility of the content.',
        ],
        'email'                     => [
            'label'                 => 'Email',
            'placeholder'           => 'Email Address',
            'help'                  => 'Please enter Email Address',
        ],
        'current_password'          => [
            'label'                 => 'Current Password',
            'placeholder'           => 'Current Password',
            'help'                  => 'Please enter your current password',
        ],
        'password'                  => [
            'label'                 => 'Password',
            'placeholder'           => 'Password',
            'help'                  => 'Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character. example: Abc@1234',
        ],
        'password_confirmation'      => [
            'label'                 => 'Password Confirmation',
            'placeholder'           => 'Password Confirmation',
            'help'                  => 'Please enter the same password as above',
        ],
        'image'                     => [
            'label'                 => 'Image',
            'placeholder'           => 'Please select an image',
            'help'                  => 'Select an image',
            'subText'               => 'Allowed file types: :types | Maximum file size: :size MB',
        ],
        'image_lang'                => [
            'label'                 => 'Image [:locale]',
            'placeholder'           => 'Please select an image',
            'help'                  => 'Select an image',
            'subText'               => 'Allowed file types: :types | Maximum file size: :size MB',
        ],
        'status'                    => [
            'label'                 => 'Status',
            'placeholder'           => 'Please select a status',
            'help'                  => 'Select a status (required)',
        ],
        'lang'                      => [
            'label'                 => 'Language',
            'placeholder'           => 'Please select a Language',
            'help'                  => 'Select a Language (required)',
        ],
        'date_range'                => [
            'label'                 => 'Date Range',
            'placeholder'           => 'Please select a date range',
            'help'                  => 'Select a date range',
        ],
        'phone'                     => [
            'label'                 => 'Phone',
            'placeholder'           => 'Please enter a Phone',
            'help'                  => 'Enter a Phone (required)',
        ],
    ],

    'role_crud'                     => [
        'code'                      => [
            'label'                 => 'Code',
            'placeholder'           => 'Please enter a code',
            'help'                  => 'The code must be unique and contain only capital letters and underscores. example: ADMIN, CUSTOMER_SERVICE',
        ],
    ],

    'permission_crud'               => [
        'permission_type'           => [
            'label'                 => 'Permissions Type',
            'placeholder'           => 'Please select a permission type',
            'help'                  => 'Select a permission type',
            'data'                  => [
                'group_permission'  => 'Group Permissions (CRUD)',
                'sigle_permission'  => 'Single Permissions',
            ],
        ],
        'permission_group'          => [
            'label'                 => 'Permission Group',
            'placeholder'           => 'Please select a Permission group',
            'help'                  => 'Please select a Permission group to add permission (required)',
        ],
        'permission_name'           => [
            'label'                 => 'Permission Name',
            'placeholder'           => 'Please enter a permission name',
            'help'                  => 'The permission name must end with the name of the group to which it belongs. example: CREATE_ROLE',
        ],
        'code'                      => [
            'label'                 => 'Code',
            'placeholder'           => 'Please enter a code',
            'help'                  => 'The code must be unique and contain only capital letters and underscores. example: CONTENT, USER_MANAGEMENT',
        ],
        'icon'                      => [
            'label'                 => 'Icon',
            'placeholder'           => 'Please enter a icon',
            'help'                  => 'Font Awesome icon class name. example: fas fa-user, fas fa-cogs',
        ],
    ],

    'country_crud'                  => [
        'native_name'               => [
            'label'                 => 'Native Name',
            'placeholder'           => 'Please enter a Native Name',
            'help'                  => 'Enter a Native Name (required)',
        ],
        'iso2'                      => [
            'label'                 => 'ISO2',
            'placeholder'           => 'Please enter a ISO2',
            'help'                  => 'Enter a ISO2 (required)',
        ],
        'iso3'                      => [
            'label'                 => 'ISO3',
            'placeholder'           => 'Please enter a ISO3',
            'help'                  => 'Enter a ISO3 (required)',
        ],
        'phone_code'                => [
            'label'                 => 'Phone Code',
            'placeholder'           => 'Please enter a Phone Code',
            'help'                  => 'Enter a Phone Code (required)',
        ],
        'currency'                  => [
            'label'                 => 'Currency',
            'placeholder'           => 'Please enter a Currency',
            'help'                  => 'Enter a Currency (required)',
        ],
        'currency_symbol'           => [
            'label'                 => 'Currency Symbol',
            'placeholder'           => 'Please enter a Currency Symbol',
            'help'                  => 'Enter a Currency Symbol (required)',
        ],
        'lat'                       => [
            'label'                 => 'Latitude',
            'placeholder'           => 'Please enter a Latitude',
            'help'                  => 'Enter a Latitude (required)',
        ],
        'lng'                       => [
            'label'                 => 'Longitude',
            'placeholder'           => 'Please enter a Longitude',
            'help'                  => 'Enter a Longitude (required)',
        ],
    ],

    'admin_crud'                    => [
        'full_name'                 => [
            'label'                 => 'Full Name',
            'placeholder'           => 'Please enter a Full Name',
            'help'                  => 'Enter a Full Name (required)',
        ],
        'username'                  => [
            'label'                 => 'Username',
            'placeholder'           => 'Please enter a Username',
            'help'                  => 'Username must be unique and contain only english letters, numbers, and underscores. example: john_doe',
        ],
        'phone_number'              => [
            'label'                 => 'Phone Number',
            'placeholder'           => 'Phone Number',
            'help'                  => 'Enter a Phone Number',
        ],
        'avatar'                    => [
            'label'                 => 'Avatar',
            'placeholder'           => 'Please select an Avatar',
            'help'                  => 'Select an Avatar',
            'subText'               => 'Allowed file types: :types | Maximum file size: :size MB',
        ],
        'gender'                    => [
            'label'                 => 'Gender',
            'placeholder'           => 'Please select Gender',
            'help'                  => 'Select Gender (required)',
        ],
    ],

    'setting_crud'                  => [
        'group'                     => [
            'label'                 => 'Group',
            'placeholder'           => 'Please select a group',
            'help'                  => 'Select a group (required)',
        ],
        'type'                      => [
            'label'                 => 'Type',
            'placeholder'           => 'Please select a type',
            'help'                  => 'Select a type (required)',
        ],
        'key'                       => [
            'label'                 => 'Key',
            'placeholder'           => 'Please enter a key',
            'help'                  => 'The key must be unique and contain only lowercase letters, numbers, and underscores. example: site_title, site_description',
        ],
        'value'                     => [
            'label'                 => 'Value',
            'placeholder'           => 'Please enter a value',
            'help'                  => 'Enter a value (required)',
        ],
        'options'                   => [
            'label'                 => 'Options',
            'placeholder'           => 'Please enter options',
            'help'                  => 'Options enter as JSON format. example: {"key": "value"}.This field is required for the select type',
        ],
        'order'                     => [
            'label'                 => 'Order',
            'placeholder'           => 'Please enter an order',
            'help'                  => 'Enter an order (required)',
        ],
        'is_required'               => [
            'label'                 => 'Is Required ?',
            'placeholder'           => 'Please select required',
            'help'                  => 'Select is required ?',
        ],
        'translatable'              => [
            'label'                 => 'Is value has translation ?',
            'placeholder'           => 'Please select translatable',
            'help'                  => 'Select translatable (required)',
        ],
        'trans_value'               => [
            'label'                 => 'Translation Value [:locale]',
            'placeholder'           => 'Please enter a translation value',
            'help'                  => 'Enter a translation value',
        ],
    ],

    'content_category_crud'         => [
        'slug'                      => [
            'label'                 => 'Slug',
            'placeholder'           => 'Please enter a slug',
            'subText'               => 'The slug is used to generate the URL of the category. You can\'t change the slug after creating the category.',
            'help'                  => 'The slug must be unique and contain only lowercase letters and dash. example: news, articles, blogs',
        ],

        'parent_id'                 => [
            'label'                 => 'Parent Category',
            'placeholder'           => 'Select a Parent Category (optional)',
            'help'                  => 'Select a Parent Category (optional)',
        ],
        'can_be_deleted'            => [
            'label'                 => 'Can Be Deleted',
            'placeholder'           => 'Please select Can Be Deleted',
            'help'                  => 'Select Can Be Deleted',
        ],
    ],

    'contents_crud'                 => [
        'published_at'              => [
            'label'                 => 'Published At',
            'placeholder'           => 'Please select a date',
            'help'                  => 'Select a date (required)',
        ],
        'image'                     => [
            'label'                 => 'Content Image [:locale]',
            'help'                  => 'It is preferable that the image dimensions be: :dimentions and the image type be: :types',
            'subText'               => 'It is preferable that the image dimensions be: :dimentions and the image type be: :types',
            'placeholder'           => '',
        ],
        'slug'                      => [
            'label'                 => 'Slug',
            'placeholder'           => 'Please enter a slug',
            'help'                  => 'The slug is used to generate the URL of the content. You can\'t change the slug after creating the content.',
            'subText'               => 'The slug must be unique and contain only lowercase letters and dash. example: news, articles, blogs',
        ],
        'sliders'                   => [
            'placement'             => [
                'label'             => 'Placement of Slider',
                'placeholder'       => 'Please select a placement',
                'help'              => 'Select a placement (required)',
            ],
            'link'                  => [
                'label'             => 'Link',
                'placeholder'       => 'https://example.com',
                'help'              => 'Enter a link (optional)',
            ],
        ],
        'blogs'                     => [
            'tags'                  => [
                'label'             => 'Tags',
                'placeholder'       => 'Please select tags',
                'help'              => 'Select tags',
            ],
        ],
    ],

    'category_crud'                 => [
        'image'                     => [
            'label'                 => 'Category Image [:locale]',
            'help'                  => 'It is preferable that the image dimensions be: :dimentions and the image type be: :types',
            'subText'               => 'It is preferable that the image dimensions be: :dimentions and the image type be: :types',
            'placeholder'           => '',
        ],
    ],

    'user_crud'                     => [
        'first_name'                => [
            'label'                 => 'First Name',
            'placeholder'           => 'Please enter a First Name',
            'help'                  => 'Enter a First Name (required)',
        ],

        'last_name'                 => [
            'label'                 => 'Last Name',
            'placeholder'           => 'Please enter a Last Name',
            'help'                  => 'Enter a Last Name (required)',
        ],
    ],

    'notification_template_crud'    => [
        'name'                      => [
            'label'                 => 'Name',
            'placeholder'           => 'Please enter a Name',
            'help'                  => 'Enter a Name (required)',
            'subText'               => 'The name must be unique and contain only lowercase letters, numbers, and underscores. example: user_registered, order_created',
        ],
        'priority'                  => [
            'label'                 => 'Priority',
            'placeholder'           => 'Please select a Priority',
            'help'                  => 'Select a Priority (required)',
        ],
        'channels'                  => [
            'label'                 => 'Channels',
            'placeholder'           => 'Please select Channels',
            'help'                  => 'Select Channels (required)',
        ],
        'variables'                 => [
            'label'                 => 'Variables',
            'placeholder'           => 'Please select Variables',
            'help'                  => 'Select Variables (required)',
            'subText'               => 'Enter the variables that will be used in the template. example: user_name, order_number',
        ],
        'short_template'            => [
            'label'                 => 'Short Template',
            'placeholder'           => 'Please enter a Short Template',
            'help'                  => 'Enter a Short Template (required)',
            'subText'               => 'Short template is used for SMS and push notifications.',
        ],
        'long_template'             => [
            'label'                 => 'Long Template',
            'placeholder'           => 'Please enter a Long Template',
            'help'                  => 'Enter a Long Template (required)',
            'subText'               => 'Long template is used for email notifications.',
        ],
    ],

    'notification_crud'             => [
        'body'                      => [
            'label'                 => 'Body',
            'placeholder'           => 'Please enter a Body',
            'help'                  => 'Enter a Body (required)',
        ],
        'link'                      => [
            'label'                 => 'Link',
            'placeholder'           => 'https://example.com',
            'help'                  => 'Enter a Link (optional)',
            'subText'               => 'A link to be directed to when the user clicks on the notification for fcm_web and fcm_mobile channels.',
        ],
        'recipient'                 => [
            'label'                 => 'Recipient',
            'placeholder'           => 'Please select a Recipient',
            'help'                  => 'Select a Recipient (required)',
        ],
        'notification_template'     => [
            'label'                 => 'Notification Template',
            'placeholder'           => 'Please select a Notification Template',
            'help'                  => 'Select a Notification Template (required)',
        ],
        'added_by'                  => [
            'label'                 => 'Added By',
            'placeholder'           => 'Please select a Added By',
            'help'                  => 'Select a Added By (required)',
        ],
        'groups'                    => [
            'label'                 => 'Groups',
            'placeholder'           => 'Please select Groups',
            'help'                  => 'Select Groups (required)',
        ],
        'users'                     => [
            'label'                 => 'Users',
            'placeholder'           => 'Please select Users',
            'help'                  => 'Select Users',
            'subText'               => 'If no users are selected, the notification will be sent to all users in the selected groups.',
        ],
        'admins'                    => [
            'label'                 => 'Admins',
            'placeholder'           => 'Please select Admins',
            'help'                  => 'Select Admins',
            'subText'               => 'If no admins are selected, the notification will be sent to all admins.',
        ],
        'channels'                  => [
            'label'                 => 'Channels',
            'placeholder'           => 'Please select Channels',
            'help'                  => 'Select Channels (required)',
            'subText'               => 'Notice: If you choose either FCM_MOBILE or FCM_WEB and intend to send the notification to all users, you cannot select other channels (such as email or SMS). This is because the notification will be sent via a topic specific to FCM.',
        ],
    ],

    'contactus_crud'                => [
        'first_name'                => [
            'label'                 => 'First Name',
            'placeholder'           => 'Please enter a First Name',
            'help'                  => 'Enter a First Name (required)',
        ],
        'last_name'                 => [
            'label'                 => 'Last Name',
            'placeholder'           => 'Please enter a Last Name',
            'help'                  => 'Enter a Last Name (required)',
        ],
        'message'                   => [
            'label'                 => 'Message',
            'placeholder'           => 'Please enter a Message',
            'help'                  => 'Enter a Message (required)',
        ],
        'reply'                     => [
            'label'                 => 'Reply',
            'placeholder'           => 'Please enter a Reply',
            'help'                  => 'Enter a Reply',
        ],
        'ip_address'                => [
            'label'                 => 'IP Address',
            'placeholder'           => 'Please enter an IP Address',
            'help'                  => 'Enter an IP Address (required)',
        ],
        'user_agent'                => [
            'label'                 => 'User Agent',
            'placeholder'           => 'Please enter a User Agent',
            'help'                  => 'Enter a User Agent (required)',
        ],
        'submission_date'           => [
            'label'                 => 'Submission Date',
            'placeholder'           => 'Please select a Submission Date',
            'help'                  => 'Select a Submission Date (required)',
        ],
    ],

    'api_log_crud'                  => [
        'service_name'              => [
            'label'                 => 'Service Name',
            'placeholder'           => 'Please enter a Service Name',
            'help'                  => 'Enter a Service Name (required)',
        ],
        'method'                    => [
            'label'                 => 'Method',
            'placeholder'           => 'Please enter a Method',
            'help'                  => 'Enter a Method (required)',
        ],
        'endpoint'                  => [
            'label'                 => 'Endpoint',
            'placeholder'           => 'Please enter an Endpoint',
            'help'                  => 'Enter an Endpoint (required)',
        ],
        'request'                   => [
            'label'                 => 'Request',
            'placeholder'           => 'Please enter a Request',
            'help'                  => 'Enter a Request (required)',
        ],
        'response'                  => [
            'label'                 => 'Response',
            'placeholder'           => 'Please enter a Response',
            'help'                  => 'Enter a Response (required)',
        ],
        'status'                    => [
            'label'                 => 'Status',
            'placeholder'           => 'Please select a Status',
            'help'                  => 'Select a Status (required)',
        ],
        'status_code'               => [
            'label'                 => 'Status Code',
            'placeholder'           => 'Please enter a Status Code',
            'help'                  => 'Enter a Status Code (required)',
        ],
    ],

    'clinic_crud'                   => [
        'name'                      => [
            'label'                 => 'Name',
            'placeholder'           => 'Please enter a Name',
            'help'                  => 'Enter a Name (required)',
        ],
        'address'                   => [
            'label'                 => 'Address',
            'placeholder'           => 'Please enter an Address',
            'help'                  => 'Enter an Address (required)',
        ],
        'phone'                     => [
            'label'                 => 'Phone',
            'placeholder'           => 'Please enter a Phone',
            'help'                  => 'Enter a Phone (required)',
        ],
        'state_id'                  => [
            'label'                 => 'City',
            'placeholder'           => 'Please select a City',
            'help'                  => 'Select a City (required)',
        ],
    ],
];
