<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenents\Back\OrderController;


Route::prefix('backend/{tenant}')
    ->middleware(['set.maitainence','set.api.tenant'])  // Middleware to load tenant
    ->name('tenant.')
    ->group(function () {

        //public route
        // // Protected routes requiring tenant authentication
        Route::middleware(['tenent.auth'])->group(function () {

            Route::middleware(['check.api.auth']) // Use the middleware name here
             ->group(function () {
                Route::get('orders', [OrderController::class, 'api_index'])->name('api.orders.index');
                
                Route::post('orders', [OrderController::class, 'store'])->name('orders.store');
                Route::put('orders/{id}', [OrderController::class, 'update'])->name('orders.update');
                Route::delete('orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
         });
           
        });
    });


    