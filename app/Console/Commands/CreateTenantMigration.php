<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Composer;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Migrations\MigrationCreator;

class CreateTenantMigration extends Command {

    // php artisan make:tenant-migration create_posts_table
    // This will create a new migration file in the database/migrations/tenant directory.

    // If you want to create a migration for a specific table, you can use:

    // php artisan make:tenant-migration create_posts_table --create=posts
    // Or to update an existing table:

    // php artisan make:tenant-migration add_status_to_posts_table --table=posts

    //To Add Table in Base Admin:
    //php artisan make:migration create_example_table --path=database/migrations/base
    protected $signature = 'make:tenant-migration {name : The name of the migration}
                            {--create= : The table to be created}
                            {--table= : The table to be updated}';

    protected $description = 'Create a new migration file for tenants';

    protected $creator;
    protected $composer;

    public function __construct(Composer $composer) {
        parent::__construct();

        $this->composer = $composer;
    }

    public function handle() {
        $name = $this->argument('name');
        $table = $this->option('table');
        $create = $this->option('create');

        $path = database_path('migrations/tenant');

        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true);
        }

        $this->creator = new MigrationCreator(File::getFacadeRoot(), __DIR__ . '/stubs');

        $file = $this->creator->create(
            $name,
            $path,
            $table,
            $create
        );

        $this->info("Created Migration: {$file}");

        $this->composer->dumpAutoloads();
    }
}
