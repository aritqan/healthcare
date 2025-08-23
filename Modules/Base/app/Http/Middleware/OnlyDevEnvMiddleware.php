<?php

namespace Modules\Base\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Base\Http\Middleware\BaseMiddleware;

class OnlyDevEnvMiddleware extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if(isDev()) {
            return $next($request);
        } else {
            return sendFailResponse('this_action_is_not_allowed_in_production');
        }
    }
}
