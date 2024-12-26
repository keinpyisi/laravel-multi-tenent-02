<?php

namespace App\Console\Commands;

use App\Models\Base\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class TenantCommand extends Command {
    //php artisan tenant:run ascon backup
    protected $signature = 'tenant:run {tenant} {command} {--force}';
    protected $description = 'Run an Artisan command for a specific tenant';

    public function handle() {
        $tenantSlug = $this->argument('tenant');
        $command = $this->argument('command');

        DB::statement("SET search_path TO base_tenants");
        $tenant = Tenant::where('domain', $tenantSlug)->first();

        if (!$tenant) {
            $this->error("Tenant not found: {$tenantSlug}");
            return 1;
        }

        // Set the tenant in the app container
        app()->instance('tenant', $tenant);

        // Set the database connection to use the tenant's schema and base_tenants
        DB::statement("SET search_path TO {$tenant->database}, base_tenants, public");
        config(['database.connections.tenant.search_path' => "{$tenant->database},base_tenants,public"]);
        DB::purge('tenant');
        DB::reconnect('tenant');

        $this->info("Running command for tenant: {$tenant->name}");

        // Run the specified command
        $params = $this->option('force') ? ['--force' => true] : [];
        $exitCode = Artisan::call($command, $params);

        $this->info(Artisan::output());

        return $exitCode;
    }
}
