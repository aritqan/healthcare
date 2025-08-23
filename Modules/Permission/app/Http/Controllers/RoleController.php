<?php

namespace Modules\Permission\Http\Controllers;

use Modules\Permission\Http\Requests\RoleRequest;
use Modules\Permission\Enums\permissions\RolePermissions;
use Modules\Base\Http\Controllers\BaseCrudController;
use Modules\Permission\Models\AbilityGroup;
use Modules\Permission\Http\Services\RoleService;
use Modules\Permission\Models\Role;
class RoleController extends BaseCrudController
{
    protected $module = 'permission';

    protected $model;

    protected $crudService;

    protected static $permissionClass = RolePermissions::class;

    protected $routePrefix = 'permission.roles';

    protected $createRequest = RoleRequest::class;

    protected $updateRequest = RoleRequest::class;

    protected static $hasPermission = true;

    protected $hasSoftDelete = false;

    protected $hasDisabled = false;

    protected $hasBulkActions = false;

    public function __construct(Role $model, RoleService $crudService, private AbilityGroup $abilityModel)
    {
        app('adminHelper')->addBreadcrumbs(trans('admin::dashboard.aside_menu.user_management.roles'), route($this->routePrefix . '.index'));

        $this->model            = $model;
        $this->crudService      = $crudService;

        $this->data['abilityGroups'] = $this->abilityModel->getModel();

        parent::__construct();
    }
}
