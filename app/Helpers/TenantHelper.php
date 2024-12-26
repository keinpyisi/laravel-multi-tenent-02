<?php

use Illuminate\Support\Facades\Log;

if (!function_exists('tenant_path')) {
    /**
     * Get the path to a tenant-specific directory.
     *
     * @param string $tenantDomain
     * @param string $path
     * @return string
     */
    function tenant_path($tenantDomain, $path = '') {
        return storage_path('tenants/' . $tenantDomain . ($path ? '/' . $path : ''));
    }
}

if (!function_exists('current_tenant')) {
    /**
     * Get the current tenant.
     *
     * @return \App\Models\Base\Tenant|null
     */
    function current_tenant() {
        return app('tenant');
    }
}

if (!function_exists('tenant_asset')) {
    /**
     * Generate a tenant-specific asset path.
     *
     * @param string $path
     * @return string
     */
    function tenant_asset($path) {
        $tenant = current_tenant();
        if (!$tenant) {
            throw new \Exception('No tenant set for asset path.');
        }
        return asset('tenants/' . $tenant->domain . '/' . ltrim($path, '/'));
    }
}

if (!function_exists('log_message')) {
    /**
     * Log with detailed trace including file, line, and function.
     *
     * @param string $message The log message
     * @param array $data Additional data to log
     * @param string $channel The log channel (e.g., 'tenant', 'stack', etc.)
     * @param array $context Additional log context
     */
    function log_message($message, $data = [], $channel = 'tenant', array $context = []) {
        if (is_array($message)) {
            $data = $message['data'] ?? [];
            $context = $message['context'] ?? [];
            $message = $message['message'] ?? 'No message provided';
        }

        // Get a more extensive backtrace
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10);

        // Find the first non-vendor call
        $caller = null;
        foreach ($trace as $t) {
            if (!isset($t['file']) || strpos($t['file'], '/vendor/') === false) {
                $caller = $t;
                break;
            }
        }

        // Add trace details to the log context
        $context['file'] = $caller['file'] ?? 'N/A';
        $context['line'] = $caller['line'] ?? 'N/A';
        $context['function'] = $caller['function'] ?? 'N/A';

        // Format the log message
        $formattedMessage = formatLogMessage($message, $data, $context);

        // Log to the specified channel with the formatted message
        try {
            Log::channel($channel)->info($formattedMessage);
        } catch (\Exception $e) {
            // If the specified channel doesn't exist, fall back to the default channel
            Log::info($formattedMessage);
        }
    }

    /**
     * Format the log message with proper indentation and line breaks.
     *
     * @param string $message
     * @param array $data
     * @param array $context
     * @return string
     */
    function formatLogMessage($message, $data, $context) {
        $formattedMessage = "[Log Message]\n" . $message . "\n";

        if (!empty($data)) {
            $formattedMessage .= "[Data]\n" . json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
        }

        $formattedMessage .= "[Trace]\n";
        $formattedMessage .= "File: " . $context['file'] . "\n";
        $formattedMessage .= "Line: " . $context['line'] . "\n";
        $formattedMessage .= "Function: " . $context['function'] . "\n";

        return $formattedMessage;
    }
}

if (!function_exists('json_send')) {
    /**
     * Method api_send
     * Making the Standard API JSON Format
     * @param $status $status [200,300,404]
     * @param $data $data [API Data to Sent]
     *
     * @return JsonResponse
     */
    function json_send($status, $data, $type = 'success') {
        $response = response()->json(["status" => $status, "type" => $type, "data" => $data], $status, [], JSON_INVALID_UTF8_IGNORE);
        return $response;
    }
}
