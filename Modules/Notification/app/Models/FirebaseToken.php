<?php

namespace Modules\Notification\Models;

use Modules\Base\Models\BaseModel;

class FirebaseToken extends BaseModel
{
    protected $fillable = [
        'tokenable_type',
        'tokenable_id',
        'token',
        'type',
        'extra_data',
    ];

    public $timestamps = true;

    public const OS_ANDROID = 'android';

    public const OS_IOS = 'ios';

    public const TOPIC_ALL = 'all';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'extra_data' => 'array',
        ];
    }

    public function tokenable()
    {
        return $this->morphTo();
    }
}
