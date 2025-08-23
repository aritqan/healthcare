<?php

namespace Modules\Log\Http\Controllers\Admin;

use Modules\Base\Http\Controllers\BaseCrudController;
use Illuminate\Http\Request;
use Modules\Log\Models\ApiLog;
use Modules\Log\Http\Services\ApiLogService;
use Modules\Log\Enums\permissions\ApiLogPermissions;

class ApiLogController extends BaseCrudController
{
    protected $model;

    protected $crudService;

    protected $module           = 'log';

    protected $routePrefix      = 'log.api_logs';

    protected $routeParameters  = [];

    protected $createRequest    = Request::class;

    protected $updateRequest    = Request::class;

    protected static $permissionClass  = ApiLogPermissions::class;

    protected static $hasPermission    = true;

    protected $hasSoftDelete    = true;

    protected $hasDisabled      = false;

    protected $hasBulkActions   = true;

    public function __construct(ApiLog $model, ApiLogService $crudService)
    {
        app('adminHelper')->addBreadcrumbs(trans('admin::dashboard.aside_menu.api_log_management.api_logs'), route($this->routePrefix . '.index'));

        $this->model        = $model;
        $this->crudService  = $crudService;

        parent::__construct();
    }
}
