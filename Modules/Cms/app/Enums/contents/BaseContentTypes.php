<?php

namespace Modules\Cms\Enums\contents;

final class BaseContentTypes
{
    const TYPE_NAMESPACE        = 'content';
    const SLIDERS               = 'sliders';
    const PAGES                 = 'pages';
    const BLOGS                 = 'blogs';
    const MEDICAL_SPECIALTIES   = 'medical-specialties';

    public static function all(): array
    {
        return [
            self::SLIDERS,
            self::PAGES,
            self::BLOGS,
            self::MEDICAL_SPECIALTIES
        ];
    }
}
