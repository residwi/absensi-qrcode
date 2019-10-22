<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{

    public function handle($request, Closure $next)
    {
        $role = $this->getRequiredRoleForRoute($request->route());
        if ($request->user()->hasRole($role) || !$role) {
            return $next($request);
        }
        abort('401');
        /* return response([
            'error' => [
            'code' => 'INSUFFICIENT_ROLE',
            'description' => 'Anda tidak diperbolehkan mengakses sumber daya ini.'
            ]
        ], 401); */
    }

    private function getRequiredRoleForRoute($route)
    {
        $actions = $route->getAction();
        return isset($actions['roles']) ? $actions['roles'] : null;
    }
}
