<?php

namespace Modules\Auth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Base\Http\Middleware\BaseMiddleware;

class ActiveUser extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if($user) {
            if (! $user->isActive()) {

                $user->tokens()->delete();

                return sendUnauthorizedResponse(message:'login_failed_inactive');
            }
        } else {
            return sendUnauthorizedResponse(message:'login_required');
        }

        return $next($request);
    }
}
