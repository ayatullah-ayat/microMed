<?php

namespace Alauddin\Authorize\Middleware;

use Closure;

class CheckAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $actionName = $request->route()->getActionName();
        $method = $request->route()->methods()[0];
        $action = substr($actionName, strpos($actionName, '@') + 1);
        $namespace = substr($actionName, 0, strrpos($actionName, '\\'));
        $controller = substr($actionName, strrpos($actionName, '\\') + 1, -(strlen($action) + 1));

        if ($request->user()->isAdmin() || $request->user()->isAuthorize($namespace, $controller, $method, $action)) {
            return $next($request);
        }

        return response(view('vendor.authorize.errors.401'), 401);
    }
}
