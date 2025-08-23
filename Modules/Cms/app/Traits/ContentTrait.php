<?php

namespace Modules\Cms\Traits;

use Illuminate\Support\Collection;
use Modules\Cms\Enums\contents\BaseContentTypes;
trait ContentTrait
{
    public static $imageTypes = [
        'jpg',
        'jpeg',
        'png',
        'webp',
    ];

    public static $typeList = [
        BaseContentTypes::SLIDERS => [
            'icon'                  => 'fas fa-sliders-h',
            'show_in_menu'          => true,
            'name'                  => 'cms::contents.content_categories.sliders',
            'fields'                => [
                'title'             => [
                    'required'      => true,
                ],
                'link'              => [
                    'required'      => false,
                ],
                'image'             => [
                    'required'      => true,
                    'dimensions'    => [
                        'preview'   => '600x220',
                        '400x200',
                    ],
                ],
                'select'            => [
                    'placement'     => [
                        'required'  => true,
                        'multiple'  => false,
                        'isAjax'    => false,
                        'data'      => [
                            'home'  => 'cms::contents.sliders.placement.home',
                        ],
                    ],
                ],
            ],
        ],

        BaseContentTypes::BLOGS     => [
            'icon'                  => 'fas fa-blog',
            'show_in_menu'          => true,
            'name'                  => 'cms::contents.content_categories.blogs',
            'fields'                => [
                'title'             => [
                    'required'      => true,
                ],
                'long_description'  => [
                    'required'      => true,
                ],
                'image'             => [
                    'required'      => true,
                    'dimensions'    => [
                        'preview'   => '200x200',
                        '400x200',
                    ],
                ],
                'select'            => [
                    'tags'          => [
                        'required'  => false,
                        'multiple'  => true,
                        'isAjax'    => true,
                        'clearable' => true,
                        'withImg'   => false,
                        'data'      => 'cms.content_tags.ajaxList',
                    ],
                ],
                'published_at'      => [
                    'required'      => true,
                ],
            ],
        ],

        BaseContentTypes::PAGES     => [
            'icon'                  => 'fas fa-file-alt',
            'show_in_menu'          => true,
            'name'                  => 'cms::contents.content_categories.pages',
            'fields'                => [
                'title'             => [
                    'required'      => true,
                ],
                'long_description'  => [
                    'required'      => true,
                ],
                'can_be_deleted'    => [
                    'required'      => true,
                ],
            ],
        ],
    ];

    /**
     * Get all content types list as collection
     */
    public static function types(): Collection
    {
        return collect(static::$typeList);
    }

    /**
     * Check if type exists in the list
     */
    public static function typeExists($type): bool
    {
        return static::types()->has($type);
    }

    /**
     * Get type info by type as array
     */
    public static function getTypeInfo($type): array|null
    {
        return static::types()->first(function ($value, $key) use ($type) {
            return $key == $type;
        });
    }

    /**
     * Returns an array of fields for the given type
     */
    public static function getTypeFields($type): array
    {
        return static::getTypeInfo($type)['fields'] ?? [];
    }

    /**
     * Returns an array of content type's Field names for the given type
     */
    public static function getTypeFieldNames($type): array
    {
        return array_keys(static::getTypeFields($type));
    }

    /**
     * Check if content type has a field
     */
    public static function typeHasField($type, $field): bool
    {
        return in_array($field, static::getTypeFieldNames($type));
    }

    /**
     * Check if type has select field
     */
    public static function typeHasSelectField($type): bool
    {
        return collect(static::getTypeFields($type))->has('select');
    }

    /**
     * Get Select Field
     */
    public static function getSelectField($type): array|null
    {
        return collect(static::getTypeFields($type))->get('select');
    }

    /**
     * Get Type Title
     */
    public static function getTypeTitle($type, $plural = true): string
    {
        $typeInfo = static::getTypeInfo($type);

        if (!$typeInfo || !isset($typeInfo['name'])) {
            return '';
        }

        return $plural ? trans_choice($typeInfo['name'], 1) : trans_choice($typeInfo['name'], 0);
    }

    /**
     * Get Type Field Data
     */
    public static function getTypeFieldData($type, $field, $key = 'data'): mixed
    {
        return static::getTypeFields($type)[$field][$key] ?? null;
    }

    /**
     * Get Type Field Data For Select Field
     */
    public static function getSelectData($type, $field, $key = 'data'): mixed
    {
        $selectField = static::getTypeFields($type)['select'];

        return $selectField[$field][$key] ?? null;
    }

    /**
     * Get Image Dimensions By Type
     */
    public static function getImageDimensions($type): Collection
    {
        return collect(static::getTypeFieldData($type, 'image', 'dimensions'));
    }

    /**
     * Get Image Preview Dimension By Type
     *
     * @param string $type
     * @return array
     */
    public static function getImagePreviewDimension($type): array
    {
        $imageDimensions = static::getImageDimensions($type);

        return $imageDimensions->has('preview') ? explode('x', $imageDimensions->get('preview')) : [];
    }

    /**
     * Get Image Types By Type
     */
    public static function getImageTypes(): string
    {
        return implode(' | ', static::$imageTypes);
    }

    /**
     * Check if content type is visible in menu
     */
    public static function isVisibleInMenu($type): bool
    {
        return static::getTypeInfo($type)['show_in_menu'] ?? false;
    }

    /**
     * Check if content type field is required
     */
    public static function isFieldRequired($type, $field): bool
    {
        return static::getTypeFields($type)[$field]['required'] ?? false;
    }

    public static function getSubType($type): string|null
    {
        return static::getTypeInfo($type)['sub_type'] ?? null;
    }
}
