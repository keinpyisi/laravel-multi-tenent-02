<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Tenant\Tenant;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class TenentAuthMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        // Start session if not started
        if (!$request->hasSession()) {
            $request->setLaravelSession(app('session')->driver());
        }

        $session = $request->session();
        // log_message('TenantAuth Initial Check', [
        //     'session_id' => $session->getId(),
        //     'has_session' => $request->hasSession(),
        //     'session_data' => $session->all()
        // ]);

        // Check if the user is authenticated
        $path = $request->path();
        $segments = explode('/', $path);
        if (count($segments) >= 2 && $segments[0] === 'backend') {
            if ($segments[1] === 'admin') {
                $request->merge(['tenant_name' => 'admin']);
            } else {
                // Tenant routes
                $tenantSlug = $segments[1];
                $request->merge(['tenant_name' => $tenantSlug]);  
                // Try to restore authentication
                if (!Auth::guard('tenants')->check()) {
                    $userId = $session->get('tenant_user_id');
                    $tenantUser = $session->get('tenant_user');

                    if ($userId && $tenantUser) {
                        $user = \App\Models\Tenant\Back\User::find($userId);
                        if ($user) {
                            Auth::guard('tenants')->login($user);
                            // log_message('Auth restored', [
                            //     'user_id' => $userId,
                            //     'auth_check' => Auth::guard('tenants')->check()
                            // ]);
                        }
                    }
                }

                if (!Auth::guard('tenants')->check()) {
                    log_message('Authentication failed', [
                        'session_data' => $session->all()
                    ]);
                    return redirect()->route('tenant.users.login', ['tenant' => $request->route('tenant')]);
                }

            }
        }else  if (count($segments) >= 2 && $segments[0] === 'frontend') {
            // Try to restore authentication
            if (!Auth::guard('tenants_front')->check()) {
                $userId = $session->get('front_tenant_user_id');
                $tenantUser = $session->get('front_tenant_user');

                if ($userId && $tenantUser) {
                    $user = \App\Models\Tenant\Front\FrontUser::find($userId);
                    if ($user) {
                        Auth::guard('tenants_front')->login($user);
                        // log_message('Auth restored', [
                        //     'user_id' => $userId,
                        //     'auth_check' => Auth::guard('tenants')->check()
                        // ]);
                    }
                }
            }

            if (!Auth::guard('tenants_front')->check()) {
                log_message('Authentication failed', [
                    'session_data' => $session->all()
                ]);
                return redirect()->route('tenant.front.users.login', ['tenant' => $request->route('tenant')]);
            }
        }
        
        
        return $next($request);
    }
}
