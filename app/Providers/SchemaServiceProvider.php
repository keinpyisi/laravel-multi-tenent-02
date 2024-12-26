<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class SchemaServiceProvider extends ServiceProvider {
    /**
     * Register services.
     */
    public function register(): void {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void {
        //
        DB::statement("SET search_path TO base_tenants, public");
    }
}
