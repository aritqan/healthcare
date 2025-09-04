<?php

namespace Modules\Nabd\Models;

use Modules\Admin\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Modules\Nabd\Enums\MedicalFacilitesTypes;
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
        'medical_facility_id',
    ];

    public $timestamps = false;

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
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

    public function formAjaxArray($selected = true)
    {
        $text = $this->parentFacility?->admin?->full_name ?? $this->admin?->full_name;

        return [
            'id'            => $this->medical_facility_id,
            'text'          => $text,
            'selected'      => $selected
        ];
    }
}
