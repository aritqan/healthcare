<?php

namespace Modules\Base\Http\Controllers;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\Admin\Resources\PaginateResource;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class BaseCrudController extends BaseController implements HasMiddleware
{
    protected $module;

    protected $model;

    protected $crudService;

    protected static $permissionClass;

    protected static $hasPermission = true;

    protected $routePrefix;

    protected $routeParameters = [];

    protected $createRequest;

    protected $updateRequest;

    protected $hasSoftDelete = true;

    protected $hasDisabled = true;

    protected $hasBulkActions = true;

    public function __construct()
    {
        if(app()->runningInConsole()) return;

        $this->data['model_plural'] = Str::plural(Str::snake(class_basename($this->model)));

        parent::__construct();
    }

    /**
     * Get the middleware associated with the controller.
     *
     * @return array
     */
    public static function middleware(): array
    {
        if(app()->runningInConsole()) return [];

        $middlewares = ['active.admin'];

        if(static::$hasPermission) {
            $middlewares = array_merge($middlewares, [
                new Middleware('need.permissions:' . static::$permissionClass::READ,        only : ['index', 'datatable', 'ajaxList']),
                new Middleware('need.permissions:' . static::$permissionClass::VIEW,        only : ['view', 'viewAsModal']),
                new Middleware('need.permissions:' . static::$permissionClass::CREATE,      only : ['create', 'postCreate']),
                new Middleware('need.permissions:' . static::$permissionClass::UPDATE,      only : ['update', 'postUpdate']),
                new Middleware('need.permissions:' . static::$permissionClass::SOFT_DELETE, only : ['softDelete', 'bulkSoftDelete']),
                new Middleware('need.permissions:' . static::$permissionClass::HARD_DELETE, only : ['hardDelete', 'bulkHardDelete']),
                new Middleware('need.permissions:' . static::$permissionClass::RESTORE,     only : ['restore', 'bulkRestore']),
                new Middleware('need.permissions:' . static::$permissionClass::DISABLE,     only : ['disable', 'bulkDisable']),
                new Middleware('need.permissions:' . static::$permissionClass::ENABLE,      only : ['enable', 'bulkEnable']),
            ]);
        }

        return $middlewares;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        app('adminHelper')->addBreadcrumbs(trans('admin::dashboard.breadcrumbs.list'));

        if(static::$hasPermission) {
            $this->data['createPermission']     = static::$permissionClass::CREATE;
            if($this->hasSoftDelete) {
                $this->data['viewTrashPermission']  = static::$permissionClass::VIEW_TRASH;
            }
        }

        $this->data['bulkActionDropdown'] = [];

        if($this->hasBulkActions) {
            $bulkActionDropdown = ['hardDelete'];

            if($this->hasSoftDelete) {
                $bulkActionDropdown = array_merge($bulkActionDropdown, ['softDelete', 'restore']);
            }

            if($this->hasDisabled) {
                $bulkActionDropdown = array_merge($bulkActionDropdown, ['disable', 'enable']);
            }

            $this->data['bulkActionDropdown'] = app('bulkActionDropdown')
            ->of(static::$permissionClass::PERMISSION_NAMESPACE)
            ->routePrefix($this->routePrefix)
            ->setRouteParameters($this->routeParameters)
            ->executeActions($bulkActionDropdown);
        }

        return view($this->module . '::' . $this->model::VIEW_PATH . '.index', $this->data);
    }

