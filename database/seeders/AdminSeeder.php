<?php

namespace Database\Seeders;

use Exception;
use Carbon\Carbon;
use App\Models\Base\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        try {
            $data = [
                ["login_id" => 'developer', "user_name" => 'developer', "name" => 'developer', "password" => 'Eu3JQpJ44AGN'],
            ];
            DB::statement("SET search_path TO common");
            DB::beginTransaction();
            foreach ($data as $record) {
                User::create($record);
            }
            DB::commit();
        } catch (Exception $ex) {
            log_message('Error occurred during admin seeding: ', ['exception' => $ex->getMessage()]);
            DB::rollBack();
        } finally {
            // Always reset search path back to common in case of failure
            DB::statement("SET search_path TO common");
        }
    }
}
