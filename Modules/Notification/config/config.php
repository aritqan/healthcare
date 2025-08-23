<?php

return [
    'enable_notification_in_admin_panel'    => false,

    'firebase_url'                          => 'https://fcm.googleapis.com/v1/projects/laravel-dashboard-c6fde/messages:send',
    'firebase_credentials'                  => storage_path('firebase/firebase-auth.json'),
    'fallback_locale'                       => 'ar',
    'firebase_config'                       => [
        'api_key'                           => 'AIzaSyCyiHlr63OsMLCthKpgcpGj8Lrj128wTRI',
        'auth_domain'                       => 'laravel-dashboard-c6fde.firebaseapp.com',
        'project_id'                        => 'laravel-dashboard-c6fde',
        'storage_bucket'                    => 'laravel-dashboard-c6fde.appspot.com',
        'messaging_sender_id'               => '632904193840',
        'app_id'                            => '1:632904193840:web:0cd227dd66f1b8d0509af5',
        'measurement_id'                    => 'G-E8CTH2GHTY',
    ],

    'firebase_subscribe_to_topic_url'       => 'https://iid.googleapis.com/iid/v1:batchAdd',
    'firebase_unsubscribe_from_topic_url'   => 'https://iid.googleapis.com/iid/v1:batchRemove',
    'firebase_token_info_url'               => 'https://iid.googleapis.com/iid/info/',

    'sendgrid'                              => [
        'url'                               => 'https://api.sendgrid.com/v3/mail/send',
        'api_key'                           => env('SENDGRID_API_KEY'),
        'from_email'                        => env('MAIL_FROM_ADDRESS'    , 'nsxezkcvwkuqqafeie@ytnhy.com'),
        'from_name'                         => env('MAIL_FROM_NAME'       , 'Example App'),
        'template_id'                       => env('SENDGRID_TEMPLATE_ID' , 'd-1b2b3b4b5b6b7b8b9b0b1b2b3b4b5b6'),
    ],
];
