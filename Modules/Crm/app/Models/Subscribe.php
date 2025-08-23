<?php

namespace Modules\Crm\Models;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Modules\Base\Models\BaseModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Crm\Enums\permissions\SubscribePermissions;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Subscribe extends BaseModel implements Auditable
{
    use SoftDeletes, HasFactory, AuditableTrait;

    // Start Properties

    const VIEW_PATH = 'subscribes';

    protected $fillable = [
        'email',
        'is_active',
    ];

    public $timestamps = true;

    protected $appends = [
        'created_at_format',
    ];

    // End Properties

    // Start Relationships

    // End Relationships

    // Start Scopes
    public function scopeSimpleSearch($query, $search)
    {
        return $query->whereAny(
            ['id', 'email'],
            'LIKE',
            '%' . $search . '%'
        );
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

    public function getModel(int $id, bool $withTrashed = false, bool $withDisabled = false) : Subscribe
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
        $model = $this::query();

        if($this->shouldShowTrash($data, SubscribePermissions::VIEW_TRASH)) {
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
            ->addColumn('actions', function ($model) {
                $excludeActions = [VIEW_ACTION, UPDATE_ACTION];

                return
                    app('customDataTable')
                    ->routePrefix('crm.subscribes')
                    ->of($model, SubscribePermissions::PERMISSION_NAMESPACE)
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

    protected function createdAtFormat() : Attribute
    {
        return Attribute::make(
            get: function($value, $attribute) {
                return Carbon::parse($this->created_at)->format('Y-m-d H:i:s');
            }
        );
    }
    // End Mutators & Accessors
}
