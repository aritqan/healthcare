<?php

use Nwidart\Modules\Facades\Module;

return [

    $currentLocalKey = app()->getLocale() == 'ar' ? 'sa' : 'us',

    'logos'                             => [
        'aside_menu'                    => getSetting('app_logo'        , asset('images/default/logos/app_logo.svg')),
        'fav_icon'                      => getSetting('app_favicon'     , asset('images/default/logos/favicon.png')),
        'header_mobile'                 => getSetting('app_mobile_logo' , asset('images/default/logos/app_mobile_logo.svg')),
        'auth_page'                     => getSetting('app_mobile_logo' , asset('images/default/logos/app_mobile_logo.svg')),
    ],

    'error_pages'                       => [
        '404'                           => Module::asset('admin:metronic/demo/media/illustrations/sigma-1/18.png'),
        '405'                           => Module::asset('admin:metronic/demo/media/illustrations/sigma-1/20.png'),
        '500'                           => Module::asset('admin:metronic/demo/media/illustrations/sigma-1/9.png'),
        '503'                           => Module::asset('admin:metronic/demo/media/illustrations/sigma-1/5.png'),
    ],

    'auth_pages'                        => [
        'login_aside_menu_background'   => Module::asset('admin:metronic/demo/media/illustrations/sketchy-1/13.png'),
    ],

    'country_flag'                      => [
        'current_local'                 => Module::asset('admin:metronic/demo/media/flags/'. $currentLocalKey .'.svg'),
        'ar'                            => Module::asset('admin:metronic/demo/media/flags/sa.svg'),
        'en'                            => Module::asset('admin:metronic/demo/media/flags/us.svg'),
    ],

    'default_placeholder'               => getSetting('app_placeholder', asset('images/default/placeholder/global.png')),
];
