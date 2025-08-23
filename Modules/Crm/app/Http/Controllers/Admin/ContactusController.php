<?php

namespace Modules\Crm\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use Modules\Crm\Models\Contactus;
use Modules\Crm\Enums\ContactusStatuses;
use Modules\Crm\Http\Services\ContactusService;
use Modules\Base\Http\Controllers\BaseCrudController;
use Modules\Crm\Http\Requests\Admin\ContactusRequest;
use Modules\Crm\Enums\permissions\ContactusPermissions;

class ContactusController extends BaseCrudController
{
    protected $model;

    protected $crudService;

    protected $module           = 'crm';

    protected $routePrefix      = 'crm.contactuses';

    protected $routeParameters  = [];

    protected $createRequest    = ContactusRequest::class;

    protected $updateRequest    = ContactusRequest::class;

    protected static $permissionClass  = ContactusPermissions::class;

    protected static $hasPermission    = true;

    protected $hasSoftDelete    = true;

    protected $hasDisabled      = false;

    protected $hasBulkActions   = true;

    public function __construct(Contactus $model, ContactusService $crudService)
    {
        app('adminHelper')->addBreadcrumbs(trans('admin::dashboard.aside_menu.contactus_management.contactus'), route($this->routePrefix . '.index'));

        $this->model        = $model;
        $this->crudService  = $crudService;

        parent::__construct();
    }

    public function view(Request $request)
    {
        app('adminHelper')->addBreadcrumbs(trans('admin::dashboard.breadcrumbs.view'));

        $this->data['model'] = $this->model->getModel(id:$request->model, withTrashed: $this->hasSoftDelete, withDisabled: $this->hasDisabled);

        if($this->data['model']->status == ContactusStatuses::PENDING) {
            $this->data['model']->status = ContactusStatuses::SEEN;
            $this->data['model']->save();
        }

        return view($this->module . '::' . $this->model::VIEW_PATH . '.view', $this->data);
    }

    public function sendReply(ContactusRequest $request)
    {
        try
        {
            $this->data['model'] = $this->model->getModel(id:$request->model);

            $response = $this->crudService->sendReply($this->data['model'], $request->validated());

            if(! $response['success']) {
                return sendFailResponse(customMessage: $response['message']);
            }
        }
        catch(Exception $e)
        {
            return sendExceptionResponse($e);
        }

        return sendSuccessResponse(route($this->routePrefix . '.index', $this->routeParameters), customMessage:$response['message']);
    }
}
