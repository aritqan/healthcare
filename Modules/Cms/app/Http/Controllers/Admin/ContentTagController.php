<?php

namespace Modules\Cms\Http\Controllers\Admin;

use Modules\Base\Http\Controllers\BaseCrudController;
use Modules\Cms\Models\ContentTag;
use Modules\Cms\Http\Services\ContentTagService;
use Modules\Cms\Enums\permissions\ContentTagPermissions;
use Modules\Cms\Http\Requests\ContentTagRequest;

class ContentTagController extends BaseCrudController
{
    protected $model;

    protected $crudService;

    protected $module           = 'cms';

    protected $routePrefix      = 'cms.content_tags';

    protected $routeParameters  = [];

    protected $createRequest    = ContentTagRequest::class;

    protected $updateRequest    = ContentTagRequest::class;

    protected static $permissionClass  = ContentTagPermissions::class;

    protected static $hasPermission    = true;

    protected $hasSoftDelete    = true;

    protected $hasDisabled      = true;

    protected $hasBulkActions   = true;

    public function __construct(ContentTag $model, ContentTagService $crudService)
    {
        app('adminHelper')->addBreadcrumbs(trans('admin::dashboard.aside_menu.content_tag_management.content_tags'), route($this->routePrefix . '.index'));

        $this->model        = $model;
        $this->crudService  = $crudService;

        parent::__construct();
    }

}
