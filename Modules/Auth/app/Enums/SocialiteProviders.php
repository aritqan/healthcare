<?php

namespace Modules\Auth\Enums;

final class SocialiteProviders
{
    const GOOGLE    = 'google';
    const FACEBOOK  = 'facebook';
    const TWITTER   = 'twitter';
    const GITHUB    = 'github';

    public static function all()
    {
        return [
            self::GOOGLE,
            self::FACEBOOK,
            self::TWITTER,
            self::GITHUB,
        ];
    }
}
