<?php

namespace Modules\Notification\Models;

use Illuminate\Http\JsonResponse;
use Modules\Base\Models\BaseModel;
use Yajra\DataTables\Facades\DataTables;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Notification\Enums\NotificationPriority;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Notification\Enums\permissions\NotificationTemplatePermissions;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class NotificationTemplate extends BaseModel implements Auditable
{
    use Translatable, SoftDeletes, HasFactory, AuditableTrait;

    // Start Properties

    const VIEW_PATH = 'notification_templates';

    protected $fillable = [
        'name',
        'channels',
        'variables',
        'priority',
    ];

    public $timestamps = true;

    public $translatedAttributes = [
        'title',
        'description',
        'short_template',
        'long_template',
    ];

    protected $with = [
        'translations'
    ];

    protected $appends = [
        'priority_format'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'channels'  => 'array',
            'variables' => 'array',
        ];
    }
    // End Properties

    // Start Relationships

    // End Relationships

    // Start Scopes
    public function scopeSimpleSearch($query, $search)
    {
        return $query->where(function($query) use($search) {
            $query->whereAny(
                ['id', 'name'],
                'LIKE',
                '%'.$search.'%'
            )->orWhereTranslationLike('title', '%'.$search.'%');
        });
    }

    public function scopeAdvancedSearch($query, $search)
    {
        return $query
            ->when(!empty($search['channels']), fn($q) => $q->whereJsonContains('channels', $search['channels']))
            ->when(!empty($search['priority']), fn($q) => $q->where('priority', $search['priority']));
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

    public function getModel(int $id, bool $withTrashed = false, bool $withDisabled = false) : NotificationTemplate
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

        if($this->shouldShowTrash($data, NotificationTemplatePermissions::VIEW_TRASH)) {
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
                $excludeActions = [VIEW_ACTION];

                return
                    app('customDataTable')
                    ->routePrefix('notification.notification_templates')
                    ->of($model, NotificationTemplatePermissions::PERMISSION_NAMESPACE)
                    ->excludeActions($excludeActions)
                    ->getDatatableActions();
            })
            ->toJson();
    }

    // End Get Data From Model

    // Start Mutators & Accessors
    protected function priorityFormat(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => [
                'label' => NotificationPriority::getPriorities()[$attributes['priority']],
                'color' => NotificationPriority::getColors()[$attributes['priority']],
            ],
        );
    }

    // End Mutators & Accessors
}
