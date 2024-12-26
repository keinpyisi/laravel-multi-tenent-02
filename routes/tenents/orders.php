<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenents\AuthController;
use App\Http\Controllers\Tenents\UserController;
use App\Http\Controllers\Tenents\OrderController;
use App\Http\Controllers\Tenant\LoginController as TenantLoginController;
use App\Http\Controllers\Tenant\DashboardController as TenantDashboardController;

Route::prefix('backend/{tenant}')
    ->middleware(['web', 'start.session','set.tenant'])   // Middleware to load tenant
    ->name('tenant.')
    ->group(function () {

        //public route
        // // Protected routes requiring tenant authentication
        Route::middleware(['tenent.auth'])->group(function () {
            Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
            Route::get('orders', [OrderController::class, 'create'])->name('orders.create');
            Route::get('orders/{id}', [OrderController::class, 'show'])->name('orders.show');
            Route::get('orders/{id}/edit', [OrderController::class, 'edit'])->name('orders.edit');
        });
    });
