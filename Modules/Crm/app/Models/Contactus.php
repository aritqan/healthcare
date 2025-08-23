<?php

namespace Modules\Crm\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use Modules\Base\Models\BaseModel;
use Yajra\DataTables\Facades\DataTables;
use Modules\Crm\Enums\ContactusStatuses;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Crm\Enums\permissions\ContactusPermissions;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Contactus extends BaseModel implements Auditable
{
    use SoftDeletes, HasFactory, AuditableTrait;

    // Start Properties

    const VIEW_PATH = 'contactuses';

    protected $fillable = [
        'type',
        'first_name',
        'last_name',
        'email',
        'phone',
        'message',
        'reply',
        'ip_address',
        'user_agent',
        'status',
        'locale',
    ];

    public $timestamps = true;

    protected $appends = [
        'full_name',
        'message_text',
        'status_format',
        'created_at_format',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Modules\Crm\Database\Factories\ContactusFactory::new();
    }

    // End Properties

    // Start Relationships

    // End Relationships

    // Start Scopes
    public function scopeSimpleSearch($query, $search)
    {
        return $query->whereAny(
            ['id', 'first_name', 'last_name', 'email', 'phone', 'message', 'reply', 'ip_address', 'user_agent'],
            'LIKE',
            '%' . $search . '%'
        );
    }

    public function scopeAdvancedSearch($query, $search)
    {
        return $query
            ->when(!empty($search['search']), fn($q) => $q->simpleSearch($search['search']));
    }

    public function canReply() : bool
    {
        return in_array($this->status, [ContactusStatuses::PENDING, ContactusStatuses::SEEN]);
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

    public function getModel(int $id, bool $withTrashed = false, bool $withDisabled = false) : Contactus
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

        if($this->shouldShowTrash($data, ContactusPermissions::VIEW_TRASH)) {
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
                $excludeActions = [UPDATE_ACTION];

                return
                    app('customDataTable')
                    ->routePrefix('crm.contactuses')
                    ->of($model, ContactusPermissions::PERMISSION_NAMESPACE)
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
    public function fullName() : Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $attributes['first_name'] . ' ' . $attributes['last_name'],
        );
    }

    public function messageText() : Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => Str::limit($attributes['message'], config('base.datatable_max_characters'), '...'),
        );
    }

    public function statusFormat() : Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => [
                'label' => ContactusStatuses::getStatuses()[$attributes['status']],
                'color' => ContactusStatuses::getStatusColor($attributes['status']),
            ],
        );
    }

    protected function createdAtFormat() : Attribute
    {
        return Attribute::make(
            get: function($value, $attribute) {
                return Carbon::parse($this->created_at)->locale(app()->getLocale())->diffForHumans();
            }
        );
    }
    // End Mutators & Accessors
}
