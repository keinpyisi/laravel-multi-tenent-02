<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenents\Back\AuthController;
use App\Http\Controllers\Tenents\Back\UsersController;

Route::prefix('backend/{tenant}')
    ->middleware(['web', 'start.session','basic.auth','set.maitainence','set.tenant'])  // Middleware to load tenant
    ->name('tenant.')
    ->group(function () {

        //public route
        Route::get('login', [AuthController::class, 'showLoginForm'])->name('users.login');
        Route::post('login', [AuthController::class, 'login'])->name('users.check_login');
        Route::post('logout', [AuthController::class, 'logout'])->name('users.logout');
        // // Protected routes requiring tenant authentication
        Route::middleware(['tenent.auth'])->group(function () {
            Route::get('/users', [UsersController::class, 'index'])->name('client.index');
            Route::get('/datas', [UsersController::class, 'datas'])->name('client.index');
        });
    });
