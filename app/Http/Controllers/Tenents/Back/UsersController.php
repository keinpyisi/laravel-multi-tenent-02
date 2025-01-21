<?php

namespace App\Http\Controllers\Tenents\Back;

use App\Models\Tenant\Back\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller {
    //
    public function index() {
        $users = User::all();
        // Use the tenant-specific daily log channel
        log_message('Tenant-specific log started for ' . $users);
        return view('tenant.pages.tenant.index', compact('users'));
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
        $users = User::all();
        // Use the tenant-specific daily log channel
        log_message('Tenant-specific log started for ' . $users);
        // Retrieve the authenticated user's ID from the 'tenants' guard
        $userId = Auth::guard('tenants')->id();
        dd($userId);
        dd($users);
    }
}
