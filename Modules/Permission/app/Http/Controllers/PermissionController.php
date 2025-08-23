<?php

namespace Modules\Permission\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Modules\Permission\Enums\permissions\AbilityPermissions;
use Modules\Base\Http\Controllers\BaseCrudController;
use Modules\Permission\Http\Requests\PermissionRequest;
use Modules\Permission\Http\Services\PermissionService;
use Modules\Permission\Models\AbilityGroup;

class PermissionController extends BaseCrudController
{
    protected $module = 'permission';

    protected $model;

    protected $crudService;

    protected static $permissionClass = AbilityPermissions::class;

    protected $routePrefix = 'permission.permissions';

    protected $createRequest = PermissionRequest::class;

    protected $updateRequest = PermissionRequest::class;

    protected static $hasPermission = true;

    protected $hasSoftDelete = false;

    protected $hasDisabled = false;

    protected $hasBulkActions = false;

    public function __construct(AbilityGroup $model, PermissionService $crudService)
    {
        app('adminHelper')->addBreadcrumbs(trans('admin::dashboard.aside_menu.user_management.permissions'), route($this->routePrefix . '.index'));

        $this->model            = $model;
        $this->crudService      = $crudService;

        parent::__construct();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->data['abilityGroups'] = $this->model->getModel();

        return parent::create();
    }

        /**
     * Store a newly created resource in storage.
     */
    public function postCreate()
    {
        app($this->createRequest);

        try
        {
            $this->crudService->createModel(app($this->createRequest)->all());
        }
        catch(Exception $e)
        {
            return sendExceptionResponse($e);
        }

        return sendSuccessResponse(route($this->routePrefix . '.index'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function update(Request $request)
    {
        app('adminHelper')->addBreadcrumbs(trans('admin::dashboard.breadcrumbs.edit'));

        $this->data['model'] = $this->model->getModel($request->model);

        foreach(CRUD_TYPES as $permission) {
            $this->data['newCrudTypes'][] = strtoupper($permission . '_' . $this->data['model']->code);
        }

        $this->data['allPermissions'] = array_merge(
            $this->data['model']->abilities->pluck('name')->toArray(),
            $this->data['newCrudTypes']
        );

        $this->data['allPermissions'] = array_values(array_unique($this->data['allPermissions']));

        return view($this->module . '::' . $this->model::VIEW_PATH . '.update', $this->data);
    }

        /**
     * Update the specified resource in storage.
     */
    public function postUpdate(Request $request)
    {
        app($this->updateRequest);

        $this->data['model'] = $this->model->getModel($request->model);

        try
        {
            $this->crudService->updateModel($request->all(), $this->data['model']);
        }
        catch (Exception $e)
        {
            return sendExceptionResponse($e);
        }

        return sendSuccessResponse(route($this->routePrefix .'.index'));
    }

}
