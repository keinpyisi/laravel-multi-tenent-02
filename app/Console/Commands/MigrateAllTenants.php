<?php

namespace App\Console\Commands;

use App\Models\Base\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;

class MigrateAllTenants extends Command
{
    protected $signature = 'tenants:migrate {--fresh} {--seed}';
    protected $description = 'Run migrations for common and all tenant schemas';

    public function handle()
    {
        
        // Migrate common first
        //$this->migrateSchema('common', 'database/migrations/common', 'BaseTenantsSeeder');

        // Then migrate all tenant schemas
        $this->migrateTenantSchemas();

        $this->info("Migration completed for common and all tenant schemas.");
    }

    private function migrateSchema($schema, $migrationPath, $seederClass = null)
{
    $this->info("Migrating {$schema} schema");

    // Switch to the schema
    DB::statement("CREATE SCHEMA IF NOT EXISTS {$schema}");
    DB::statement("SET search_path TO {$schema}");

    // Display tables in the schema
    $tables = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = ?", [$schema]);
    $tableData = collect($tables)->map(function ($table) {
        return [$table->table_name];
    })->toArray();

    $this->info("Tables in {$schema} schema:");
    $this->table(['Table Name'], $tableData);

    // Check if the migrations table exists in the schema
    $migrationsTableExists = DB::select("
        SELECT EXISTS (
            SELECT FROM information_schema.tables 
            WHERE table_schema = ?
            AND table_name = 'migrations'
        )
    ", [$schema])[0]->exists;

    if (!$migrationsTableExists) {
        $this->info("Creating migrations table in {$schema} schema");
        DB::statement("
            CREATE TABLE {$schema}.migrations (
                id SERIAL PRIMARY KEY,
                migration VARCHAR(255) NOT NULL,
                batch INTEGER NOT NULL
            )
        ");
    } else {
        $this->info("Migrations table already exists in {$schema} schema");
    }

    if ($this->option('fresh')) {
        $this->info("Dropping all tables in {$schema} schema");
        $tables = DB::select("SELECT tablename FROM pg_tables WHERE schemaname = ?", [$schema]);
        foreach ($tables as $table) {
            if ($table->tablename !== 'migrations') {
                DB::statement("DROP TABLE IF EXISTS {$schema}.{$table->tablename} CASCADE");
            }
        }
        DB::table('migrations')->truncate();
    }

    // Create a custom migrator
    $repository = new DatabaseMigrationRepository(app('db'), 'migrations');
    $migrator = new Migrator($repository, app('db'), app('files'));

    // Run the migrations
    $this->info("Running migrations for {$schema} schema");
    $migrator->run([$migrationPath]);

    if ($this->option('seed') && $seederClass) {
        $this->info("Seeding {$schema} schema");
        Artisan::call('db:seed', ['--class' => $seederClass]);
        $this->info(Artisan::output());
    }

    $this->info("Migration completed for {$schema} schema");

    // Display final table list
    $tables = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = ?", [$schema]);
    $tableData = collect($tables)->map(function ($table) {
        return [$table->table_name];
    })->toArray();

    $this->info("Final tables in {$schema} schema:");
    $this->table(['Table Name'], $tableData);
}

    private function migrateTenantSchemas()
    {
        
        $this->migrateSchema('base_client', 'database/migrations/tenant', 'TenantDatabaseSeeder');
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            $this->migrateSchema($tenant->database, 'database/migrations/tenant', 'TenantDatabaseSeeder');
        }

        // Reset search path to public
        DB::statement("SET search_path TO public");
    }
}