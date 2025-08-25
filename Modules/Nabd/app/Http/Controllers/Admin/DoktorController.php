<?php

namespace Modules\Nabd\Http\Controllers\Admin;

use Modules\Base\Http\Controllers\BaseCrudController;
use Illuminate\Http\Request;
use Modules\Nabd\Models\Doktor;
use Modules\Nabd\Http\Services\DoktorService;
use Modules\Nabd\Enums\permissions\DoktorPermissions;
use Modules\Nabd\Http\Requests\DoktorRequest;

class DoktorController extends BaseCrudController
{
    protected $model;

    protected $crudService;

    protected $module           = 'nabd';

    protected $routePrefix      = 'nabd.doktors';

    protected $routeParameters  = [];

    protected $createRequest    = DoktorRequest::class;

    protected $updateRequest    = DoktorRequest::class;

    protected static $permissionClass  = DoktorPermissions::class;

    protected static $hasPermission    = true;

    protected $hasSoftDelete    = true;

    protected $hasDisabled      = true;

    protected $hasBulkActions   = true;

    public function __construct(Doktor $model, DoktorService $crudService)
    {
        app('adminHelper')->addBreadcrumbs(trans('admin::dashboard.aside_menu.doktor_management.doktors'), route($this->routePrefix . '.index'));

        $this->model        = $model;
        $this->crudService  = $crudService;

        parent::__construct();
    }

}
