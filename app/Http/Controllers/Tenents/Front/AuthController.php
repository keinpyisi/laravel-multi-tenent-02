<?php

namespace App\Http\Controllers\Tenents\Front;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Tenant\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\Auth\LoginRequest;

class AuthController extends Controller {
    public function showLoginForm(Request $request) {
        // Forget the cookies
        Auth::guard('tenants_front')->logout();
        // $request->session()->regenerate();
        // $request->session()->invalidate();
        // $request->session()->regenerateToken();
        // Return the view with cookies forgotten
        return response()
            ->view('tenents.Front.auth.login');
    }

    public function login(LoginRequest $request) {
        try {
          
            $request->authenticate_front_tenant();

            log_message('Login successful', [
                'session_id' => $request->session()->getId(),
                'auth_check' => Auth::guard('tenants_front')->check(),
                'session_data' => $request->session()->all()
            ]);
    
            return redirect()->intended(route('tenant.front.client.index', ['tenant' => $request->tenant_name], absolute: false));
           
        } catch (Exception $ex) {
            log_message('Error occurred during login : ', ['exception' => $ex->getMessage()]);
            return redirect()->intended(route('tenant.front.client.index',['tenant'=>$request->tenant_name], absolute: false));
        }
    }

    public function logout(Request $request) {
        Auth::guard('tenants_front')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->intended(route('front.tenant.users.login',['tenant'=>$request->tenant_name], absolute: false));
    }
}
