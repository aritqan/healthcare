<?php

namespace Modules\Auth\Models;

use Modules\Auth\Models\User;
use Modules\Base\Models\BaseModel;
use Modules\Zms\Models\City;
use Modules\Zms\Models\State;
use Modules\Zms\Models\Country;

class Address extends BaseModel
{
    protected $fillable = [
        'user_id',
        'country_id',
        'state_id',
        'city_id',
        'is_default',
        'title',
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'building',
        'street',
        'floor',
        'apartment',
        'address',
    ];

    public $timestamps = true;

    protected $with = [
        'country',
        'state',
        'city',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    // Start Get Data From Model
    public function getDataForApi($data, $isCollection = false) : mixed
    {
        $modelCollection = $this->where('user_id', $data['user_id']);

        if($isCollection) {
            return $modelCollection->latest();
        }

        return $modelCollection->findOrFail($data['id']);
    }
    // End Get Data From Model
}
