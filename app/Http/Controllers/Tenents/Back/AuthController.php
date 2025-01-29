<?php

namespace App\Http\Controllers\Tenents\Back;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Tenant\Tenant;
use Illuminate\Http\JsonResponse;
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
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller {
    public function showLoginForm(Request $request) {
        if(Auth::guard('tenants')->check()){
            return redirect()->intended(route('tenant.client.index', ['tenant' => $request->tenant_name], absolute: false));
        }
        // Forget the cookies
        Auth::guard('tenants')->logout();
        // $request->session()->regenerate();
        // $request->session()->invalidate();
        // $request->session()->regenerateToken();
        // Return the view with cookies forgotten
        return response()
            ->view('tenents.Back.auth.login');
    }

    public function login(LoginRequest $request) {
        try {
          
            $request->authenticate_tenant();

            log_message('Login successful', [
                'session_id' => $request->session()->getId(),
                'auth_check' => Auth::guard('tenants')->check(),
                'session_data' => $request->session()->all()
            ]);
    
            return redirect()->intended(route('tenant.client.index', ['tenant' => $request->tenant_name], absolute: false));
           
        } catch (Exception $ex) {
            log_message('Error occurred during login : ', ['exception' => $ex->getMessage()]);
            return redirect()->intended(route('tenant.users.login',['tenant'=>$request->tenant_name], absolute: false));
        }
    }

    public function back_api_login(Request $request){
       $validator = Validator::make($request->all(), [
            "login_id" => ["required", "exists:users,login_id"],
            "password" => ["required"],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' =>  $validator->errors()
            ], 400);
        }
    
       // Check if credentials are correct
       if (!Auth::guard('tenants')->attempt($request->only('login_id', 'password'))) {
           return response()->json([
               'message' => 'Invalid login credentials.'
           ], 401);
       }

       // Get authenticated user
       $user = Auth::guard('tenants')->user();

       // Create a new token
       $token = $user->createToken(env('APP_NAME', 'Laravel'))->plainTextToken;

       return json_send(JsonResponse::HTTP_OK, [
           'message' => 'Backend Login successful',
           'user' => $user->only(['id', 'login_id', 'tenant_id']),
           'token' => $token
       ]);
   }

    public function logout(Request $request) {
        Auth::guard('tenants')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->intended(route('tenant.users.login',['tenant'=>$request->tenant_name], absolute: false));
    }
}
