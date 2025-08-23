<?php

namespace Modules\Permission\Models;

use Illuminate\Http\JsonResponse;
use Modules\Base\Trait\ModelHelper;
use Yajra\DataTables\Facades\DataTables;
use Astrotomic\Translatable\Translatable;
use Modules\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Permission\Enums\permissions\AbilityPermissions;
class AbilityGroup extends BaseModel
{
    use Translatable, ModelHelper;

    // Start Properties

    const VIEW_PATH = 'permissions';

    protected $fillable = [
        'code',
        'icon',
    ];

    public $translatedAttributes = ['title', 'description'];

    protected $with = [
        'translations'
    ];

    // End Properties

    // Start Relationships

    public function abilities(): HasMany
    {
        return $this->hasMany(Ability::class);
    }

    // End Relationships

    // Start Scopes

    public function scopeSimpleSearch($query, $search)
    {
        return $query->where(function($query) use($search) {
            $query->whereAny(
                ['id', 'code'],
                'LIKE',
                '%' . $search . '%'
            )->orWhereTranslationLike('title', '%' . $search . '%');
        });
    }

    public function scopeWithAbilities($query)
    {
        return $query->with('abilities');
    }

    // End Scopes

    // Start Get Data From Model
    public function getModel(int|null $id = null, $withTrashed = false, $withDisabled = false)
    {
        $model = $this::withAbilities();

        if($id && $withTrashed && $withDisabled) {
            return $model->withTrashed()->withDisabled()->findOrFail($id);
        } elseif($id && $withTrashed) {
            return $model->withTrashed()->findOrFail($id);
        } elseif($id && $withDisabled) {
            return $model->withDisabled()->findOrFail($id);
        } elseif($id) {
            return $model->findOrFail($id);
        } else {
            return $model->get();
        }
    }

    public function getDataTable(array $data) : JsonResponse
    {
        $model = $this::withAbilities();

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
                    ->routePrefix('permission.permissions')
                    ->of($model, AbilityPermissions::PERMISSION_NAMESPACE)
                    ->excludeActions($excludeActions)
                    ->getDatatableActions();
            })
            ->toJson();
    }

    // End Get Data From Model
}
