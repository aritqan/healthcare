<?php

namespace Modules\Notification\Models;

use Modules\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Modules\Notification\Enums\NotificationStatuses;

class NotificationChannel extends BaseModel
{
    protected $fillable = [
        'notification_id',
        'status',
        'is_fcm_mobile',
        'is_fcm_web',
        'is_email',
        'is_sms',
        'is_database'
    ];

    public $timestamps = false;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_fcm_mobile' => 'boolean',
            'is_fcm_web'    => 'boolean',
            'is_email'      => 'boolean',
            'is_sms'        => 'boolean',
            'is_database'   => 'boolean'
        ];
    }

    public function notification()
    {
        return $this->belongsTo(Notification::class);
    }

    public function scopeSuccessfully($query)
    {
        return $query->whereIn('status', [NotificationStatuses::DELIVERED, NotificationStatuses::SEEN, NotificationStatuses::READ]);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', NotificationStatuses::DELIVERED);
    }

    public function scopeUnRead($query)
    {
        return $query->whereIn('status', [NotificationStatuses::DELIVERED, NotificationStatuses::SEEN]);
    }

    public function scopeIsWeb($query)
    {
        return $query->where('is_fcm_web', true);
    }

    public function scopeIsMobile($query)
    {
        return $query->where('is_fcm_mobile', true);
    }

    public function scopeIsEmail($query)
    {
        return $query->where('is_email', true);
    }

    protected function statusFormat(): Attribute
    {
        return Attribute::make(
            get: function($value, $attributes) {
                return [
                    'label' => trans('notification::notifications.statuses.' . $attributes['status']),
                    'color' => NotificationStatuses::getColors()[$attributes['status']]
                ];
            }
        );
    }

}
