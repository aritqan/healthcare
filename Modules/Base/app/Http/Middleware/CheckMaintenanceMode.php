<?php

namespace Modules\Base\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\Base\Http\Middleware\BaseMiddleware;

class CheckMaintenanceMode extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $excludePaths   = ['admin/*'];
        $excludeRoutes  = ['admin.*'];

        if($this->isExcluded($request, $excludePaths, $excludeRoutes)) {
            return $next($request);
        }

        if ($this->isMaintenanceMode()) {
            return sendMaintenanceModeResponse();
        }

        return $next($request);
    }

    /**
     * Check if the application is in maintenance mode.
     */
    private function isMaintenanceMode(): bool
    {
        return getSetting('maintenance_mode', false);
    }

    /**
     * Check if the request is excluded from maintenance mode.
     */
    private function isExcluded(Request $request, array $excludePaths, array $excludeRoutes): bool
    {
        foreach ($excludePaths as $path) {
            if ($request->is(app()->getLocale() . '/' . $path) || $request->is($path)) {
                return true;
            }
        }

        foreach ($excludeRoutes as $route) {
            if (Str::startsWith($request->route()?->getName(), $route)) {
                return true;
            }
        }

        return false;
    }
}
