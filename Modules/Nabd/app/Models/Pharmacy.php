<?php

namespace Modules\Nabd\Models;

use Modules\Zms\Models\State;
use Illuminate\Http\JsonResponse;
use Modules\Base\Models\BaseModel;
use Modules\Base\Trait\Disableable;
use Yajra\DataTables\Facades\DataTables;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Nabd\Enums\permissions\PharmacyPermissions;

class Pharmacy extends BaseModel
{
    use Translatable, SoftDeletes, Disableable, HasFactory;

    // Start Properties

    const VIEW_PATH = 'pharmacies';

    protected $fillable = [
        'state_id',
    ];

    public $timestamps = true;

    public $translatedAttributes = [
        'name',
    ];

    protected $with = [
        'translations'
    ];

    protected $appends = [
        'state_name'
    ];
    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Modules\Nabd\Database\Factories\PharmacyFactory::new();
    }

    // End Properties

    // Start Relationships
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }
    // End Relationships

    // Start Scopes
    public function scopeSimpleSearch($query, $search)
    {
        return
        $query->where(function($query) use($search) {
            $query->where('id', $search);
        });
    }

    public function scopeAdvancedSearch($query, $search)
    {
        return $query;
    }
    // End Scopes

    // Start Get Data From Model

    public function formAjaxArray($selected = true)
    {
        return [
            'id'            => $this->id,
            'selected'      => $selected
        ];
    }

    public function getModel(int $id, bool $withTrashed = false, bool $withDisabled = false) : Pharmacy
    {
        $model = $this::query();

        if($withTrashed && $withDisabled) {
            return $model->withTrashed()->withDisabled()->findOrFail($id);
        } elseif($withTrashed) {
            return $model->withTrashed()->findOrFail($id);
        } elseif($withDisabled) {
            return $model->withDisabled()->findOrFail($id);
        }

        return $model->findOrFail($id);
    }

    public function getDataTable(array $data) : JsonResponse
    {
        $model = $this::query()->withDisabled();

        if($this->shouldShowTrash($data, PharmacyPermissions::VIEW_TRASH)) {
            $model = $model->onlyTrashed();
        }

        return DataTables::of($model)
            ->filter(function ($query) use ($data) {
                if(isset($data['search']['value']) && !empty($data['search']['value'])){
                    $query->simpleSearch($data['search']['value']);
                }
                if(isset($data['advanced_search']) && !empty($data['advanced_search'])){
                    $query->advancedSearch($data['advanced_search']);
                }
            })
            ->addColumn('name', function ($model) {
                return $model->smartTrans('name');
            })
            ->addColumn('actions', function ($model) {
                $excludeActions = [VIEW_ACTION];

                return
                    app('customDataTable')
                    ->routePrefix('nabd.pharmacies')
                    ->of($model, PharmacyPermissions::PERMISSION_NAMESPACE)
                    ->excludeActions($excludeActions)
                    ->getDatatableActions();
            })
            ->toJson();
    }

    public function getDataForApi($data, $isCollection = false) : mixed
    {
        $modelCollection = $this->query();

        if($isCollection) {
            return $modelCollection->latest();
        }

        return $modelCollection->findOrFail($data['id']);
    }
    // End Get Data From Model

    // Start Mutators & Accessors
    public function stateName() : Attribute
    {
        return Attribute::make(
            get: fn () => $this->state?->smartTrans('name'),
        );
    }
    // End Mutators & Accessors
}
