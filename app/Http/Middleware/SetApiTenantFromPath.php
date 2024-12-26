<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\Base\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SetApiTenantFromPath {
    public function handle($request, Closure $next) {
         // Basic Auth check
         if (!$request->session()->isStarted()) {
            $request->session()->start();
        }
        $path = $request->path();
        $segments = explode('/', $path);
        if (count($segments) >= 2 && $segments[1] === 'backend') {
            if ($segments[2] === 'admin') {
                $tenantLogPath = storage_path('logs/admins/');
                config(['logging.channels.tenant' => [
                    'driver' => 'daily',
                    'path' => $tenantLogPath . '/laravel.log',
                    'level' => 'debug',
                    'days' => 14, // Keep logs for 14 days
                ]]);
                // Admin routes
                DB::statement("SET search_path TO base_tenants");
            } else {
                // Tenant routes
                $tenantSlug = $segments[2];
                $tenant = Tenant::where('domain', $tenantSlug)->first();

                if ($tenant) {
                    // Set the tenant in the app container
                    app()->instance('tenant', $tenant);

                    // Set the database connection to use only the tenant's schema
                    DB::statement("SET search_path TO {$tenant->database}");
                    config(['database.connections.tenant.search_path' => $tenant->database]);
                    DB::purge('tenant');
                    DB::reconnect('tenant');
                    // Set custom daily log path for the tenant
                    $tenantLogPath = storage_path('logs/tenants/' . $tenantSlug);
                    config(['logging.channels.tenant' => [
                        'driver' => 'daily',
                        'path' => $tenantLogPath . '/laravel.log',
                        'level' => 'debug',
                        'days' => 14, // Keep logs for 14 days
                    ]]);
                } else {
                    abort(404);
                }
            }
        } else {
            // For routes not starting with 'backend', set to public schema
            DB::statement("SET search_path TO public");
        }
        

        return $next($request);
    }
}
