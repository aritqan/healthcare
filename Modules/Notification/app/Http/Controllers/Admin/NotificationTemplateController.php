<?php

namespace Modules\Notification\Http\Controllers\Admin;

use Modules\Base\Http\Controllers\BaseCrudController;
use Modules\Notification\Models\NotificationTemplate;
use Modules\Notification\Http\Services\NotificationTemplateService;
use Modules\Notification\Enums\permissions\NotificationTemplatePermissions;
use Modules\Notification\Http\Requests\NotificationTemplateRequest;

class NotificationTemplateController extends BaseCrudController
{
    protected $model;

    protected $crudService;

    protected $module           = 'notification';

    protected $routePrefix      = 'notification.notification_templates';

    protected $routeParameters  = [];

    protected $createRequest    = NotificationTemplateRequest::class;

    protected $updateRequest    = NotificationTemplateRequest::class;

    protected static $permissionClass  = NotificationTemplatePermissions::class;

    protected static $hasPermission    = true;

    protected $hasSoftDelete    = true;

    protected $hasDisabled      = false;

    protected $hasBulkActions   = true;

    public function __construct(NotificationTemplate $model, NotificationTemplateService $crudService)
    {
        app('adminHelper')->addBreadcrumbs(trans('admin::dashboard.aside_menu.notification_template_management.notification_templates'), route($this->routePrefix . '.index'));

        $this->model        = $model;
        $this->crudService  = $crudService;

        parent::__construct();
    }

}
