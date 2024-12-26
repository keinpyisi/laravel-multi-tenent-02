<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class LogHelper {
    public static function info($message, $context = []) {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $caller = $trace[1] ?? null;

        if ($caller) {
            $file = $caller['file'] ?? 'unknown';
            $line = $caller['line'] ?? 'unknown';

            $context['_file'] = $file;
            $context['_line'] = $line;

            if (file_exists($file)) {
                $content = file($file);
                $context['_code'] = trim($content[$line - 1] ?? '');
            }
        }

        Log::info($message, $context);
    }

    // You can add similar methods for debug, warning, error, etc.
}
