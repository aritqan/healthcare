<?php

namespace Modules\Base\Http\Controllers;

use Illuminate\Support\Facades\Artisan;
use Modules\Config\Enums\permissions\SettingPermissions;

class ActionController extends BaseController
{
    public function clearCache()
    {
        Artisan::call('cache:clear');

        return sendSuccessResponse(message: trans('cache_cleared'));
    }

    public function clearLogs()
    {
        Artisan::call('log:clear');

        return sendSuccessResponse(message: trans('logs_cleared'));
    }

    public function resetPermissions()
    {
        Artisan::call('module:seed' , [
            '--class'   => 'PermissionDatabaseSeeder',
            'module'    => 'Permission',
        ]);

        return sendSuccessResponse(message: trans('permissions_reset'));
    }
}