    /**
     * Send a listing of the resource to ajax.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function datatable(Request $request)
    {
        return $this->model->getDataTable($request->all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        app('adminHelper')->addBreadcrumbs(trans('admin::dashboard.breadcrumbs.add_new'));

        return view($this->module . '::' . $this->model::VIEW_PATH . '.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postCreate()
    {
        app($this->createRequest);

        try
        {
            $this->crudService->createModel(app($this->createRequest)->validated());
        }
        catch(Exception $e)
        {
            return sendExceptionResponse($e);
        }

        return sendSuccessResponse(route($this->routePrefix . '.index', $this->routeParameters));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        app('adminHelper')->addBreadcrumbs(trans('admin::dashboard.breadcrumbs.edit'));

        $this->data['model'] = $this->model->getModel(id:$request->model, withTrashed: $this->hasSoftDelete, withDisabled: $this->hasDisabled);

        return view($this->module . '::' . $this->model::VIEW_PATH . '.update', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postUpdate(Request $request)
    {
        app($this->updateRequest);

        $this->data['model'] = $this->model->getModel(id: $request->model, withTrashed: $this->hasSoftDelete, withDisabled: $this->hasDisabled);

        try
        {
            $this->crudService->updateModel($this->data['model'], app($this->updateRequest)->validated());
        }
        catch(Exception $e)
        {
            return sendExceptionResponse($e);
        }

        return sendSuccessResponse(route($this->routePrefix . '.index', $this->routeParameters));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function view(Request $request)
    {
        app('adminHelper')->addBreadcrumbs(trans('admin::dashboard.breadcrumbs.view'));

        $this->data['model'] = $this->model->getModel(id:$request->model, withTrashed: $this->hasSoftDelete, withDisabled: $this->hasDisabled);

        return view($this->module . '::' . $this->model::VIEW_PATH . '.view', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function viewAsModal(Request $request)
    {
        $this->data['model'] = $this->model->getModel(id:$request->model, withTrashed: $this->hasSoftDelete, withDisabled: $this->hasDisabled);

        $this->data['view'] = view($this->module . '::' . $this->model::VIEW_PATH . '.view', $this->data)->render();

        return response()->json($this->data);
    }

    /**
     * Check if the model can be deleted or not.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return array
     */
    public function canDelete($model)
    {
        return sendSuccessInternalResponse();
    }

