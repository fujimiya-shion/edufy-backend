<?php

use App\Http\Middleware\Admin\Authorization\AdminAuthorizationMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laratrust\Middleware\Ability as AbilityMiddleware;
use Laratrust\Middleware\Permission as PermissionMiddleware;
use Laratrust\Middleware\Role as RoleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->group('auth:admin', [
            \App\Http\Middleware\Admin\Authentication\AdminAuthenticate::class,
        ]);

        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'ability' => AbilityMiddleware::class,
            'admin.authorize' => AdminAuthorizationMiddleware::class,
        ]);

        $middleware->validateCsrfTokens([
            '/api/*', 'webhook/auto-*'
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
