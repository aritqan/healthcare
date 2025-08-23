<?php

namespace Modules\Zms\Models;

use Illuminate\Http\JsonResponse;
use Modules\Base\Models\BaseModel;
use Modules\Base\Trait\Disableable;
use Yajra\DataTables\Facades\DataTables;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Zms\Trait\CountryTraitHelper;
use Modules\Zms\Enums\permissions\CountryPermissions;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Country extends BaseModel implements Auditable
{
    use Translatable, SoftDeletes, CountryTraitHelper, Disableable, AuditableTrait;

    // Start Properties

    const VIEW_PATH  = 'countries';

    protected $fillable = [
        'native_name',
        'iso3',
        'iso2',
        'phone_code',
        'currency',
        'currency_symbol',
        'lat',
        'lng',
    ];

    public $timestamps = false;

    public $translatedAttributes = [
        'name',
    ];

    protected $with = [
        'translations'
    ];

    // End Properties

    // Start Relationships

    public function states()
    {
        return $this->hasMany(State::class);
    }

    // End Relationships

    // Start Scopes
    public function scopeSimpleSearch($query, $search)
    {
        return $query->where(function($query) use($search) {
            $query->whereAny(
                ['id', 'native_name', 'iso3', 'phone_code', 'currency'],
                'LIKE',
                '%' . $search . '%'
            )->orWhereTranslationLike('name', '%' . $search . '%');
        });
    }
    // End Scopes

    // Start Get Data From Model

    public function getModel(int $id, bool $withTrashed = false, bool $withDisabled = false) : Country
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
        $model = $this::withCount('states')->withDisabled();

        if($this->shouldShowTrash($data, CountryPermissions::VIEW_TRASH)) {
            $model = $model->onlyTrashed();
        }

        return DataTables::of($model)
            ->filter(function ($query) use ($data) {
                if(isset($data['search']['value']) && !empty($data['search']['value'])){
                    $query->simpleSearch($data['search']['value']);
                }
            })
            ->addColumn('actions', function ($model) {
                $excludeActions = [VIEW_ACTION];

                return
                    app('customDataTable')
                    ->routePrefix('zms.countries')
                    ->of($model,  CountryPermissions::PERMISSION_NAMESPACE)
                    ->excludeActions($excludeActions)
                    ->getDatatableActions();
            })
            ->toJson();
    }

    public function getDataForApi($data, $isCollection = false)
    {
        $modelCollection = $this->with('states.cities');

        if($isCollection) {
            if (isset($data['q']) && !empty($data['q'])) {
                $term = trim($data['q']);

                $modelCollection = $modelCollection->simpleSearch($term);
            }

            return $modelCollection;
        }

        return $modelCollection->findOrFail($data['id']);
    }

    // End Get Data From Model

    // Start Mutators & Accessors


    // End Mutators & Accessors
}
