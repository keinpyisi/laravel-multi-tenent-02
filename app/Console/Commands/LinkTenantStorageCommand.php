<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class LinkTenantStorageCommand extends Command
{
    protected $signature = 'tenants:link';
    protected $description = 'Create a symbolic link from public/tenants to storage/tenants';

    public function handle()
    {
        if (file_exists(public_path('tenants'))) {
            return $this->error('The "public/tenants" directory already exists.');
        }

        $this->laravel->make('files')->link(
            storage_path('tenants'), public_path('tenants')
        );

        $this->info('The [public/tenants] directory has been linked to [storage/tenants].');
    }
}