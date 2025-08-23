<?php

namespace Modules\Auth\Http\Controllers\admin;

use Illuminate\Http\Request;
use Modules\Auth\Models\User;
use Modules\Auth\Http\Requests\UserCrudRequest;
use Modules\Auth\Http\Services\UserCrudService;
use Modules\Auth\Enums\permissions\UserPermissions;
use Modules\Base\Http\Controllers\BaseCrudController;

class UserCrudController extends BaseCrudController
{
    protected $module = 'auth';

    protected $model;

    protected $crudService;

    protected static $permissionClass = UserPermissions::class;

    protected $routePrefix = 'auth.users';

    protected $createRequest = UserCrudRequest::class;

    protected $updateRequest = UserCrudRequest::class;

    protected static $hasPermission = true;

    protected $hasSoftDelete = true;

    protected $hasDisabled = false;

    protected $hasBulkActions = false;

    public function __construct(User $model, UserCrudService $crudService)
    {
        app('adminHelper')->addBreadcrumbs(trans('admin::dashboard.aside_menu.user_management.users'), route($this->routePrefix . '.index'));

        $this->model            = $model;
        $this->crudService      = $crudService;

        parent::__construct();
    }
}
