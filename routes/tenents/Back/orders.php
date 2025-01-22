<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenents\Back\AuthController;
use App\Http\Controllers\Tenents\Back\UserController;
use App\Http\Controllers\Tenents\Back\OrderController;

Route::prefix('backend/{tenant}')
    ->middleware(['web', 'start.session','basic.auth','set.maitainence','set.tenant'])   // Middleware to load tenant
    ->name('tenant.')
    ->group(function () {

        //public route
        // // Protected routes requiring tenant authentication
        Route::middleware(['tenent.auth'])->group(function () {

             // Create a new middleware class instead of inline closure
             // Resource routes for orders
             Route::get('orders', [OrderController::class, 'index'])
                 ->name('orders.index');
             Route::get('orders/create', [OrderController::class, 'create'])
                 ->name('orders.create');
             Route::get('orders/{id}', [OrderController::class, 'show'])
                 ->name('orders.show');
             Route::get('orders/{id}/edit', [OrderController::class, 'edit'])
                 ->name('orders.edit');
        });
    });
