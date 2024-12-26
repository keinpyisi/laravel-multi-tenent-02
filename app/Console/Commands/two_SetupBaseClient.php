<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class two_SetupBaseClient extends Command
{
    protected $signature = 'setup:base-client {--fresh} {--seed}';
    protected $description = 'Set up the base_client schema';

    public function handle()
    {
        $this->info('Setting up base_client schema...');

        try {
            // Create the base_client schema if it doesn't exist
            $this->createSchema('base_client');

            // Set the search path to use the base_client schema
            $this->setSearchPath('base_client');

            // Run migrations
            $this->runMigrations();

            // Optionally seed the database
            if ($this->option('seed')) {
                $this->runSeeder();
            }

            $this->info('base_client schema setup completed successfully.');
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

        $params = ['--path' => 'database/migrations/tenant'];

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
            '--class' => 'BaseClientDatabaseSeeder',
        ]);
        $this->info(Artisan::output());
    }
}