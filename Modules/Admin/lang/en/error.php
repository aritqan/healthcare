<?php

return [

    'footer'    => 'Go back to <a href="' . route('admin.dashboard.index') . '">dashboard</a>.',

    '404_page'      => [
        'title'     => 'Not Found Page !!',
        'header'    => '404 - Not Found',
        'message'   => 'The page you are looking for might be under construction or does not exist.',
    ],

    '403_page'      => [
        'title'     => 'Forbidden Page !!',
        'header'    => '403 - Forbidden',
        'message'   => 'You are not allowed to access this page.',
    ],

    '405_page'      => [
        'title'     => 'Method Not Allowed !!',
        'header'    => '405 - Method Not Allowed',
        'message'   => 'The method you are trying to access is not allowed.',
    ],

    '500_page'      => [
        'title'     => 'Server Error !!',
        'header'    => '500 - Internal Server Error',
        'message'   => 'Oops! Something went wrong on our end. We are working to fix this issue as soon as possible.',
    ],

    '503_page'      => [
        'title'     => 'Service Unavailable !!',
        'header'    => '503 - Service Unavailable',
        'message'   => 'Sorry, we are currently under maintenance. Please check back later.',
    ],
];
