<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\Base\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SetApiTenantFromPath {
    public function handle($request, Closure $next) {
         // Basic Auth check
         if (!$request->session()->isStarted()) {
            $request->session()->start();
        }
         if (!$this->checkBasicAuth($request)) {
            return response('Unauthorized', 401)
                ->header('WWW-Authenticate', 'Basic realm="Restricted Area"');
        }

        $config = $this->isInMaintenanceMode($request);

        // Maintenance mode check
        if ($config) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'The application is in maintenance mode',
                    'config' => $config
                ], 503);
            }
            return response()->view('admin.layouts.maintainence', compact('config'), 503);
        }

        $config = $this->isInTenentMaintenanceMode($request);

        // Tenant maintenance mode check
        if ($config) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'The tenant is in maintenance mode',
                    'config' => $config
                ], 503);
            }
            return response()->view('admin.layouts.maintainence', compact('config'), 503);
        }

        $path = $request->path();
        $segments = explode('/', $path);
        if (count($segments) >= 2 && $segments[1] === 'backend') {
            if ($segments[2] === 'admin') {
                $tenantLogPath = storage_path('logs/admins/');
                config(['logging.channels.tenant' => [
                    'driver' => 'daily',
                    'path' => $tenantLogPath . '/laravel.log',
                    'level' => 'debug',
                    'days' => 14, // Keep logs for 14 days
                ]]);
                // Admin routes
                DB::statement("SET search_path TO base_tenants");
            } else {
                // Tenant routes
                $tenantSlug = $segments[2];
                $tenant = Tenant::where('domain', $tenantSlug)->first();

                if ($tenant) {
                    // Set the tenant in the app container
                    app()->instance('tenant', $tenant);

                    // Set the database connection to use only the tenant's schema
                    DB::statement("SET search_path TO {$tenant->database}");
                    config(['database.connections.tenant.search_path' => $tenant->database]);
                    DB::purge('tenant');
                    DB::reconnect('tenant');
                    // Set custom daily log path for the tenant
                    $tenantLogPath = storage_path('logs/tenants/' . $tenantSlug);
                    config(['logging.channels.tenant' => [
                        'driver' => 'daily',
                        'path' => $tenantLogPath . '/laravel.log',
                        'level' => 'debug',
                        'days' => 14, // Keep logs for 14 days
                    ]]);
                } else {
                    abort(404);
                }
            }
        } else {
            // For routes not starting with 'backend', set to public schema
            DB::statement("SET search_path TO public");
        }
        

        return $next($request);
    }
    private function checkBasicAuth(Request $request) {
        if (!Storage::disk('tenant')->exists('.htpasswd')) {
            return true;
            // Process the file contents as needed
        }
        $authHeader = $request->header('Authorization');

        if (empty($authHeader)) {
            return false;
        }

        // The "Basic" keyword and the base64-encoded credentials
        list($type, $credentials) = explode(' ', $authHeader, 2);

        if (strtolower($type) != 'basic') {
            return false;
        }

        // Decode base64 credentials
        $decodedCredentials = base64_decode($credentials);
        list($username, $password) = explode(':', $decodedCredentials, 2);

        // Read the .htpasswd file from the storage directory
        if (Storage::disk('tenant')->exists('.htpasswd')) {
            $htpasswdContents = Storage::disk('tenant')->get('.htpasswd');

            // Parse the .htpasswd file
            $lines = explode("\n", $htpasswdContents);
            foreach ($lines as $line) {
                if (empty($line)) continue;
                // Split the line into username and password hash
                list($storedUsername, $storedPassword) = explode(':', $line, 2);

                // Check if username matches and password matches the hash
                if ($username === $storedUsername && $this->verifyPassword($password, $storedPassword)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function verifyPassword($password, $storedPassword) {
        // Check if the password matches the stored hash (for htpasswd)
        // Laravel doesn't have a built-in method for htpasswd hash verification,
        // so we use an external package or a custom solution to verify the password.

        // For example, use an Apache htpasswd password hash verification method:
        if (Hash::check($password, $storedPassword)) {
            return true;
        }

        return false;
    }

    private function isInMaintenanceMode(Request $request) {
        $settingPath = 'admins/files/_settings/';
        $jsonFileName = 'maintenance.json';
        $fullJsonPath = $settingPath . $jsonFileName;

        // Check if the maintenance.json file exists and read its contents
        if (!Storage::disk('tenant')->exists($fullJsonPath)) {
            return false;
        }

        try {
            $existingJsonContent = Storage::disk('tenant')->get($fullJsonPath);
            $config = json_decode($existingJsonContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Error decoding existing JSON: ' . json_last_error_msg());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Error reading existing maintenance settings: ' . $e->getMessage());
            return false;
        }
        // Check if maintenance mode is on
        if ($config['maintenance_0'] === 'on' || ($config['maintenance_0'] === 'scheduled' && $this->isInMaintenancePeriod($config['maintenance_term']))) {

            $path = $request->path();
            $segments = explode('/', $path);
            $url = $segments[0];
            // Check if the current site is targeted for maintenance
            if ($url == $config['back_site']) {

                // Check if the user's IP is in the allowed list
                $userIp = $request->ip();
                if (!in_array($userIp, $config['allow_ip'])) {
                    $config['text'] = $this->formatMaintenanceMessage($config['back_main_message'], $config['maintenance_term']);
                    return $config;
                }
            } else if ($url == $config['front_site']) {

                // Check if the user's IP is in the allowed list
                $userIp = $request->ip();
                if (!in_array($userIp, $config['allow_ip'])) {
                    $config['text'] = $this->formatMaintenanceMessage($config['front_main_message'], $config['maintenance_term']);
                    return $config;
                }
            }
        }

        return false;
    }


    private function isInTenentMaintenanceMode(Request $request) {
        $path = $request->path();
        $segments = explode('/', $path);
        // Tenant routes
        $tenantSlug = $segments[1];
        $settingPath = $tenantSlug . '/files/_settings/';
        $jsonFileName = 'maintenance.json';
        $fullJsonPath = $settingPath . $jsonFileName;

        // Check if the maintenance.json file exists and read its contents
        if (!Storage::disk('tenant')->exists($fullJsonPath)) {
            return false;
        }

        try {
            $existingJsonContent = Storage::disk('tenant')->get($fullJsonPath);
            $config = json_decode($existingJsonContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Error decoding existing JSON: ' . json_last_error_msg());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Error reading existing maintenance settings: ' . $e->getMessage());
            return false;
        }
        // Check if maintenance mode is on
        if ($config['maintenance_0'] === 'on' || ($config['maintenance_0'] === 'scheduled' && $this->isInMaintenancePeriod($config['maintenance_term']))) {
            // Check if the current site is targeted for maintenance
            $userIp = $request->ip();
            if (!in_array($userIp, $config['allow_ip'])) {
                $path = $request->path();
                $segments = explode('/', $path);
                if ($segments[0] == 'backend') {
                    $config['text'] = $this->formatMaintenanceMessage($config['back_main_message'], $config['maintenance_term']);
                } else {
                    $config['text'] = $this->formatMaintenanceMessage($config['front_main_message'], $config['maintenance_term']);
                }
                return $config;
            }
        }
        return false;
    }

    private function isInMaintenancePeriod($term) {
        if (empty($term)) {
            return false;
        }
        $now = Carbon::now();
        $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $term['maintanance_term_start']);
        $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $term['maintanance_term_end']);

        return $now->between($startDate, $endDate);
    }
    private function formatMaintenanceMessage($message, $term) {
        return preg_replace_callback('/\{(\w+)\|([^}]+)\}/', function ($matches) use ($term) {
            $key = $matches[1]; // e.g., "start" or "end"
            $format = $matches[2]; // e.g., "Y/m/d (w)"

            if (isset($term[$key])) {
                $date = Carbon::createFromFormat('Y-m-d H:i:s', $term[$key]);

                // Check for "w" in the format
                if (strpos($format, 'w') !== false) {
                    // Replace "w" with the mapped day name
                    $dayOfWeek = $this->mapDayOfWeek($date->format('N'));
                    $format = str_replace('w', $dayOfWeek, $format);
                }

                // Format the date dynamically
                return $date->format($format);
            }

            return $matches[0]; // Return the placeholder if key doesn't exist
        }, $message);
    }
    private function mapDayOfWeek($dayNumber) {
        $days = [
            1 => '月',
            2 => '火',
            3 => '水',
            4 => '木',
            5 => '金',
            6 => '土',
            7 => '日',
        ];
        return $days[$dayNumber] ?? 'Unknown';
    }
}
