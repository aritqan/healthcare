<?php

namespace Modules\Notification\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use Modules\Notification\Models\Notification;
use Modules\Base\Http\Controllers\BaseCrudController;
use Modules\Notification\Enums\NotificationChannels;
use Modules\Notification\Http\Requests\NotificationRequest;
use Modules\Notification\Http\Services\NotificationService;
use Modules\Notification\Enums\permissions\NotificationPermissions;
use Modules\Notification\Http\Requests\UpdateNotificationChannelStatusRequest;

class NotificationController extends BaseCrudController
{
    protected $model;

    protected $crudService;

    protected $module           = 'notification';

    protected $routePrefix      = 'notification.notifications';

    protected $routeParameters  = [];

    protected $createRequest    = NotificationRequest::class;

    protected $updateRequest    = NotificationRequest::class;

    protected static $permissionClass  = NotificationPermissions::class;

    protected static $hasPermission    = true;

    protected $hasSoftDelete    = false;

    protected $hasDisabled      = false;

    protected $hasBulkActions   = false;

    public function __construct(Notification $model, NotificationService $crudService)
    {
        app('adminHelper')->addBreadcrumbs(trans('admin::dashboard.aside_menu.notification_management.notifications'), route($this->routePrefix . '.index'));

        $this->model        = $model;
        $this->crudService  = $crudService;

        parent::__construct();
    }

    public function postCreate()
    {
        app($this->createRequest);

        $validateData       = app($this->createRequest)->validated();
        $fcmChannels        = [NotificationChannels::FCM_MOBILE, NotificationChannels::FCM_WEB];
        $hasFcmChannel      = count(array_intersect($fcmChannels, $validateData['channels'])) > 0;

        try
        {
            if(!isset($validateData['users_id']))
            {
                if($hasFcmChannel)  // To send to all users By Topic
                {
                    $this->crudService->sendToAll($validateData);
                }
                else // To send to all users Without Topic
                {   // If we have very large number of users we can use job for this process
                    $allUsersIds = $this->crudService->getAllUsersIds($validateData['group']);

                    $this->crudService->sendToUsers($allUsersIds, $validateData);
                }
            }
            else
            {
                $this->crudService->sendToUsers($validateData['users_id'], $validateData);
            }
            dd('Success');
        }
        catch(Exception $e)
        {
            return sendExceptionResponse($e);
        }

        return sendSuccessResponse(route($this->routePrefix . '.index', $this->routeParameters));
    }

    /**
     * User web notifications
     *
     * @param Request $request
     */
    public function adminWebNotifications(Request $request)
    {
        $this->data['model'] = $this->crudService->adminWebNotifications(app('admin'));

        return parent::formatDataForAjax($request, $this->data['model']);
    }

    /**
     * Update notification channel status
     *
     * @param Request $request
     */
    public function updateNotificationChannelStatus(UpdateNotificationChannelStatusRequest $request)
    {
        try
        {
            $this->crudService->updateNotificationChannelStatus(app('admin'), $request->validated());

            return sendSuccessResponse();
        }
        catch(Exception $e)
        {
            return sendExceptionResponse($e);
        }
    }
}
