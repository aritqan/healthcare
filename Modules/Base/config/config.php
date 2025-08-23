<?php

return [
    'name' => 'Base',

    'datatable_max_characters'   => 50,

    'input_size'                => [
        'text'                  => [
            'min'               => 3,
            'max'               => 255,
        ],
        'textarea'              => [
            'min'               => 10,
            'max'               => 1000,
        ],
        'long_text'             => [
            'min'               => 20,
            'max'               => 50000,
        ],
    ],

    'file'                      => [
        'image'                 => [
            'max_size'          => 10,
            'accepted_types'    => ['png', 'jpg', 'jpeg', 'webp'],
        ],
        'document'              => [
            'max_size'          => 100,
            'accepted_types'    => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'],
        ],
    ],
];
