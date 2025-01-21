<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Json\UserJson;
use App\Http\Controllers\Admin\Json\UserTenentJson;
use App\Http\Controllers\Admin\Json\MaintainenceJson;

Route::prefix('backend/admin')->name('admin.')->middleware(['basic.auth','set.maitainence','set.api.tenant'])->group(function () {
    Route::middleware(['admin.auth'])->group(function () {
        Route::get('user', [UserJson::class, 'get_all'])->name('users.all');
        Route::post('users', [UserJson::class, 'store'])->name('users.store');
        Route::put('users/{id}/update', [UserJson::class, 'update'])->name('users.update');
        Route::delete('users', [UserJson::class, 'destroy'])->name('users.destroy');
        Route::get('users/{id}', [UserJson::class, 'get_one'])->name('users.show');
    
        Route::get('maitenance', action: [MaintainenceJson::class, 'get_all'])->name('maitenance.all');
        Route::get('maitenances/{id}', [MaintainenceJson::class, 'get_one'])->name('maitenance.show');
        Route::put('maitenances/{tenent}/update', [MaintainenceJson::class, 'update'])->name('maitenance.update');
    
    
        Route::get('tenent_user', [UserTenentJson::class, 'get_all'])->name('tenent_user.all');
        Route::post('tenent_users', [UserTenentJson::class, 'store'])->name('tenent_user.store');
        Route::put('tenent_users/{id}/update', [UserTenentJson::class, 'update'])->name('tenent_user.update');
        Route::delete('tenent_users', [UserTenentJson::class, 'destroy'])->name('tenent_user.destroy');
        Route::get('tenent_users/{id}', [UserTenentJson::class, 'get_one'])->name('tenent_user.show');
    });
   
});
