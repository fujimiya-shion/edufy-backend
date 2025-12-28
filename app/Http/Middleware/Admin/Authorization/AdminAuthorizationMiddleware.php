<?php

namespace App\Http\Middleware\Admin\Authorization;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthorizationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $param): Response
    {
        $user = auth('admin')->user();

        if (!$user) {
            abort(403, __('Không có quyền truy cập'));
        }

        // Bypass the authorization for super-admin
        if ($user->hasRole('super-admin')) {
            return $next($request);
        }

        $permissions = explode('|', $param);

        foreach ($permissions as $permission) {
            [$crud, $resource] = explode('-', $permission, 2);

            $crudActions = ['create', 'edit', 'read', 'delete'];

            if ($crud === 'manage') {
                foreach ($crudActions as $action) {
                    if ($user->hasPermission("{$action}-{$resource}")) {
                        return $next($request);
                    }
                }
            }

            if ($user->hasPermission("{$crud}-{$resource}")) {
                return $next($request);
            }
        }

        abort(403, __('Bạn không có quyền truy cập: ') . $param);
    }

}
