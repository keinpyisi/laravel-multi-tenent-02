<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Auth\LoginRequest;

class AuthController extends Controller {
    protected $redirectTo = 'backend/admin/login';
    public function showLoginForm() {
        return view('admin.auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse{
        try {
            // Validate input
            $request->validate([
                'login_id' => 'required|string',
                'password' => 'required|string',
            ]);

            // Attempt login using user ID and password
            DB::statement("SET search_path TO common");
          
            $request->authenticate();

            $request->session()->regenerate();
    
            return redirect()->intended(route('admin.tenants.index', absolute: false));
           
        } catch (Exception $ex) {
            dd($ex);
            log_message('Error occurred during login : ', ['exception' => $ex->getMessage()]);
            return redirect()->intended(route('admin.users.login', absolute: false));
        } finally {
            DB::statement("SET search_path TO common");
        }
    }

    public function logout(Request $request) {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        return redirect()->route('admin.users.login');
    }
}
