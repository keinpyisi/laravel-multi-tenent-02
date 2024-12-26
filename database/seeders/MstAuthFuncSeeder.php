<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\Base\MstAuthFunc;
use Illuminate\Support\Facades\DB;

class MstAuthFuncSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $data = [
            ["0", "auth_admin", "admin", "2"],
            ["1000", "auth_setting", "setting", "3"],
            ["1010", "auth_setting_client", "setting", "3"],
            ["1020", "auth_setting_user", "setting", "3"],
            ["1030", "auth_setting_auth", "setting", "3"],
            ["1040", "auth_setting_word", "setting", "3"],
            ["1050", "auth_setting_config", "setting", "3"],
            ["1100", "auth_info", "info", "3"],
            ["1110", "auth_info_help", "info", "3"],
            ["1120", "auth_info_message", "info", "3"],
        ];

        foreach ($data as $record) {
            MstAuthFunc::create([
                'auth_func_id' => $record[0],
                'auth_func_name' => $record[1],
                'auth_func_group' => $record[2],
                'auth_func_type' => $record[3],
            ]);
        }
    }
}
