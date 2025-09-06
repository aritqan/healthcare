<?php

namespace Modules\Nabd\Models;

use Modules\Zms\Models\State;
use Modules\Admin\Models\Admin;
use Modules\Cms\Models\Content;
use Illuminate\Database\Eloquent\Model;
use Modules\Nabd\Enums\MedicalFacilitesTypes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalFacility extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'admin_id',
        'state_id',
        'type',
        'medical_specialty_id',
        'medical_facility_id',
    ];

    public $timestamps = false;

    protected $appends = [
        'state_name',
        'medical_specialty_name',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function parentFacility(): BelongsTo
    {
        return $this->belongsTo(MedicalFacility::class, 'medical_facility_id');
    }

    public function doctors(): HasMany
    {
        return $this->hasMany(MedicalFacility::class, 'medical_facility_id')
            ->where('type', MedicalFacilitesTypes::DOCTOR);
    }

    public function pharmacists(): HasMany
    {
        return $this->hasMany(MedicalFacility::class, 'medical_facility_id')
            ->where('type', MedicalFacilitesTypes::PHARMACIST);
    }

    public function medicalSpecialty(): BelongsTo
    {
        return $this->belongsTo(Content::class, 'medical_specialty_id');
    }

    public function formAjaxArray($selected = true)
    {
        $text = $this->parentFacility?->admin?->full_name ?? $this->admin?->full_name;

        return [
            'id'            => $this->medical_facility_id,
            'text'          => $text,
            'selected'      => $selected
        ];
    }

    protected function stateName(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => !empty($state = $this->state) ? $state->smartTrans('name') : '----',
        );
    }

    protected function medicalSpecialtyName(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => !empty($medicalSpecialty = $this->medicalSpecialty) ? $medicalSpecialty->smartTrans('title') : '----',
        );
    }
}
