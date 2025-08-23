<?php

return [
    'name' => 'Config',

    'app_logo'      => [
        'types'     => ['png', 'jpg', 'jpeg', 'webp', 'svg'],
        'max_size'  => 2, // (MB) 2mb
        'min_width' => 100,
        'min_height'=> 100,
        'max_width' => 2048,
        'max_height'=> 2048,
    ],

    'app_favicon'   => [
        'types'     => ['png', 'jpg', 'jpeg', 'webp', 'svg'],
        'max_size'  => 0.5, // (MB) 500kb
        'min_width' => 16,
        'min_height'=> 16,
        'max_width' => 512,
        'max_height'=> 512,
    ],
];
