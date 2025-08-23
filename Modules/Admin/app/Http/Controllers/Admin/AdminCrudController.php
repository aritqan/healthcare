<?php

namespace Modules\Admin\Http\Controllers\Admin;

use Modules\Admin\Models\Admin;
use Modules\Permission\Models\Role;
use Modules\Admin\Http\Services\AdminCrudService;
use Modules\Admin\Enums\permissions\AdminPermissions;
use Modules\Admin\Http\Requests\CreateOrUpdateAdminRequest;
use Modules\Base\Http\Controllers\BaseCrudController;

class AdminCrudController extends BaseCrudController
{
    protected $module = 'admin';

    protected $model;

    protected $crudService;

    protected static $permissionClass = AdminPermissions::class;

    protected $routePrefix = 'admin.admins';

    protected $createRequest = CreateOrUpdateAdminRequest::class;

    protected $updateRequest = CreateOrUpdateAdminRequest::class;

    protected static $hasPermission = true;

    protected $hasSoftDelete = true;

    protected $hasDisabled = true;

    protected $hasBulkActions = true;

    public function __construct(Admin $model, AdminCrudService $crudService, protected Role $role)
    {
        app('adminHelper')->addBreadcrumbs(trans('admin::dashboard.aside_menu.user_management.admins'), route($this->routePrefix . '.index'));

        $this->model            = $model;
        $this->crudService      = $crudService;
        $this->data['roles']    = $this->role->get();

        parent::__construct();
    }
}
