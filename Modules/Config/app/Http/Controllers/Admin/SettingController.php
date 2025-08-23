<?php

namespace Modules\Config\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use Modules\Config\Models\Setting;
use Modules\Config\Enums\SettingTypes;
use Modules\Config\Enums\SettingGroups;
use Modules\Config\Http\Services\SettingService;
use Modules\Base\Http\Controllers\BaseCrudController;
use Modules\Config\Http\Requests\CreateSettingRequest;
use Modules\Config\Http\Requests\UpdateSettingRequest;
use Modules\Config\Enums\permissions\SettingPermissions;

class SettingController extends BaseCrudController
{
    protected $module = 'config';

    protected $model;

    protected $crudService;

    protected static $permissionClass = SettingPermissions::class;

    protected $routePrefix = 'config.settings';

    protected $createRequest = CreateSettingRequest::class;

    protected $updateRequest = UpdateSettingRequest::class;

    protected static $hasPermission = true;

    protected $hasSoftDelete = false;

    protected $hasDisabled = false;

    protected $hasBulkActions = false;

    public function __construct(Setting $model, SettingService $crudService)
    {
        $this->model            = $model;
        $this->crudService      = $crudService;

        parent::__construct();
    }

    public function index()
    {
        $this->data['settings'] = $this->model->getAllSettings();
        $this->data['settings'] = $this->data['settings']->sortBy('order')->groupBy('group');

        return parent::index();
    }

    public function create()
    {
        $this->data['groups'] = SettingGroups::getGroups();
        $this->data['types']  = SettingTypes::all();

        return parent::create();
    }

    public function postUpdate(Request $request)
    {
        app($this->updateRequest);

        try
        {
            $this->crudService->updateSetting(app($this->updateRequest)->validated());
        }
        catch(Exception $e)
        {
            return sendExceptionResponse($e);
        }

        return sendSuccessResponse(route($this->routePrefix . '.index'));
    }
}
