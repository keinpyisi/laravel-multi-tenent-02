<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class LinkFrontTenantStorageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants:front_link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a symbolic link from public/tenants to storage/app/public/tenants';


    // Handle the command execution logic
    public function handle()
    {
        $publicTenantsPath = public_path('tenants');
        $storageTenantsPath = storage_path('app/public/tenants');

        // Check if the symbolic link already exists
        if (file_exists($publicTenantsPath)) {
            return $this->error('The "public/tenants" directory already exists.');
        }

        // Create the symbolic link using Laravel's file system service
        $this->laravel->make('files')->link(
            $storageTenantsPath, $publicTenantsPath
        );

        // Display success message
        $this->info('The [public/tenants] directory has been linked to [storage/app/public/tenants].');
    }
}
