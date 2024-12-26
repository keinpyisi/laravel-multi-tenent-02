<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class one_CreateBaseTenantSchema extends Command
{
    //php artisan tenant:create-base-schema --seed
    protected $signature = 'tenant:create-base-schema {--fresh} {--seed}';
    protected $description = 'Create base tenant schema and run migrations';

    public function handle()
    {
        $this->info('Setting up base tenant schema...');

        try {
            // Create the base_tenants schema if it doesn't exist
            $this->createSchema('base_tenants');

            // Set the search path to use the base_tenants schema
            $this->setSearchPath('base_tenants');

            // Run migrations
            $this->runMigrations();

            // Optionally seed the database
            if ($this->option('seed')) {
                $this->runSeeder();
            }

            $this->info('Base tenant schema setup completed successfully.');
        } catch (\Exception $e) {
            $this->error("Error during setup: " . $e->getMessage());
        }
    }

    private function createSchema($name)
    {
        $this->info("Checking if schema '$name' exists...");

        $query = "SELECT 1 FROM information_schema.schemata WHERE schema_name = :schema_name";
        $exists = DB::select($query, ['schema_name' => $name]);

        if (empty($exists)) {
            $this->info("Schema '$name' does not exist. Creating...");
            DB::statement("CREATE SCHEMA \"$name\"");
            $this->info("Schema '$name' created successfully.");
        } else {
            $this->info("Schema '$name' already exists.");
        }
    }

    private function setSearchPath($schema)
    {
        $this->info("Setting search path to schema '$schema'...");
        DB::statement("SET search_path TO \"$schema\", public");
        $this->info("Search path set to schema '$schema'.");
    }

    private function runMigrations()
    {
        $this->info("Running migrations...");

        $params = ['--path' => 'database/migrations/base'];

        if ($this->option('fresh')) {
            $this->info("Running 'migrate:fresh'...");
            Artisan::call('migrate:fresh', $params);
        } else {
            $this->info("Running 'migrate'...");
            Artisan::call('migrate', $params);
        }

        $this->info(Artisan::output());
    }

    private function runSeeder()
    {
        $this->info("Running database seeder...");
        Artisan::call('db:seed', [
            '--class' => 'BaseTenantDatabaseSeeder',
        ]);
        $this->info(Artisan::output());
    }
}