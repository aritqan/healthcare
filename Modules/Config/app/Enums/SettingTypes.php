<?php

namespace Modules\Config\Enums;

final class SettingTypes
{
    public const TEXT       = 'text';
    public const TEXTAREA   = 'textarea';
    public const NUMBER     = 'number';
    public const SELECT     = 'select';
    public const RADIO      = 'radio';
    public const CHECKBOX   = 'checkbox';
    public const SWITCH     = 'switch';
    public const PHONE      = 'phone';
    public const FILE       = 'file';
    public const IMAGE      = 'image';
    public const DATE       = 'date';
    public const DATETIME   = 'datetime';
    public const URL        = 'url';
    public const BUTTON     = 'button';
    public const PARAGRAPH  = 'paragraph';

    public static function all() : array
    {
        return [
            self::TEXT,
            self::TEXTAREA,
            self::SELECT,
            self::CHECKBOX,
            self::RADIO,
            self::SWITCH,
            self::FILE,
            self::IMAGE,
            self::DATE,
            self::DATETIME,
            self::URL,
            self::BUTTON,
            self::PARAGRAPH,
            self::NUMBER,
            self::PHONE,
        ];
    }
}
