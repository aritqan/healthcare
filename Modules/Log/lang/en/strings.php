<?php

return [
    'services'                      => [
        'firebase'                  => 'Firebase',
        'google'                    => 'Google',
        'sendgrid'                  => 'Sendgrid',
        'aws'                       => 'AWS',
    ],

    'statuses'                      => [
        'success'                   => 'Success',
        'failed'                    => 'Failed',
    ],

    'system'                        => 'System',
    'details'                       => 'Details',
    'view_modal_title'              => [
        'api_log'                   => 'API Log Details',
        'activity_log'              => 'Activity Log Details',
    ],
    'audit'                         => [
        'events'                    => [
            'created'               => 'Created',
            'updated'               => 'Updated',
            'deleted'               => 'Deleted',
            'restored'              => 'Restored',
            'role_changed'          => 'Role Changed',
            'update_translation'    => 'Update Translation',
        ],
        'metadata'                  => 'On :audit_created_at, :user_full_name [:audit_ip_address] <strong class="text-decoration-underline">:audit_event</strong> this record via <a class="text-decoration-underline" href=":audit_url" target="_blank">:audit_url</a>',
        'modified'                  => 'The <strong class="text-decoration-underline text-warning">:attribute</strong> has been modified from ',
    ],
];
