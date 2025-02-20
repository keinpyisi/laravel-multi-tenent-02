<?php

namespace App\Http\Controllers\Admin;


use Exception;
use App\Models\Base\Tenant;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Tenant\Back\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Admin\Client_Validation;
use \App\Models\Tenant\Tenant as Client_Tenant;
use App\Http\Requests\Admin\Client_Edit_Validation;

class UserController extends Controller {
    private Request $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function index() {
        $header_js_defines = [
            'resources/js/users/index.js',
        ];
        $header_css_defines = [
            //'resources/css/clients/index.css',
        ];

        // Share the variable globally
        view()->share('header_js_defines', $header_js_defines);
        view()->share('header_css_defines', $header_css_defines);
        // Return the view with the paginated tenants
        return view('admin.pages.admins.users.list');
    }


    public function create() {
        $header_js_defines = [
            'resources/js/admins/create.js',
        ];
        $header_css_defines = [
            //'resources/css/clients/index.css',
        ];
        // Share the variable globally
        view()->share('header_js_defines', $header_js_defines);
        view()->share('header_css_defines', $header_css_defines);
        return view('admin.pages.tenants.create');
    }

    public function show(int $id) {
        DB::statement("SET search_path TO common");

        $r = [
            "id" => $id,
        ];

        $validator = Validator::make($r, [
            "id" => ["required", "exists:tenants,id"],
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Return 404 if validation fails
            abort(404);
        }
        $header_js_defines = [
            'resources/js/admins/show.js',
        ];
        $header_css_defines = [
            //'resources/css/clients/index.css',
        ];

        // Share the variable globally
        view()->share('header_js_defines', $header_js_defines);
        view()->share('header_css_defines', $header_css_defines);


        return view('admin.pages.tenants.show', compact('tenant', 'users', 'all_usage', 'client_usage'));
    }


    public function edit(int $id) {
    }
}
