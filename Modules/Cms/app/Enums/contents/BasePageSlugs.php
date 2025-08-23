<?php

namespace Modules\Cms\Enums\contents;

final class BasePageSlugs
{
    const PRIVACY_POLICY        = 'privacy-policy';
    const TERMS_AND_CONDITIONS  = 'terms-and-conditions';
    const ABOUT_US              = 'about-us';

    public static function all(): array
    {
        return [
            self::PRIVACY_POLICY,
            self::TERMS_AND_CONDITIONS,
            self::ABOUT_US,
        ];
    }


}
