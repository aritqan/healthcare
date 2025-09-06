<?php

namespace Modules\Admin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class PasswordIsNotTemp
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $exceptRoutes = [
            'admin.auth.changePassword',
            'admin.auth.updatePassword',
        ];

        if (! in_array($request->route()->getName(), $exceptRoutes) && auth()->guard('admin')->user()?->password_is_temp) {
            return redirect()->route('admin.auth.changePassword');
        }

        return $next($request);
    }
}
