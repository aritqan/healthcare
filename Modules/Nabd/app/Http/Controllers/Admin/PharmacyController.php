<?php

namespace Modules\Nabd\Http\Controllers\Admin;

use Modules\Base\Http\Controllers\BaseCrudController;
use Illuminate\Http\Request;
use Modules\Nabd\Models\Pharmacy;
use Modules\Nabd\Http\Services\PharmacyService;
use Modules\Nabd\Enums\permissions\PharmacyPermissions;
use Modules\Nabd\Http\Requests\PharmacyRequest;

class PharmacyController extends BaseCrudController
{
    protected $model;

    protected $crudService;

    protected $module           = 'nabd';

    protected $routePrefix      = 'nabd.pharmacies';

    protected $routeParameters  = [];

    protected $createRequest    = PharmacyRequest::class;

    protected $updateRequest    = PharmacyRequest::class;

    protected static $permissionClass  = PharmacyPermissions::class;

    protected static $hasPermission    = true;

    protected $hasSoftDelete    = true;

    protected $hasDisabled      = true;

    protected $hasBulkActions   = true;

    public function __construct(Pharmacy $model, PharmacyService $crudService)
    {
        app('adminHelper')->addBreadcrumbs(trans('admin::dashboard.aside_menu.pharmacy_management.pharmacies'), route($this->routePrefix . '.index'));

        $this->model        = $model;
        $this->crudService  = $crudService;

        parent::__construct();
    }

}
