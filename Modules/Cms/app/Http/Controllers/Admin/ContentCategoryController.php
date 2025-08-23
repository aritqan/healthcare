<?php

namespace Modules\Cms\Http\Controllers\Admin;

use Modules\Base\Http\Controllers\BaseCrudController;
use Modules\Cms\Models\ContentCategory;
use Modules\Cms\Http\Services\ContentCategoryService;
use Modules\Cms\Enums\permissions\ContentCategoryPermissions;
use Modules\Cms\Http\Requests\ContentCategoryRequest;

class ContentCategoryController extends BaseCrudController
{
    protected $model;

    protected $crudService;

    protected $module           = 'cms';

    protected $routePrefix      = 'cms.content_categories';

    protected $createRequest    = ContentCategoryRequest::class;

    protected $updateRequest    = ContentCategoryRequest::class;

    protected static $permissionClass  = ContentCategoryPermissions::class;

    protected static $hasPermission    = true;

    protected $hasSoftDelete    = true;

    protected $hasDisabled      = true;

    protected $hasBulkActions   = true;

    public function __construct(ContentCategory $model, ContentCategoryService $crudService)
    {
        app('adminHelper')->addBreadcrumbs(trans('admin::dashboard.aside_menu.content_category_management.content_categories'), route($this->routePrefix . '.index'));

        $this->model        = $model;
        $this->crudService  = $crudService;

        parent::__construct();
    }

    public function canDelete($model)
    {
        if(! $model->can_be_deleted) {
            return sendFailInternalResponse('content_cannot_be_deleted');
        }

        return sendSuccessInternalResponse();
    }
}
