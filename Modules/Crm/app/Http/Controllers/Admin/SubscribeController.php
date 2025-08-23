<?php

namespace Modules\Crm\Http\Controllers\Admin;

use Modules\Base\Http\Controllers\BaseCrudController;
use Modules\Crm\Models\Subscribe;
use Modules\Crm\Http\Services\SubscribeService;
use Modules\Crm\Enums\permissions\SubscribePermissions;
use Modules\Crm\Http\Requests\Admin\SubscribeRequest;

class SubscribeController extends BaseCrudController
{
    protected $model;

    protected $crudService;

    protected $module           = 'crm';

    protected $routePrefix      = 'crm.subscribes';

    protected $routeParameters  = [];

    protected $createRequest    = SubscribeRequest::class;

    protected $updateRequest    = SubscribeRequest::class;

    protected static $permissionClass  = SubscribePermissions::class;

    protected static $hasPermission    = true;

    protected $hasSoftDelete    = true;

    protected $hasDisabled      = false;

    protected $hasBulkActions   = true;

    public function __construct(Subscribe $model, SubscribeService $crudService)
    {
        app('adminHelper')->addBreadcrumbs(trans('admin::dashboard.aside_menu.subscribe_management.subscribes'), route($this->routePrefix . '.index'));

        $this->model        = $model;
        $this->crudService  = $crudService;

        parent::__construct();
    }

}
