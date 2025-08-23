<?php

namespace Modules\Config\Enums;

final class SettingGroups
{
    public const GENERAL        = 'general';
    public const SOCIAL_MEDIA   = 'social_media';
    public const CONTACT_INFO   = 'contact_info';
    public const MEDIA          = 'media';
    public const DEVELOPERS     = 'developers';

    public static function all() : array
    {
        return [
            self::GENERAL,
            self::SOCIAL_MEDIA,
            self::CONTACT_INFO,
            self::MEDIA,
            self::DEVELOPERS,
        ];
    }

    public static function getGroups() : array
    {
        return [
            self::GENERAL       => trans('config::settings.groups.general.title'),
            self::SOCIAL_MEDIA  => trans('config::settings.groups.social_media.title'),
            self::CONTACT_INFO  => trans('config::settings.groups.contact_info.title'),
            self::MEDIA         => trans('config::settings.groups.media.title'),
            self::DEVELOPERS    => trans('config::settings.groups.developers.title'),
        ];
    }
}