    /**
     * Remove the specified resource from storage as a soft delete.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function softDelete(Request $request)
    {
        $this->data['model'] = $this->model->getModel(id: $request->model, withTrashed: false, withDisabled: $this->hasDisabled);

        try
        {
            $result = $this->canDelete($this->data['model']);

            if(! $result['success']) {
                return sendFailResponse(customMessage: $result['message']);
            }

            $this->data['model']->delete();
        }
        catch(Exception $e)
        {
            return sendExceptionResponse($e);
        }

        return sendSuccessResponse();
    }

    /**
     * Exceute this function to perform a actions before the model is hard deleted.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function beforeHardDelete($model)
    {
        // Check if the model has translations and delete them
        if(!empty($model->translations)) {
            $model->translations()->each(function($translation){
                $translation->delete();
            });
        }
    }

    /**
     * Remove the specified resource from storage as a hard delete.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function hardDelete(Request $request)
    {
        $this->data['model'] = $this->model->getModel(id: $request->model, withTrashed: $this->hasSoftDelete, withDisabled: $this->hasDisabled);

        try
        {
            $result = $this->canDelete($this->data['model']);

            if(! $result['success']) {
                return sendFailResponse(customMessage: $result['message']);
            }

            $this->beforeHardDelete($this->data['model']);
            $this->data['model']->forceDelete();
        }
        catch(Exception $e)
        {
            return sendExceptionResponse($e);
        }

        return sendSuccessResponse();
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request)
    {
        $this->data['model'] = $this->model->onlyTrashed();

        if($this->hasDisabled) {
            $this->data['model'] = $this->data['model']->withDisabled();
        }

        $this->data['model'] = $this->data['model']->findOrFail($request->model);

        try
        {
            $this->data['model']->restore();
        }
        catch(Exception $e)
        {
            return sendExceptionResponse($e);
        }

        return sendSuccessResponse();
    }

    /**
     * Disable the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function disable(Request $request)
    {
        $this->data['model'] = $this->model->getModel(id: $request->model, withTrashed: $this->hasSoftDelete, withDisabled: false);

        try
        {
            $this->data['model']->disable();
        }
        catch(Exception $e)
        {
            return sendExceptionResponse($e);
        }

        return sendSuccessResponse();
    }

    /**
     * Enable the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function enable(Request $request)
    {
        $this->data['model'] = $this->model->onlyDisabled();

        if($this->hasSoftDelete) {
            $this->data['model'] = $this->data['model']->withTrashed();
        }

        $this->data['model'] = $this->data['model']->findOrFail($request->model);

        try
        {
            $this->data['model']->enable();
        }
        catch(Exception $e)
        {
            return sendExceptionResponse($e);
        }

        return sendSuccessResponse();
    }

    /**
     * Remove the specified resources from storage as a soft delete.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkSoftDelete(Request $request)
    {
        $this->data['models'] = $this->model;

        if($this->hasDisabled) {
            $this->data['models'] = $this->data['models']->withDisabled();
        }

        try
        {
            $models = $this->data['models']->whereKey($request->ids)->get();

            // Check if all models can be deleted
            $undeletableModel = $models->first(function ($model) {
                $result = $this->canDelete($model);

                return ! $result['success'];
            });

            if ($undeletableModel) {
                return sendFailResponse(customMessage: $this->canDelete($undeletableModel)['message']);
            }

            // Perform the deletion
            $models->each->delete();
        }
        catch(Exception $e)
        {
            return sendExceptionResponse($e);
        }

        return sendSuccessResponse();
    }

    /**
     * Remove the specified resources from storage as a hard delete.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkHardDelete(Request $request)
    {
        try
        {
            $models = $this->model->whereKey($request->ids)->get();

            // Check if all models can be deleted
            $undeletableModel = $models->first(function ($model) {
                $result = $this->canDelete($model);

                return ! $result['success'];
            });

            if ($undeletableModel) {
                return sendFailResponse(customMessage: $this->canDelete($undeletableModel)['message']);
            }

            $this->model->whereKey($request->ids)->each(function($model){
                $this->beforeHardDelete($model);
                $model->forceDelete();
            });
        }
        catch(Exception $e)
        {
            return sendExceptionResponse($e);
        }

        return sendSuccessResponse();
    }

    /**
     * Restore the specified resources from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkRestore(Request $request)
    {
        $this->data['models'] = $this->model->onlyTrashed();

        if($this->hasDisabled) {
            $this->data['models'] = $this->data['models']->withDisabled();
        }

        try
        {
            $this->data['models']->whereKey($request->ids)->each(function($model){
                $model->restore();
            });
        }
        catch(Exception $e)
        {
            return sendExceptionResponse($e);
        }

        return sendSuccessResponse();
    }

    /**
     * Disable the specified resources from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkDisable(Request $request)
    {
        $this->data['models'] = $this->model;

        if($this->hasSoftDelete) {
            $this->data['models'] = $this->data['models']->withTrashed();
        }

        try
        {
           $this->data['models']->whereKey($request->ids)->each(function($model){
                $model->disable();
            });
        }
        catch(Exception $e)
        {
            return sendExceptionResponse($e);
        }

        return sendSuccessResponse();
    }

    /**
     * Enable the specified resources from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkEnable(Request $request)
    {
        $this->data['models'] = $this->model->onlyDisabled();

        if($this->hasSoftDelete) {
            $this->data['models'] = $this->data['models']->withTrashed();
        }

        try
        {
            $this->data['models']->whereKey($request->ids)->each(function($model){
                $model->enable();
            });
        }
        catch(Exception $e)
        {
            return sendExceptionResponse($e);
        }

        return sendSuccessResponse();
    }

    /**
     * Get the model for ajax.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModelForAjax(Request $request)
    {
        $this->data['model'] = $this->model::query();

        if ($request->has('q')) {
            $term = trim($request->q);

            $this->data['model'] = $this->data['model']->simpleSearch($term);
        }

        return $this->data['model'];
    }

    /**
     * Send a listing of the resource to ajax.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ajaxList(Request $request)
    {
        $this->data['model'] = $this->getModelForAjax($request);

        return $this->formatDataForAjax($request, $this->data['model']);
    }

    /**
     * Format the data for ajax.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $model
     * @return \Illuminate\Http\Response
     */
    public function formatDataForAjax(Request $request, mixed $model)
    {
        $result = [];

        $this->data['page'] = $request->has('page') ? $request->page : 1;

        $this->data['paginate'] = $model->paginate(NUMBER_OF_RECORDS_PER_PAGE, ['*'], $this->data['model_plural'], $this->data['page']);

        foreach ($this->data['paginate'] as $modelItem) {
            $result['items'][] = $modelItem->formAjaxArray();
        }

        // If is_select is true, return json result list for select2 plugin
        if ($request->has('is_select') && $request->is_select == "true") {

            // Change items key to results key for select2 plugin
            $result['results'] = $result['items'] ?? [];
            unset($result['items']);

            $result['pagination']['more']   = $this->data['paginate']->hasMorePages();
            $result['total']                = $this->data['paginate']->total();

            return response()->json($result);
        }

        $result['pagination'] = new PaginateResource($this->data['paginate']);

        return app('response')
            ->success()
            ->withDefaultMessage('data_fetched_success')
            ->withData($result)
            ->send(isInternal: false, asAjax:true);
    }
}
