<?php

namespace Modules\Admin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Base\Http\Middleware\BaseMiddleware;

class SystemUserInfo extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $isSyestemOwner = false;
        $systemUser     = null;
        $adminUser      = null;

        if($user = auth()->guard('admin')->user()) {
            if($user->isRoot()) {
                $isSyestemOwner = true;
            }
            $systemUser     = $user;
            $adminUser      = $user;
        }

        app()->singleton('owner', function () use ($isSyestemOwner) {
            return $isSyestemOwner;
        });

        app()->singleton('admin', function () use ($adminUser) {
            return $adminUser;
        });

        app()->singleton('user', function () use ($systemUser) {
            return $systemUser;
        });

        return $next($request);
    }
}
