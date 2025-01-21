<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenents\Front\AuthController;
use App\Http\Controllers\Tenents\Front\UsersController;

Route::prefix('frontend/{tenant}')
    ->middleware(['web', 'start.session','basic.auth','set.maitainence','set.tenant'])  // Middleware to load tenant
    ->name('tenant.')
    ->group(function () {

        //public route
        Route::get('login', [AuthController::class, 'showLoginForm'])->name('front.users.login');
        Route::post('login', [AuthController::class, 'login'])->name('front.users.check_login');
        Route::post('logout', [AuthController::class, 'logout'])->name('front.users.logout');
        // // Protected routes requiring tenant authentication
        Route::middleware(['tenent.auth'])->group(function () {
            Route::get('users', [UsersController::class, 'datas'])->name('front.client.index');
            
        });
    });
