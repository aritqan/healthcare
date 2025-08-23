<?php

namespace Modules\Zms\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use Modules\Zms\Models\State;
use Modules\Zms\Http\Requests\UpdateState;
use Modules\Zms\Http\Services\StateService;
use Modules\Base\Http\Controllers\BaseCrudController;
use Modules\Zms\Enums\permissions\CountryPermissions;

class StateController extends BaseCrudController
{
    protected $module = 'zms';

    protected $model;

    protected $crudService;

    protected static $permissionClass = CountryPermissions::class;

    protected $routePrefix = 'zms.countries';

    protected $updateRequest = UpdateState::class;

    protected static $hasPermission = true;

    protected $hasSoftDelete = false;

    protected $hasDisabled = false;

    protected $hasBulkActions = false;

    public function __construct(State $model, StateService $crudService)
    {
        $this->model            = $model;
        $this->crudService      = $crudService;

        parent::__construct();
    }

    /**
     * Send a listing of the resource to ajax.
     */
    public function datatable(Request $request)
    {
        $request->merge(['country_id' => $request->country_id]);
        return parent::datatable($request);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function update(Request $request)
    {
        $this->data['model']         = $this->model->getModel($request->model);

        app('adminHelper')->addBreadcrumbs($this->data['model']->country?->name, route($this->routePrefix . '.update', $this->data['model']->country_id));

        app('adminHelper')->addBreadcrumbs(trans('admin::cruds.states.edit'));

        return view($this->module . '::' . $this->model::VIEW_PATH . '.update', $this->data);
    }


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


        return sendSuccessResponse(route($this->routePrefix . '.update', $this->data['model']->country_id));
    }

}
