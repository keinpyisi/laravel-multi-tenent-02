<?php

use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use App\Http\Middleware\SetTenantFromPath;
use App\Http\Middleware\AdminAuthMiddleware;
use App\Http\Middleware\SetApiTenantFromPath;
use App\Http\Middleware\TenentAuthMiddleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: array_merge(
            [
                __DIR__ . '/../routes/web.php',
            ],
            // Use native PHP glob to load wildcard routes for web/admin and web/tenants
            glob(__DIR__ . '/../routes/admin/*.php'),
            glob(__DIR__ . '/../routes/tenents/*.php')
        ),
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        api: array_merge(
            [
                __DIR__ . '/../routes/api.php',
            ],
            // Manually load wildcard routes for api/admin and api/tenants
            glob(__DIR__ . '/../routes/api/admin/*.php'),
            glob(__DIR__ . '/../routes/api/tenants/*.php')
        ),
        apiPrefix: 'api',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'set.tenant' => SetTenantFromPath::class,
            'set.api.tenant' => SetApiTenantFromPath::class,
            'admin.auth' => AdminAuthMiddleware::class,
            'tenent.auth' => TenentAuthMiddleware::class,
            'start.session' => \App\Http\Middleware\StartSession::class,

        ]);
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo(function (Request $request) {
            $path = $request->path();
            $segments = explode('/', $path);
            if (count($segments) >= 2 && $segments[0] === 'backend') {
                $tenantSlug = $segments[1];
                return "/backend/{$tenantSlug}/login";
            }
            // Default redirect for other paths (if necessary)
            return '/'; // Or any other default route
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withProviders([
        // ... other service providers ...
        App\Providers\SchemaServiceProvider::class,
    ])
    ->create();

