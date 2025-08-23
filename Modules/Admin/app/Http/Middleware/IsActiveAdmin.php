<?php

namespace Modules\Admin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Base\Http\Middleware\BaseMiddleware;

class IsActiveAdmin extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->guard('admin')->check()) {
            $admin = auth()->guard('admin')->user();

            if (! $admin->isActive()) {

                auth()->guard('admin')->logout();

                $request->session()->invalidate();

                $request->session()->regenerateToken();

                return sendUnauthorizedResponse(route('admin.auth.login'), 'login_failed_inactive');
            }
        } else {
            return sendUnauthorizedResponse(route('admin.auth.login'));
        }

        return $next($request);
    }
}
