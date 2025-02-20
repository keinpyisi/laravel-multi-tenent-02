<?php

namespace App\Http\Controllers\Tenents\Front;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant\Front\FrontUser;

class UsersController extends Controller {
    //
    public function index() {
        $users = FrontUser::all();
        // Use the tenant-specific daily log channel
        log_message('Tenant-specific log started for ' . $users);
        return view('tenents.Front.pages.index', compact('users'));
    }
    public function datas() {
        // User::create([
        //     'login_id' => 'ascon',
        //     'email' => 'ascon@ascon.co.jp',
        //     'user_name' => 'ascon',
        //     'password' => 'asadsadsafd',
        //     'tenant_id' => 1,
        //     'mst_user_auth_id' => 1,
        // ]);
        $users = FrontUser::all();
        // Use the tenant-specific daily log channel
        log_message('Tenant-specific log started for ' . $users);
        // Retrieve the authenticated user's ID from the 'tenants' guard
        $userId = Auth::guard('tenants_front')->id();
        dd($userId);
        //dd($users);
    }
}
