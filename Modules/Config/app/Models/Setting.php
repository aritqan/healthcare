<?php

namespace Modules\Config\Models;

use Modules\Base\Models\BaseModel;
use Modules\Base\Trait\ModelHelper;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Setting extends BaseModel
{
    use Translatable, ModelHelper;

    // Start Properties

    const VIEW_PATH = 'settings';

    protected $fillable = [
        'group',
        'type',
        'key',
        'value',
        'options',
        'order',
        'is_required',
        'translatable'
    ];

    public $timestamps = true;

    public $translatedAttributes = [
        'title',
        'description',
        'trans_value',
    ];

    protected $with = [
        'translations'
    ];

    protected $appends = [
        'media_url',
        'action_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_required'   => 'boolean',
            'translatable'  => 'boolean',
            'options'       => 'array',
        ];
    }

    // End Properties

    public function getAllSettings()
    {
        return $this->all();
    }

    // Start Mutators & Accessors

    protected function mediaUrl(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => getFileUrl($attributes['value']),
        );
    }

    protected function actionUrl(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => route($attributes['value']),
        );
    }

    // End Mutators & Accessors

}
