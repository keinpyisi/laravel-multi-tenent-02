<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenents\Front\AuthController;


Route::prefix('frontend/{tenant}')
    ->middleware(['set.maitainence','set.api.tenant'])  // Middleware to load tenant
    ->name('tenant.')
    ->group(function () {

        Route::post('login', [AuthController::class, 'front_api_login'])->name('back.user.login');
    });


    