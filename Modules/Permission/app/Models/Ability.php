<?php

namespace Modules\Permission\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Base\Trait\ModelHelper;
use Silber\Bouncer\Database\Ability as DatabaseAbility;

class Ability extends DatabaseAbility
{
    use Translatable, ModelHelper;

    // Start Properties

    protected $fillable = [
        'name',
        'group_id',
        'entity_id',
        'entity_type',
        'only_owned',
        'options',
        'scope',
    ];

    public $translatedAttributes = ['title', 'description'];

    protected $with = [
        'translations'
    ];

    // End Properties

    // Start Relationships

    public function abilityGroup(): BelongsTo
    {
        return $this->belongsTo(AbilityGroup::class);
    }

    // End Relationships
}
