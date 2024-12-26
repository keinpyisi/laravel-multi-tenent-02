<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class MaintainenceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
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
        if ($config) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'The tenant is in maintenance mode',
                    'config' => $config
                ], 503);
            }
            return response()->view('admin.layouts.maintainence', compact('config'), 503);
        }
        return $next($request);
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
