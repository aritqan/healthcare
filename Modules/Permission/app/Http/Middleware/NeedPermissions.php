<?php

namespace Modules\Permission\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Base\Http\Middleware\BaseMiddleware;

class NeedPermissions extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        if($user = auth()->guard('admin')->user()) {
            if($user->isRoot()) {
                return $next($request);
            }

            foreach($permissions as $permission) {
                if($user->can($permission)) {
                    return $next($request);
                }
            }
        }

        return sendDontHavePermissionResponse();
    }
}
