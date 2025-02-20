<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Models\Base\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SetApiTenantFromPath {
    public function handle($request, Closure $next) {
        try {
          
            $path = $request->path();
            $segments = explode('/', $path);
            if (count($segments) >= 3 && $segments[1] === 'backend') {
                $this->handleBackendRoutes($request, $segments[2]);
                $this->authenticateTenantUser();
            } else if(count($segments) >= 2 && $segments[1] === 'frontend'){
                $this->handleFrontendRoutes($request, $segments[2]);
                $this->authenticateFrontTenantUser();
            }else {
                $this->setPublicSchema($request);
            }

            return $next($request);
        } catch (Exception $ex) {
            Log::error('Middleware Error: ', ['exception' => $ex]);
            abort(500, 'Internal Server Error');
        }
    }

    private function handleBackendRoutes(Request $request, $tenantSlug) {
        if ($tenantSlug === 'admin') {
            $this->setAdminConfig($request);
        } else {
            $this->setTenantConfig($request, $tenantSlug);
        }
    }

    private function handleFrontendRoutes(Request $request, $tenantSlug) {
        if ($tenantSlug === 'admin') {
            abort(404);
        } else {
            $this->setTenantConfig($request, $tenantSlug);
        }
    }

    private function setAdminConfig(Request $request) {
        $request->merge(['tenant_name' => 'admin']);

        // Set the schema only if necessary
        if (config('database.connections.tenant.search_path') !== 'common') {
            DB::statement("SET search_path TO common");
        }

        config(['logging.channels.tenant' => [
            'driver' => 'daily',
            'path' => storage_path('logs/admins/laravel.log'),
            'level' => 'debug',
            'days' => 14,
        ]]);
    }

    private function setTenantConfig(Request $request, $tenantSlug) {
        // Cache tenant lookup to reduce DB queries
        $tenant = Cache::remember("tenant:{$tenantSlug}", now()->addMinutes(10), function () use ($tenantSlug) {
            return Tenant::where('domain', $tenantSlug)->first(['id', 'database']);
        });
        if (!$tenant) {
            abort(404, 'Tenant Not Found');
        }

        $request->merge(['tenant_name' => $tenantSlug]);
        app()->instance('tenant', $tenant);

        // Avoid setting the same schema multiple times
        if (config('database.connections.tenant.search_path') !== $tenant->database) {
            DB::statement("SET search_path TO '{$tenant->database}'");
        }

        view()->share('tenant_name', $tenantSlug);

        config([
            'database.connections.tenant.search_path' => $tenant->database,
            'auth.guards.tenant.provider' => 'tenants',
            'logging.channels.tenant' => [
                'driver' => 'daily',
                'path' => storage_path("logs/tenants/{$tenantSlug}/laravel.log"),
                'level' => 'debug',
                'days' => 14,
            ],
        ]);
    }

    private function setPublicSchema(Request $request) {
        $request->merge(['tenant_name' => 'admin']);

        // Avoid redundant DB query
        if (config('database.connections.tenant.search_path') !== 'public') {
            DB::statement("SET search_path TO public");
        }
    }

    private function authenticateTenantUser() {
        $userId = session('tenant_user_id');

        if ($userId && !Auth::guard('tenants')->check()) {
            // Use cache to prevent frequent database lookups
            $user = Cache::remember("tenant_user:{$userId}", now()->addMinutes(10), function () use ($userId) {
                return \App\Models\Tenant\Back\User::find($userId, ['id', 'name', 'email','remember_token']); // Load only required fields
            });

            if ($user) {
                Auth::guard('tenants')->login($user);
                session()->save(); // Ensure session is updated
            }
        }
    }

    private function authenticateFrontTenantUser() {
        $userId = session('front_tenant_user_id');

        if ($userId && !Auth::guard('tenants_front')->check()) {
            // Use cache to prevent frequent database lookups
            $user = Cache::remember("front_tenant_user:{$userId}", now()->addMinutes(10), function () use ($userId) {
                return \App\Models\Tenant\Front\FrontUser::find($userId, ['id', 'name', 'email','login_id','remember_token']); // Load only required fields
            });

            if ($user) {
                Auth::guard('tenants_front')->login($user);
                session()->save(); // Ensure session is updated
            }
        }
    }
}
