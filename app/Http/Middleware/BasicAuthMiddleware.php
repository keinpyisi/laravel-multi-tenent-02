<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        if(env('BASIC_AUTH')==true){
             // Basic Auth check
            if (!$this->checkBasicAuth($request)) {
                return response('Unauthorized', 401)->header('WWW-Authenticate', 'Basic realm="Restricted Area"');
            }
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
                $line = trim($line); // Trim any leading/trailing spaces
                //dd(explode(':', $line, 2));
                if (strpos($line, ':') !== false) {
                    list($storedUsername, $storedPassword) = explode(':', $line, 2);
                } else {
                    // Handle error or log the malformed line
                    // Example: Log or throw an error if the line format is incorrect
                    Log::error('Malformed authentication line: ' . $line);
                    dd($line);
                } 
                // Check if username matches and password matches the hash
                if ($username === $storedUsername && $this->verifyPassword($password, $storedPassword)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function verifyPassword($password, $storedPassword) {
       
        // If the stored password is bcrypt (common in htpasswd), verify using Hash::check
        if (strpos($storedPassword, '$2y$') === 0) {
            // Bcrypt hash check
            return Hash::check($password, $storedPassword);
        }
    
        // If the stored password uses crypt (e.g., $1$ for MD5-based hashing)
        if (strpos($storedPassword, '$1$') === 0) {
            // Use crypt function for MD5-based hashes (if you're using it)
            return crypt($password, $storedPassword) === $storedPassword;
        }
    
        // If the stored password is a plain text password (not hashed)
        if (empty($storedPassword) || strpos($storedPassword, '$') === false) {
            return $password === $storedPassword;
        }
    
        return false;  // Default to false for unsupported hash formats
    }
    
}
