<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tenents\Back\AuthController;
use App\Http\Controllers\Tenents\Back\OrderController;


Route::prefix('backend/{tenant}')
    ->middleware(['basic.auth','set.maitainence','set.api.tenant'])  // Middleware to load tenant
    ->name('tenant.')
    ->group(function () {

        Route::post('login', [AuthController::class, 'back_api_login'])->name('back.user.login');
    });


    