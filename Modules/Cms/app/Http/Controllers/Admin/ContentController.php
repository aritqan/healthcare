<?php

namespace Modules\Cms\Http\Controllers\Admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\Cms\Models\Content;
use Illuminate\Support\Facades\Route;
use Modules\Cms\Http\Requests\ContentRequest;
use Modules\Cms\Http\Services\ContentService;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Base\Http\Controllers\BaseCrudController;

class ContentController extends BaseCrudController
{
    protected $model;

    protected $crudService;

    protected $module           = 'cms';

    protected $routePrefix      = 'cms.contents';

    protected $createRequest    = ContentRequest::class;

    protected $updateRequest    = ContentRequest::class;

    protected static $permissionClass;

    protected $type                     = null;

    protected static $hasPermission     = true;

    protected $hasSoftDelete            = true;

    protected $hasDisabled              = true;

    protected $hasBulkActions           = true;

    public static function middleware(): array
    {
        if(app()->runningInConsole()) return [];

        $permissionClass = 'Modules\\Cms\\Enums\\permissions\\' . Str::studly(request('type')) . 'Permissions';

        if (!class_exists($permissionClass)) {
            abort(404, 'Permission class not found.');
        }

        return array_merge(['active.admin'], [
            new Middleware('need.permissions:' . $permissionClass::READ,        only : ['index', 'datatable', 'ajaxList']),
            new Middleware('need.permissions:' . $permissionClass::VIEW,        only : ['view', 'viewAsModal']),
            new Middleware('need.permissions:' . $permissionClass::CREATE,      only : ['create', 'postCreate']),
            new Middleware('need.permissions:' . $permissionClass::UPDATE,      only : ['update', 'postUpdate']),
            new Middleware('need.permissions:' . $permissionClass::SOFT_DELETE, only : ['softDelete', 'bulkSoftDelete']),
            new Middleware('need.permissions:' . $permissionClass::HARD_DELETE, only : ['hardDelete', 'bulkHardDelete']),
            new Middleware('need.permissions:' . $permissionClass::RESTORE,     only : ['restore', 'bulkRestore']),
            new Middleware('need.permissions:' . $permissionClass::DISABLE,     only : ['disable', 'bulkDisable']),
            new Middleware('need.permissions:' . $permissionClass::ENABLE,      only : ['enable', 'bulkEnable']),
        ]);
    }

    public function __construct(Content $model, ContentService $crudService)
    {
        if(app()->runningInConsole()) return;

        $this->type  = e(request('type'));

        if(! Content::typeExists($this->type)) {
            abort(404);
        }

        $this->model            = $model;
        $this->crudService      = $crudService;
        $this->routeParameters  = ['type' => $this->type];

        app('adminHelper')->addBreadcrumbs(trans_choice('cms::contents.content_categories.' . $this->type, 1), route($this->routePrefix . '.index', ['type' => $this->type]));

        static::$permissionClass = 'Modules\\Cms\\Enums\\permissions\\' . Str::studly(request('type')) . 'Permissions';

        $this->data['type'] = $this->type;

        parent::__construct();
    }

    public function datatable(Request $request)
    {
        $request->merge(['type' => $this->type]);

        return $this->model->getDataTable($request->all(), static::$permissionClass);
    }

    public function getModelForAjax(Request $request)
    {
        $this->data['model'] = $this->model->where('type', $request->type);

        if ($request->has('q')) {
            $term = trim($request->q);

            $this->data['model'] = $this->data['model']->simpleSearch($term, $request->type);
        }

        return $this->data['model'];
    }

    public function canDelete($model)
    {
        if(! $model->can_be_deleted) {
            return sendFailInternalResponse('content_cannot_be_deleted');
        }

        return sendSuccessInternalResponse();
    }
}
