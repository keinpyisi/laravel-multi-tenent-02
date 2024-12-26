<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Carbon\Carbon;
use App\Models\Base\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;

class SetTenantFromPath {
    public function handle($request, Closure $next) {
        try {
             // Ensure session is started
            if (!$request->session()->isStarted()) {
                $request->session()->start();
            }
            // log_message('SetTenant Before', [
            //     'session_id' => session()->getId(),
            //     'session_data' => session()->all(),
            //     'auth_check' => Auth::guard('tenants')->check()
            // ]);
            $path = $request->path();
            $segments = explode('/', $path);
            // Check if we are already at the login page

            if (count($segments) >= 2 && $segments[0] === 'backend') {
                if ($segments[1] === 'admin') {
                    $request->merge(['tenant_name' => 'admin']);
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
                    $tenantSlug = $segments[1];
                    $tenant = Tenant::where('domain', $tenantSlug)->first();
                    $request->merge(['tenant_name' => $tenantSlug]);
                    if ($tenant) {
                        // Set the tenant in the app container
                        app()->instance('tenant', $tenant);

                        // Set the database connection to use only the tenant's schema
                        DB::statement("SET search_path TO '{$tenant->database}'");

                        config(['database.connections.tenant.search_path' => $tenant->database]);
                        config(['auth.guards.tenant.provider' => 'tenants']);
                        DB::purge('tenant');
                        DB::reconnect('tenant');
                        // Share the tenant slug with all views
                        view()->share('tenant_name', $tenantSlug);
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
                $request->merge(['tenant_name' => 'admin']);
                // For routes not starting with 'backend', set to public schema
                DB::statement("SET search_path TO public");
            }
            $userId = session('tenant_user_id');
            if ($userId && !Auth::guard('tenants')->check()) {
                $user = \App\Models\Tenant\User::find($userId);
                if ($user) {
                    Auth::guard('tenants')->login($user);
                    session()->save(); // Force save the session
                }
            }

            //$schema = DB::selectOne('SHOW search_path');
            $response = $next($request);
            return $response;
        } catch (Exception $ex) {
            Log::error('Middleware Error: ');
            Log::error($ex);
        }
    }
}
