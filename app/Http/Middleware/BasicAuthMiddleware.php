<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class BasicAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         // Basic Auth check
        if (!$this->checkBasicAuth($request)) {
            return response('Unauthorized', 401)->header('WWW-Authenticate', 'Basic realm="Restricted Area"');
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
}
