<?php

namespace Modules\Log\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Auth\Models\User;
use Modules\Log\Models\Audit;
use Illuminate\Contracts\View\View;
use Modules\Admin\Models\Admin;
use Modules\Cms\Models\Content;
use Modules\Crm\Models\Contactus;
use Modules\Crm\Models\Subscribe;
use Modules\Zms\Models\Country;
use Modules\Base\Http\Controllers\BaseController;
use Modules\Auth\Enums\permissions\UserPermissions;
use Modules\Cms\Enums\permissions\BlogsPermissions;
use Modules\Cms\Enums\permissions\PagesPermissions;
use Modules\Admin\Enums\permissions\AdminPermissions;
use Modules\Cms\Enums\permissions\SlidersPermissions;
use Modules\Notification\Models\NotificationTemplate;
use Modules\Crm\Enums\permissions\ContactusPermissions;
use Modules\Crm\Enums\permissions\SubscribePermissions;
use Modules\Zms\Enums\permissions\CountryPermissions;
use Modules\Notification\Enums\permissions\NotificationTemplatePermissions;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AuditController extends BaseController implements HasMiddleware
{
    protected $model;

    protected $crudService;

    protected $routePrefix = 'log.activity_log';

    protected $modelType;

    protected $modelName;

    public function __construct(Audit $model)
    {
        $this->model     = $model;
        $this->modelType = strtoupper(str_replace('-', '_', request()->type));

        $this->handleModelName();

        app('adminHelper')->addBreadcrumbs(trans('admin::dashboard.aside_menu.activity_log_management.activity_logs'));

        parent::__construct();
    }

    public static function middleware(): array
    {
        return [
            'active.admin',
            new Middleware('need.permissions:SHOW_LOG_' . strtoupper(str_replace('-', '_', request()->type)), only : ['index', 'datatable']),
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return Illuminate\Contracts\View\View | Illuminate\Contracts\View\Factory
     */
    public function index() : View
    {
        app('adminHelper')->addBreadcrumbs(trans('admin::dashboard.breadcrumbs.list'));

        $this->data['modelName'] = $this->modelName;
        $this->data['modelType'] = $this->modelType;
        $this->data['modelId']   = request()->model;

        return view('log::audits.index', $this->data);
    }

    /**
     * Get data for datatable
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function datatable() : JsonResponse
    {
        $data = [
            'type' => $this->hanldeModelNameSpace(),
            'id'   => request()->model,
        ];

        return $this->model->getDataTable($data);
    }

    /**
     * Display the specified resource.
     *
     * @param Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function viewAsModal(Request $request) : JsonResponse
    {
        $this->data['model'] = $this->model->getModel($request->model);

        $this->data['view'] = view('log::audits.view', $this->data)->render();

        return response()->json($this->data);
    }

    /**
     * Handle model name
     *
     * @return void
     */
    protected function handleModelName() : void
    {
        switch ($this->modelType) {
            case AdminPermissions::PERMISSION_NAMESPACE :
                $this->modelName = trans('admin::dashboard.aside_menu.user_management.admins');
                app('adminHelper')->addBreadcrumbs($this->modelName, route('admin.admins.index'));
                break;
            case UserPermissions::PERMISSION_NAMESPACE :
                $this->modelName = trans('admin::dashboard.aside_menu.user_management.users');
                app('adminHelper')->addBreadcrumbs($this->modelName, route('auth.users.index'));
                break;
            case SlidersPermissions::PERMISSION_NAMESPACE :
                $this->modelName = trans_choice('cms::contents.content_categories.sliders', 1);
                app('adminHelper')->addBreadcrumbs($this->modelName, route('cms.contents.index', ['type' => 'sliders']));
                break;
            case BlogsPermissions::PERMISSION_NAMESPACE :
                $this->modelName = trans_choice('cms::contents.content_categories.blogs', 1);
                app('adminHelper')->addBreadcrumbs($this->modelName, route('cms.contents.index', ['type' => 'blogs']));
                break;
            case PagesPermissions::PERMISSION_NAMESPACE :
                $this->modelName = trans_choice('cms::contents.content_categories.pages', 1);
                app('adminHelper')->addBreadcrumbs($this->modelName, route('cms.contents.index', ['type' => 'pages']));
                break;
            case CountryPermissions::PERMISSION_NAMESPACE :
                $this->modelName = trans('admin::cruds.countries.title');
                app('adminHelper')->addBreadcrumbs($this->modelName, route('zms.countries.index'));
                break;
            case NotificationTemplatePermissions::PERMISSION_NAMESPACE :
                $this->modelName = trans('admin::cruds.notification_templates.title');
                app('adminHelper')->addBreadcrumbs($this->modelName, route('notification.notification_templates.index'));
                break;
            case ContactusPermissions::PERMISSION_NAMESPACE :
                $this->modelName = trans('admin::cruds.contactuses.title');
                app('adminHelper')->addBreadcrumbs($this->modelName, route('crm.contactuses.index'));
                break;
            case SubscribePermissions::PERMISSION_NAMESPACE :
                $this->modelName = trans('admin::cruds.subscribes.title');
                app('adminHelper')->addBreadcrumbs($this->modelName, route('crm.subscribes.index'));
                break;
        }

        return;
    }

    /**
     * Handle model namespace
     *
     * @return mixed
     */
    protected function hanldeModelNameSpace() : mixed
    {
        switch ($this->modelType) {
            case AdminPermissions::PERMISSION_NAMESPACE :
                return Admin::class;
            case UserPermissions::PERMISSION_NAMESPACE :
                return User::class;
            case SlidersPermissions::PERMISSION_NAMESPACE :
            case BlogsPermissions::PERMISSION_NAMESPACE :
            case PagesPermissions::PERMISSION_NAMESPACE :
                return Content::class;
            case CountryPermissions::PERMISSION_NAMESPACE :
                return Country::class;
            case NotificationTemplatePermissions::PERMISSION_NAMESPACE :
                return NotificationTemplate::class;
            case ContactusPermissions::PERMISSION_NAMESPACE :
                return Contactus::class;
            case SubscribePermissions::PERMISSION_NAMESPACE :
                return Subscribe::class;
        }

        return null;
    }
}
