<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/tenant/logo/{domain}/{file}', function ($domain, $file) {
    $path = "{$domain}/logo/{$file}";
    if (Storage::disk('tenant')->exists($path)) {
        // Get the full path of the file from the 'tenant' disk
        $fullPath = Storage::disk('tenant')->path($path);

        // Return the file as a response
        return response()->file($fullPath);
    } else {
        // Handle file not found case
        return response()->json(['message' => 'File not found'], 404);
    }

    abort(404);
})->name('tenant.logo');

require __DIR__.'/auth.php';
