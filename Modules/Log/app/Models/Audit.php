<?php

namespace Modules\Log\Models;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Modules\Auth\Models\User;
use Modules\Admin\Models\Admin;
use Yajra\DataTables\Facades\DataTables;
use OwenIt\Auditing\Models\Audit as AuditModel;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Audit extends AuditModel
{
    protected $appends = [
        'user_type_name',
        'created_at_format',
        'event_format',
    ];

    // End Properties

    // Start Relationships

    // End Relationships

    // Start Scopes
    public function scopeSimpleSearch($query, $search)
    {
        return $query;
    }

    public function scopeAdvancedSearch($query, $search)
    {
        return $query;
    }
    // End Scopes

    // Start Get Data From Model

    public function getModel(int $id) : self
    {
        return $this->findOrFail($id);
    }

    public function getDataTable(array $data) : JsonResponse
    {
        $model = $this::where('auditable_type', $data['type'])
        ->where('auditable_id', $data['id'])
        ->with('user');

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
                $modelId = $model->id;
                $additionalActions[] = app('customDataTable')->viewAsModal(route('log.activity_log.viewAsModal', ['model' => $modelId]), $modelId, 'activityLogViewModal', trans('log::strings.details'));

                return app('customDataTable')->getDatatableActions(additionalActions: $additionalActions, withMainCrudActions: false);
            })

            ->toJson();
    }

    // End Get Data From Model

    // Start Mutators & Accessors

    public function userTypeName() : Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $userType = $attributes['user_type'];
                switch ($userType) {
                    case Admin::class:
                        return 'Admin';
                    case User::class:
                        return 'User';
                }
            }
        );
    }

    public function eventFormat() : Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $event = $attributes['event'];
                $badge = $this->eventBadge();

                return [
                    'label' => trans("log::strings.audit.events.{$event}"),
                    'color' => $badge[$event] ?? 'info',
                ];
            }
        );
    }

    public function createdAtFormat() : Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => Carbon::parse($attributes['created_at'])->diffForHumans()
        );
    }

    public function eventBadge() : array
    {
        return [
            'created'  => 'success',
            'updated'  => 'info',
            'deleted'  => 'danger',
            'restored' => 'warning',
        ];
    }

    // End Mutators & Accessors
}
