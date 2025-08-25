<?php

namespace Modules\Nabd\Http\Controllers\Admin;

use Modules\Base\Http\Controllers\BaseCrudController;
use Illuminate\Http\Request;
use Modules\Nabd\Models\MedicalSpecialty;
use Modules\Nabd\Http\Services\MedicalSpecialtyService;
use Modules\Nabd\Enums\permissions\MedicalSpecialtyPermissions;
use Modules\Nabd\Http\Requests\MedicalSpecialtyRequest;

class MedicalSpecialtyController extends BaseCrudController
{
    protected $model;

    protected $crudService;

    protected $module           = 'nabd';

    protected $routePrefix      = 'nabd.medical_specialties';

    protected $routeParameters  = [];

    protected $createRequest    = MedicalSpecialtyRequest::class;

    protected $updateRequest    = MedicalSpecialtyRequest::class;

    protected static $permissionClass  = MedicalSpecialtyPermissions::class;

    protected static $hasPermission    = true;

    protected $hasSoftDelete    = true;

    protected $hasDisabled      = false;

    protected $hasBulkActions   = true;

    public function __construct(MedicalSpecialty $model, MedicalSpecialtyService $crudService)
    {
        app('adminHelper')->addBreadcrumbs(trans('admin::dashboard.aside_menu.medical_specialty_management.medical_specialties'), route($this->routePrefix . '.index'));

        $this->model        = $model;
        $this->crudService  = $crudService;

        parent::__construct();
    }

}
