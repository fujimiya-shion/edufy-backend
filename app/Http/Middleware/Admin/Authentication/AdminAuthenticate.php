<?php

namespace App\Http\Middleware\Admin\Authentication;

use Illuminate\Auth\Middleware\Authenticate as AuthenticateMiddleware;
use Illuminate\Http\Request;

class AdminAuthenticate extends AuthenticateMiddleware
{
    protected function redirectTo(Request $request)
    {
        if (!$request->expectsJson()) {
            return route('filament.admin.auth.login');
        }

        if ($request->is('admin') || $request->is('admin/*')) {
            return route('filament.admin.auth.login');
        }
    }
}
