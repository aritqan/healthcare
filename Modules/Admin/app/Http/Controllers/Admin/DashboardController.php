<?php

namespace Modules\Admin\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Auth\Models\User;
use Modules\Admin\Models\Admin;
use Modules\Cms\Models\Content;
use Modules\Cms\Enums\contents\BaseContentTypes;
use Modules\Base\Http\Controllers\BaseController;
use Modules\Cms\Models\ContentCategory;
use Modules\Crm\Models\Contactus;
use Modules\Crm\Models\Subscribe;
use Modules\Permission\Models\Role;

class DashboardController extends BaseController
{
    public function dashboard(Request $request)
    {
        if($request->isMethod('post')) {
            $request->validate([
                'date_range' => 'required|string',
            ]);
        }

        $this->data['fromDate'] = null;
        $this->data['toDate']   = null;

        if($request->has('date_range')) {
            $dateRange                  = explode(' - ', $request->date_range);
            $this->data['fromDate']     = Carbon::parse($dateRange[0])->format('Y-m-d');
            $this->data['toDate']       = Carbon::parse($dateRange[1])->format('Y-m-d');
        }

        $this->getUsersData(); $this->getContentData(); $this->getCrmData();

        if($request->ajax()) return sendSuccessInternalResponse(data: $this->data);

        return view('admin::dashboard', $this->data);
    }

    private function getUsersData()
    {
        $this->data['statistics']['users'][] = dashboardSetItem(
            key         : 'role',
            label       : trans('admin::dashboard.aside_menu.user_management.roles'),
            modelClass  : Role::class,
            fromDate    : $this->data['fromDate'],
            toDate      : $this->data['toDate']
        );

        $this->data['statistics']['users'][] = dashboardSetItem(
            key         : 'user',
            label       : trans('admin::dashboard.aside_menu.user_management.users'),
            modelClass  : User::class,
            fromDate    : $this->data['fromDate'],
            toDate      : $this->data['toDate']
        );

        $this->data['statistics']['users'][] = dashboardSetItem(
            key         : 'admin',
            label       : trans('admin::dashboard.aside_menu.user_management.admins'),
            modelClass  : Admin::class,
            fromDate    : $this->data['fromDate'],
            toDate      : $this->data['toDate']
        );
    }

    private function getContentData()
    {
        $this->data['statistics']['contents'][] = dashboardSetItem(
            key         : 'content_categories',
            label       : trans('admin::dashboard.aside_menu.content_category_management.content_categories'),
            modelClass  : ContentCategory::class,
            fromDate    : $this->data['fromDate'],
            toDate      : $this->data['toDate'],
            hideIfEmpty : true
        );

        foreach (BaseContentTypes::all() as $type) {
            $this->data['statistics']['contents'][] = dashboardSetItem(
                key         : $type,
                label       : trans('admin::cruds.' . $type . '.title'),
                route       : route('cms.contents.index', ['type' => $type]),
                icon        : Content::getTypeInfo($type)['icon'] ?? null,
                modelClass  : Content::class,
                fromDate    : $this->data['fromDate'],
                toDate      : $this->data['toDate'],
                customQuery : fn($query) => $query->byType($type),
                hideIfEmpty : true
            );
        }
    }

    private function getCrmData()
    {
        $this->data['statistics']['crm'][] = dashboardSetItem(
            key         : 'contactuses',
            label       : trans('admin::dashboard.aside_menu.crm_management.contactuses'),
            modelClass  : Contactus::class,
            fromDate    : $this->data['fromDate'],
            toDate      : $this->data['toDate'],
            hideIfEmpty : true
        );

        $this->data['statistics']['crm'][] = dashboardSetItem(
            key         : 'subscribes',
            label       : trans('admin::dashboard.aside_menu.crm_management.subscribes'),
            modelClass  : Subscribe::class,
            fromDate    : $this->data['fromDate'],
            toDate      : $this->data['toDate'],
            hideIfEmpty : true
        );
    }
}
