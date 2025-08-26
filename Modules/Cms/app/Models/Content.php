<?php

namespace Modules\Cms\Models;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Modules\Admin\Models\Admin;
use Illuminate\Http\JsonResponse;
use Modules\Base\Models\BaseModel;
use Modules\Base\Trait\Disableable;
use Modules\Cms\Traits\ContentTrait;
use OwenIt\Auditing\Contracts\Auditable;
use Yajra\DataTables\Facades\DataTables;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditableTrait;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Modules\Cms\Enums\permissions\ContentPermissions;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Content extends BaseModel implements Auditable
{
    use Translatable, SoftDeletes, Disableable, HasFactory, ContentTrait, AuditableTrait;

    // Start Properties

    const VIEW_PATH = 'contents';

    protected $fillable = [
        'type',
        'sub_type',
        'slug',
        'link',
        'custom_properties',
        'can_be_deleted',
        'published_at',
        'placement',
        'value',
    ];

    public $timestamps = true;

    public $translatedAttributes = [
        'title',
        'short_description',
        'long_description',
    ];

    protected $with = [
        'translations'
    ];

    protected $appends = [
        'can_be_deleted_format',
        'placement_position',
        'created_at_format',
        'published_at_format',
    ];

    /**
     * Attributes to exclude from the Audit.
     *
     * @var array
     */
    protected $auditExclude = [];

    public const MEDIA_COLLECTION = 'content';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'custom_properties' => 'array',
        ];
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Modules\Cms\Database\Factories\ContentFactory::new();
    }

    /**
     * Transform the model for auditing.
     *
     * @param array $data
     * @return array
     */
    public function transformAudit(array $data): array
    {
        if (Arr::has($data, 'new_values.can_be_deleted')) {
            $data['old_values']['can_be_deleted'] = (bool) $data['old_values']['can_be_deleted'] ;
            $data['new_values']['can_be_deleted'] = (bool) $data['new_values']['can_be_deleted'] ;
        }

        return $data;
    }

    // End Properties

    // Start Relationships
    public function MainCategoriesParent()
    {
        return $this->belongsToMany(ContentCategory::class)->wherePivot('relation_type', 'main_category');
    }

    public function SubCategoriesParent()
    {
        return $this->belongsToMany(ContentCategory::class)->wherePivot('relation_type', 'subcategory');
    }

    public function tags()
    {
        return $this->belongsToMany(ContentTag::class, 'tag_content');
    }

    // End Relationships

    // Start Scopes
    public function scopeSimpleSearch($query, $search, $type)
    {
        return $query->byType($type)
            ->where(function($query) use($search) {
                $query->where('id', $search)
                ->orWhereTranslationLike('title', '%' . $search . '%');
            });
    }

    public function scopeAdvancedSearch($query, $search, $type)
    {
        return $query->byType($type);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }
    // End Scopes

    // Start Get Data From Model

    public function formAjaxArray($selected = true)
    {
        return [
            'id'            => $this->id,
            'text'          => $this->smartTrans('title'),
            'selected'      => $selected
        ];
    }

    public function findBySlug($slug, $locale = null)
    {
        $locale ??= config('cms.slug_default_locale');

        return $this->whereHas('translations', function ($query) use ($slug, $locale) {
            $query->where('slug', $slug)->where('locale', $locale);
        })->first();
    }

    public function getModel(int $id, bool $withTrashed = false, bool $withDisabled = false) : Content
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

    public function getDataTable(array $data, $permissionClass) : JsonResponse
    {
        $model              = $this::query()->byType($data['type'])->withDisabled();
        $permissionClass    = 'Modules\\Cms\\Enums\\permissions\\' . Str::studly($data['type']) . 'Permissions';

        if($this->shouldShowTrash($data, $permissionClass::VIEW_TRASH)) {
            $model = $model->onlyTrashed();
        }

        return DataTables::of($model)
            ->filter(function ($query) use ($data) {
                if(isset($data['search']['value']) && !empty($data['search']['value'])){
                    $query->simpleSearch($data['search']['value'], $data['type']);
                }
                if(isset($data['advanced_search']) && !empty($data['advanced_search'])){
                    $query->advancedSearch($data['advanced_search'], $data['type']);
                }
            })
            ->addColumn('placement', function ($model) {
                return $model->placement_position;
            })
            ->addColumn('image_url', function ($model) {
                if($this->typeHasField($model->type, 'image')) {
                    return $model->transImageUrl(self::MEDIA_COLLECTION, app()->getLocale(), 'thumb-100');
                }
            })
            ->addColumn('orginal_image_url', function ($model) {
                if($this->typeHasField($model->type, 'image')) {
                    return $model->transImageUrl(self::MEDIA_COLLECTION, app()->getLocale());
                }
            })
            ->addColumn('actions', function ($model) use($data, $permissionClass) {
                $excludeActions = [VIEW_ACTION];

                if(! $model->can_be_deleted) {
                    $excludeActions = array_merge($excludeActions, [SOFT_DELETE_ACTION, DISABLE_ACTION]);
                }

                return
                    app('customDataTable')
                    ->routePrefix('cms.contents')
                    ->setRouteParameters(['type' => $data['type']])
                    ->of($model, $permissionClass::PERMISSION_NAMESPACE)
                    ->excludeActions($excludeActions)
                    ->getDatatableActions();
            })
            ->toJson();
    }

    public function getDataForApi($data, $isCollection = false)
    {
        $modelCollection = $this->byType($data['type'] ?? null);

        if($isCollection) {
            if (isset($data['q']) && !empty($data['q'])) {
                $term = trim($data['q']);

                $modelCollection = $modelCollection->simpleSearch($term, $data['type'] ?? null);
            }

            return $modelCollection;
        }

        if(isset($data['slug'])) {
            $model = $this->findBySlug($data['slug']);
        }

        if(! isset($model) || empty($model)) {
            $model = $this->byType($data['type'])->findOrFail($data['slug']);
        }

        return $model;
    }

    // End Get Data From Model

    // Start Mutators & Accessors

    protected function canBeDeletedFormat() : Attribute
    {
        return Attribute::make(
            get: fn($value, $attribute) => trans('base::base.yes_no_boolean.' . $attribute['can_be_deleted']),
        );
    }

    protected function placementPosition() : Attribute
    {
        return Attribute::make(
            get: function($value, $attribute) {
                $key = null;

                if($this->typeHasField($this->type, 'placement')) {
                    $key = $this->custom_properties['placement'] ?? null;
                }

                return $key ? trans('cms::contents.sliders.placement.' . $key) : '--';
            }
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

    protected function publishedAtFormat() : Attribute
    {
        return Attribute::make(
            get: function($value, $attribute) {
                return Carbon::parse($this->published_at)->format('Y-m-d H:i:s');
            }
        );
    }
    // End Mutators & Accessors
}
