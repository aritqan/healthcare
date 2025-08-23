<?php

namespace Modules\Zms\Http\Controllers\Admin;

use Modules\Zms\Models\Country;
use Modules\Base\Http\Controllers\BaseCrudController;
use Modules\Zms\Http\Requests\UpdateCountry;
use Modules\Zms\Http\Services\CountryService;
use Modules\Zms\Enums\permissions\CountryPermissions;

class CountryController extends BaseCrudController
{
    protected $module = 'zms';

    protected $model;

    protected $crudService;

    protected static $permissionClass = CountryPermissions::class;

    protected $routePrefix = 'zms.countries';

    protected $updateRequest = UpdateCountry::class;

    protected static $hasPermission = true;

    protected $hasSoftDelete = true;

    protected $hasDisabled = true;

    protected $hasBulkActions = true;

    public function __construct(Country $model, CountryService $crudService)
    {
        app('adminHelper')->addBreadcrumbs(trans('admin::dashboard.aside_menu.country_management.countries'), route($this->routePrefix . '.index'));

        $this->model            = $model;
        $this->crudService      = $crudService;

        parent::__construct();
    }
}
