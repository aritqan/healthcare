<?php

namespace Modules\Zms\Models;

use Illuminate\Http\JsonResponse;
use Modules\Base\Trait\ModelHelper;
use Yajra\DataTables\Facades\DataTables;
use Astrotomic\Translatable\Translatable;
use Modules\Base\Models\BaseModel;
use Modules\Zms\Trait\CountryTraitHelper;
use Modules\Zms\Enums\permissions\CountryPermissions;


class State extends BaseModel
{
    use Translatable, ModelHelper, CountryTraitHelper;

    // Start Properties

    const VIEW_PATH  = 'states';

    protected $fillable = [
        'country_id',
        'native_name',
        'lat',
        'lng',
    ];

    public $timestamps = false;

    public $translatedAttributes = ['name'];

    protected $with = [
        'translations'
    ];

    // End Properties

    // Start Relationships

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }

    // End Relationships

    // Start Scopes
    public function scopeSimpleSearch($query, $search)
    {
        return $query->where(function($query) use($search) {
            $query->whereAny(
                ['id', 'native_name'],
                'LIKE',
                '%' . $search . '%'
            )->orWhereTranslationLike('name', '%' . $search . '%');
        });
    }
    // End Scopes

    // Start Get Data From Model

    public function getModel(int $id, bool $withTrashed = false, $withDisabled = false) : State
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
        // Check if data has country_id key get the states of this country if not get all states
        $model = $this::where('country_id', $data['country_id'] ?? null)->withCount('cities');

        return DataTables::of($model)
            ->filter(function ($query) use ($data) {
                if(isset($data['search']['value']) && !empty($data['search']['value'])){
                    $query->simpleSearch($data['search']['value']);
                }
            })
            ->addColumn('actions', function ($model) use ($data){
                $excludeActions = [VIEW_ACTION];

                return
                    app('customDataTable')
                    ->routePrefix('zms.states')
                    ->of($model, CountryPermissions::PERMISSION_NAMESPACE)
                    ->excludeActions($excludeActions)
                    ->getDatatableActions();
            })
            ->toJson();
    }

    // End Get Data From Model

    // Start Mutators & Accessors

    // End Mutators & Accessors
}
