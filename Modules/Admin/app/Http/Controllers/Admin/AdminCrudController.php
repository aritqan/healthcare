<?php

namespace Modules\Admin\Http\Controllers\Admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\Admin\Models\Admin;
use Modules\Permission\Models\Role;
use Illuminate\Routing\Controllers\Middleware;
use Modules\Permission\Enums\SystemDefaultRoles;
use Modules\Admin\Http\Services\AdminCrudService;
use Modules\Admin\Enums\permissions\AdminPermissions;
use Modules\Base\Http\Controllers\BaseCrudController;
use Modules\Admin\Enums\permissions\ClinicPermissions;
use Modules\Admin\Enums\permissions\PharmacyPermissions;
use Modules\Admin\Http\Requests\CreateOrUpdateAdminRequest;

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

    protected $role   = null;

    public static function middleware(): array
    {
        if(app()->runningInConsole()) return [];

        $permissionClass = self::permissionMapping(e(request('role')));

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


    public function __construct(Admin $model, AdminCrudService $crudService, protected Role $roles)
    {
        if(app()->runningInConsole()) return;

        $this->role  = e(request('role'));

        app('adminHelper')->addBreadcrumbs(trans('admin::dashboard.aside_menu.user_management.' . Str::plural(strtolower($this->role))), route($this->routePrefix . '.index', ['role' => $this->role]));

        $this->model            = $model;
        $this->crudService      = $crudService;
        $this->routeParameters  = ['role' => $this->role];
        $this->data['roles']    = $this->roles->where('name', $this->role)->get();
        $this->data['roleName'] = Str::plural(strtolower($this->role));

        parent::__construct();
    }

    protected static function permissionMapping($role)
    {
        switch (strtoupper($role)) {
            case SystemDefaultRoles::SYSTEM_ADMIN_ROLE:
                return AdminPermissions::class;
            case SystemDefaultRoles::CLINIC:
                return ClinicPermissions::class;
            case SystemDefaultRoles::PHARMACY:
                return PharmacyPermissions::class;
            default:
                return 'Modules\\Admin\\Enums\\permissions\\' . Str::studly($role) . 'Permissions';
        }
    }

    public function datatable(Request $request)
    {
        $request->merge(['role' => $this->role]);

        return $this->model->getDataTable($request->all());
    }
}
