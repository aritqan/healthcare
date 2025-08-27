<?php

namespace Modules\Base\Enums;

trait EnumHelper
{
    /**
     * Get all enum cases.
     */
    public static function casesArray(): array
    {
        return array_map(fn ($case) => $case, self::cases());
    }

    /**
     * Get all enum values.
     */
    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }

    /**
     * Get all enum names.
     */
    public static function names(): array
    {
        return array_map(fn ($case) => $case->name, self::cases());
    }

    /**
     * Get key-value pairs (name => value).
     */
    public static function keyValue(): array
    {
        return array_column(self::cases(), 'value', 'name');
    }

    /**
     * Check if a value exists in enum.
     */
    public static function hasValue(string $value): bool
    {
        return in_array($value, self::values(), true);
    }

    /**
     * Check if a name exists in enum.
     */
    public static function hasName(string $name): bool
    {
        return in_array($name, self::names(), true);
    }
}
