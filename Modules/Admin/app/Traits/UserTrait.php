<?php

namespace Modules\Admin\Traits;

use Carbon\Carbon;
use Modules\Admin\Enums\AdminStatus;
use Modules\Notification\Traits\FcmTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait UserTrait
{
    use FcmTrait;

    public function isActive()
    {
        return $this->status == AdminStatus::ACTIVE;
    }

    protected function password() : Attribute
    {
        return Attribute::make(
            set: fn ($value) => $value ? bcrypt($value) : null,
        );
    }

    protected function emailFormat(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => strlen($attributes['email']) > 20 ? substr($attributes['email'], 0, 20) . '...' : $attributes['email'],
        );
    }

    public function statusFormat() : Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => [
                'label' => AdminStatus::getStatuses()[$attributes['status']],
                'color' => AdminStatus::getStatusColor($attributes['status']),
            ],
        );
    }

    public function lastLoginFormat() : Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['last_login_at'] ? Carbon::parse($attributes['last_login_at'])->diffForHumans() : DEFAULT_DATE,
        );
    }

    public function createdAtFormat() : Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => getFormattedDate($attributes['created_at'], 'd M Y'),
        );
    }

    public function scopeActive($query)
    {
        return $query->where('status', AdminStatus::ACTIVE);
    }
}
