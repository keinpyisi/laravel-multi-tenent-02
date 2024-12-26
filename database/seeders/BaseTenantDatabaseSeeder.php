<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BaseTenantDatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        //
        $this->call([
            MstAuthFuncSeeder::class,
            AdminSeeder::class,
            // Add more seeders as needed
        ]);
    }
}
