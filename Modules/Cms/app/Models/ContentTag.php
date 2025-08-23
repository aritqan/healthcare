<?php

namespace Modules\Cms\Models;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Modules\Base\Models\BaseModel;
use Modules\Base\Trait\Disableable;
use Yajra\DataTables\Facades\DataTables;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Cms\Enums\permissions\ContentTagPermissions;

class ContentTag extends BaseModel
{
    use Translatable, SoftDeletes, Disableable, HasFactory;

    // Start Properties

    const VIEW_PATH = 'content_tags';

    protected $fillable = [

    ];

    public $timestamps = true;

    public $translatedAttributes = [
        'title',
    ];

    protected $with = [
        'translations'
    ];

    protected $appends = [
        'created_at_format',
    ];

    // End Properties

    // Start Relationships

    // End Relationships

    // Start Scopes
    public function scopeSimpleSearch($query, $search)
    {
        return
        $query->where(function($query) use($search) {
            $query->where('id', $search)
            ->orWhereTranslationLike('title', '%' . $search . '%');
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
            'text'          => $this->smartTrans('title'),
            'selected'      => $selected
        ];
    }

    public function getModel(int $id, bool $withTrashed = false, bool $withDisabled = false) : ContentTag
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

        if($this->shouldShowTrash($data, ContentTagPermissions::VIEW_TRASH)) {
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
                    ->routePrefix('cms.content_tags')
                    ->of($model, ContentTagPermissions::PERMISSION_NAMESPACE)
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
                return Carbon::parse($this->created_at)->format('Y-m-d H:i');
            }
        );
    }
    // End Mutators & Accessors
}
