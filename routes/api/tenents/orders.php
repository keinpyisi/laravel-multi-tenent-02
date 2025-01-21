<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenents\Back\OrderController;


Route::prefix('backend/{tenant}')
    ->middleware(['basic.auth','set.maitainence','set.api.tenant'])  // Middleware to load tenant
    ->name('tenant.')
    ->group(function () {

        //public route
        // // Protected routes requiring tenant authentication
        Route::middleware(['tenent.auth'])->group(function () {
            Route::post('orders', [OrderController::class, 'store'])->name('orders.store');
            Route::put('orders/{id}', [OrderController::class, 'update'])->name('orders.update');
            Route::delete('orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
        });
    });