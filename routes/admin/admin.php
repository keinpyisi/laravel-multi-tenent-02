<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Admin\MaitenanceController;

Route::prefix('backend/admin')->name('admin.')->middleware(['basic.auth','set.tenant'])->group(function () {

    Route::get('login', [AuthController::class, 'showLoginForm'])->name('users.login');
    Route::post('login', [AuthController::class, 'login'])->name('users.check_login');
    Route::post('logout', [AuthController::class, 'logout'])->name('users.logout');

    Route::middleware(['auth'])->group(function () {
        Route::resource('tenants', TenantController::class);
        Route::resource('maitenance', MaitenanceController::class);

        Route::post('tenants/{domain}/reset', [TenantController::class, 'reset_basic'])->name('tenants.reset');

        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/create', [UserController::class, 'create'])->name('users.create');
        Route::get('users/{id}', [UserController::class, 'show'])->name('users.show');
        Route::get('users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    });
});
