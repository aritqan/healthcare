<?php

namespace Modules\Permission\Models;

use Illuminate\Http\JsonResponse;
use Modules\Base\Trait\ModelHelper;
use Yajra\DataTables\Facades\DataTables;
use Astrotomic\Translatable\Translatable;
use Modules\Permission\Enums\SystemDefaultRoles;
use Silber\Bouncer\Database\Role as DatabaseRole;
use Modules\Permission\Enums\permissions\RolePermissions;

class Role extends DatabaseRole
{
    use Translatable, ModelHelper;

    // Start Properties

    const VIEW_PATH = 'roles';

    protected $fillable = [
        'name',
        'title',
        'scope'
    ];

    public $translatedAttributes = ['title', 'description'];

    protected $with = [
        'translations'
    ];

    // End Properties

    // Start Glopal Scope
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('withoutRoot', function ($query) {
            $query->where('name', '!=', SystemDefaultRoles::ROOT_ROLE);
        });
    }
    // End Glopal Scope

    // Start Relationships

    // End Relationships

    // Start Scopes

    public function scopeSimpleSearch($query, $search)
    {
        return $query->where(function($query) use($search) {
            $query->whereAny(
                ['id', 'name'],
                'LIKE',
                '%' . $search . '%'
            )->orWhereTranslationLike('title', '%' . $search . '%');
        });
    }

    // End Scopes

    // Start Get Data From Model

    public function formAjaxArray($selected = true)
    {
        return [
            'id'            => $this->id,
            'code'          => $this->name,
            'title'         => $this->smartTrans('title'),
            'selected'      => $selected
        ];
    }

    public function getModel(int $id, bool $withTrashed = false, $withDisabled = false) : Role
    {
        $model = $this::with('abilities');

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
        $model = $this::with('abilities');

        if($this->shouldShowTrash($data, RolePermissions::VIEW_TRASH)) {
            $model = $model->onlyTrashed();
        }

        return DataTables::of($model)
        ->filter(function ($query) use($data){
            if(isset($data['search']['value']) && !empty($data['search']['value'])){
                $query->simpleSearch($data['search']['value']);
            }
        })
        ->addColumn('permissions', function($model) {
            $permissions = [];

            foreach($model->abilities->groupBy('ability_group_id') as $group) {
                $abilityGroup = $group->first()?->abilityGroup;
                if(!$abilityGroup) {
                    continue;
                }
                $permissions[] = [
                    'title'         => $abilityGroup->smartTrans('title'),
                    'icon'          => $abilityGroup->icon,
                    'permissions'   => $group->map(function($ability) {
                        return [
                            'id'    => $ability->id,
                            'code'  => $ability->name,
                            'title' => $ability->smartTrans('title')
                        ];
                    })->toArray()
                ];
            }

            return $permissions;
        })
        ->addColumn('actions', function($model) {
            $excludeActions = [VIEW_ACTION];

            return
            app('customDataTable')
            ->routePrefix('permission.roles')
            ->of($model, RolePermissions::PERMISSION_NAMESPACE)
            ->excludeActions($excludeActions)
            ->getDatatableActions();
        })
        ->toJson();
    }

    // End Get Data From Model

}
