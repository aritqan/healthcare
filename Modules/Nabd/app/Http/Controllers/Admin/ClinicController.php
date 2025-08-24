<?php

namespace Modules\Nabd\Http\Controllers\Admin;

use Modules\Base\Http\Controllers\BaseCrudController;
use Modules\Nabd\Models\Clinic;
use Modules\Nabd\Http\Services\ClinicService;
use Modules\Nabd\Enums\permissions\ClinicPermissions;
use Modules\Nabd\Http\Requests\ClinicRequest;

class ClinicController extends BaseCrudController
{
    protected $model;

    protected $crudService;

    protected $module           = 'nabd';

    protected $routePrefix      = 'nabd.clinics';

    protected $routeParameters  = [];

    protected $createRequest    = ClinicRequest::class;

    protected $updateRequest    = ClinicRequest::class;

    protected static $permissionClass  = ClinicPermissions::class;

    protected static $hasPermission    = true;

    protected $hasSoftDelete    = true;

    protected $hasDisabled      = true;

    protected $hasBulkActions   = true;

    public function __construct(Clinic $model, ClinicService $crudService)
    {
        app('adminHelper')->addBreadcrumbs(trans('admin::dashboard.aside_menu.clinic_management.clinics'), route($this->routePrefix . '.index'));

        $this->model        = $model;
        $this->crudService  = $crudService;

        parent::__construct();
    }

}
