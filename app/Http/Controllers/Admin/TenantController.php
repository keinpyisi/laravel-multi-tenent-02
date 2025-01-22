<?php

namespace App\Http\Controllers\Admin;


use Exception;
use App\Models\Base\Tenant;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Tenant\Back\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Admin\Client_Validation;
use \App\Models\Tenant\Tenant as Client_Tenant;
use App\Http\Requests\Admin\Client_Edit_Validation;

class TenantController extends Controller {
    private Request $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function index() {
        $header_js_defines = [
            'resources/js/admins/index.js',
        ];
        $header_css_defines = [
            //'resources/css/clients/index.css',
        ];

        // Share the variable globally
        view()->share('header_js_defines', $header_js_defines);
        view()->share('header_css_defines', $header_css_defines);

        // Fetch active tenants and paginate
        $tenents = Tenant::activeWith()->paginate(20);
        log_message('Admin-specific log: Tenants list', $tenents);
        // Return the view with the paginated tenants
        return view('admin.pages.admins.list', compact('tenents'));
    }


    public function create() {
        $header_js_defines = [
            'resources/js/admins/create.js',
        ];
        $header_css_defines = [
            //'resources/css/clients/index.css',
        ];
        // Share the variable globally
        view()->share('header_js_defines', $header_js_defines);
        view()->share('header_css_defines', $header_css_defines);
        return view('admin.pages.admins.create');
    }

    public function store(Client_Validation $request) {
        try {
            DB::beginTransaction();

            // Merge additional data into the request
            $request->merge([
                'insert_user_id' => 1,
                'update_user_id' => 1,
                'domain' => $request->account_name,
                'database' => $request->account_name,
            ]);

            // Handle file upload for logo
            $data = $request->except(['_token', '_method']);
            $this->createCustomFolder($data['domain']);

            if ($request->hasFile('logo')) {
                try {
                    $logo = $request->file('logo');

                    // Get the original extension
                    $extension = $logo->getClientOriginalExtension(); // "png", "jpg", etc.

                    // Set the logo name
                    $logoName = 'logo.' . $extension;

                    // Define the directory structure (corrected path)
                    $tenantLogoPath = $data['domain'] . '/logo';  // No need to include 'tenants/'
                    // Store the file in the specified location
                    $logoPath = $logo->storeAs($tenantLogoPath, $logoName, 'tenant');

                    // Check if the file exists
                    if (Storage::disk('tenant')->exists($logoPath)) {
                        // Get the full path
                        $fullPath = Storage::disk('tenant')->path($logoPath);
                        log_message(['message' => 'File uploaded and exists', 'full_path' => $fullPath]);
                    } else {
                        log_message('Error uploading logo: No File');
                    }
                    $frontlogoPath = $logo->storeAs($tenantLogoPath, $logoName, 'front_tenant');

                    // Check if the file exists
                    if (Storage::disk('front_tenant')->exists($frontlogoPath)) {
                        // Get the full path
                        $fullPath = Storage::disk('front_tenant')->path($frontlogoPath);
                        log_message(['message' => 'File uploaded and exists', 'full_path' => $fullPath]);
                    } else {
                        log_message('Error uploading logo: No File');
                    }
                    

                    // Save the file path to the database or log it
                    $data['logo'] = $logoPath;
                } catch (Exception $e) {
                    log_message('Error uploading logo: ' . $e->getMessage());
                    return back()->withErrors(['error' => ['title' => 'An error occurred while uploading the logo.']]);
                }
            } else {
                log_message('Logo file upload failed for tenant: ' . $data['domain']);
            }


            // Add tenant unique key
            $data['tenent_unique_key'] = Str::uuid()->toString();  // Ensure spelling matches the database column
            
            // Create the Tenant with the validated data
            //$tenant = Tenant::create($data);
            $tenant = Tenant::create([
                'client_name'=>$data['client_name'] ?? '',
                'account_name'=>$data['account_name']?? '',
                'domain'=>$data['domain']?? '',
                'database'=>$data['database']?? '',
                'kana'=>$data['kana']?? '',
                'logo'=>$data['logo']?? '',
                'genre'=>$data['genre']?? '',
                'person_in_charge'=>$data['person_in_charge']?? '',
                'tel'=>$data['tel']?? '',
                'address'=>$data['address']?? '',
                'post_code'=>$data['post_code']?? '',
                'fax_number'=>$data['fax_number']?? '',
                'e_mail'=>$data['e_mail']?? '',
                'homepage'=>$data['homepage']?? '',
                'support_mail'=>$data['support_mail']?? '',
                'note'=>$data['note']?? '',
                'tenent_unique_key'=>$data['tenent_unique_key']?? ''
            ]);
            

            // Handle schema creation and migrations
            $this->createSchema($data['database']);
            DB::statement("SET search_path TO {$data['database']}");
            $this->runTenantMigrations($data['database']);

            DB::statement("SET search_path TO common");

            DB::commit();

            DB::beginTransaction();

            DB::statement("SET search_path TO {$data['database']}");
            $data['id'] = $tenant->id;
            $tenant = Client_Tenant::create([
                'client_name'=>$data['client_name'] ?? '',
                'account_name'=>$data['account_name']?? '',
                'domain'=>$data['domain']?? '',
                'database'=>$data['database']?? '',
                'kana'=>$data['kana']?? '',
                'logo'=>$data['logo']?? '',
                'genre'=>$data['genre']?? '',
                'person_in_charge'=>$data['person_in_charge']?? '',
                'tel'=>$data['tel']?? '',
                'address'=>$data['address']?? '',
                'post_code'=>$data['post_code']?? '',
                'fax_number'=>$data['fax_number']?? '',
                'e_mail'=>$data['e_mail']?? '',
                'homepage'=>$data['homepage']?? '',
                'support_mail'=>$data['support_mail']?? '',
                'note'=>$data['note']?? '',
                'tenent_unique_key'=>$data['tenent_unique_key']?? ''
            ]);
            User::create([
                'login_id' => $data['login_id'],
                'user_name' => $data['login_id'],
                'password' => $data['password'], // Hash the password
                'tenant_id' => $tenant->id,

            ]);
            DB::statement("SET search_path TO common");

            DB::commit();
            $randomPassword = Str::random(8);

            // Generate a bcrypt hash of the password
            $hashedPassword = password_hash($randomPassword, PASSWORD_BCRYPT);
            
            // Prepare the htpasswd line
            $htpasswdLine = "{$data['database']}:{$hashedPassword}";
            
            // Define the path to the .htpasswd file
            $htpasswdPath = '.htpasswd';
            
            // Check if the file exists
            if (!Storage::disk('tenant')->exists($htpasswdPath)) {
                // If the file doesn't exist, create it
                Storage::disk('tenant')->put($htpasswdPath, $htpasswdLine . PHP_EOL);
            } else {
                // If the file exists, append the new user data
                Storage::disk('tenant')->append($htpasswdPath, $htpasswdLine . PHP_EOL);
            }
            
            // Redirect to the tenant's index with success
            return redirect()->route('admin.tenants.index')->with(
                'success',
                [
                    'title' => __('lang.success_title'),
                    'text' => __('lang.success', ['attribute' => $data['client_name']]),
                ]
            );
        } catch (Exception $ex) {
            log_message($ex);
            log_message('Error occurred during tenant creation: ', ['exception' => $ex->getMessage()]);

            DB::rollBack();

            return redirect()->route('admin.tenants.index')->with('error', [
                'title' => __('lang.error_title'),
                'text' => __('lang.error', ['attribute' => $ex->getMessage()]),
            ]);
        } finally {
            // Always reset search path back to common in case of failure
            DB::statement("SET search_path TO common");
        }
    }


    public function show(int $id) {
        DB::statement("SET search_path TO common");

        $r = [
            "id" => $id,
        ];

        $validator = Validator::make($r, [
            "id" => ["required", "exists:tenants,id"],
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            // Return 404 if validation fails
            abort(404);
        }
        $header_js_defines = [
            'resources/js/admins/show.js',
        ];
        $header_css_defines = [
            //'resources/css/clients/index.css',
        ];

        // Share the variable globally
        view()->share('header_js_defines', $header_js_defines);
        view()->share('header_css_defines', $header_css_defines);



        $tenant = Tenant::findOrFail($id);
        $header_js_variables = [
            'tenant' => $tenant->database,
        ];
        view()->share('header_js_variables', $header_js_variables);

        DB::statement("SET search_path TO {$tenant->database}");
        $users = User::where('tenant_id', $tenant->id)->paginate(100);
        DB::statement("SET search_path TO common");
        // Specify the directory to calculate (e.g., storage directory)
        $directory = storage_path();  // You can change this to any directory you want to analyze

        // Get the total disk size, free space, and used space
        $totalSize = disk_total_space($directory);  // in bytes
        $freeSpace = disk_free_space($directory);   // in bytes
        $usedSpace = $totalSize - $freeSpace;       // Calculate used space

        // Convert bytes to gigabytes
        $totalSizeGB = $totalSize / 1073741824;  // Divide by 1024^3
        $freeSpaceGB = $freeSpace / 1073741824;
        $usedSpaceGB = $usedSpace / 1073741824;

        // Calculate usage rate
        $usageRate = ($usedSpace / $totalSize) * 100;  // Calculate usage rate in percentage

        // Format the values for display
        $totalSizeFormatted = number_format($totalSizeGB, 2) . ' GB';
        $freeSpaceFormatted = number_format($freeSpaceGB, 2) . ' GB';
        $usedSpaceFormatted = number_format($usedSpaceGB, 2) . ' GB';
        $usageRateFormatted = number_format($usageRate, 2) . '%';
        $all_usage = [
            'total_size' => $totalSizeFormatted,
            'free_space' => $freeSpaceFormatted,
            'used_space' => $usedSpaceFormatted,
            'usage_rate' => $usageRateFormatted
        ];

        $logoFolder = tenant_path($tenant->domain, 'files'); // Path to the folder containing the files

        // Retrieve all the files in the folder
        $files = File::allFiles($logoFolder);

        // Initialize variables to store total size for main files and thumbnails
        $totalSize = 0;
        $mainSize = 0;
        $thumbnailSize = 0;

        // Iterate through the files and calculate their sizes
        foreach ($files as $file) {
            $totalSize += $file->getSize();  // Total size of all files in bytes
            // Assuming that you can differentiate between main and thumbnail files
            // For example, if the file name contains 'thumb' or something similar:
            // if (str_contains($file->getFilename(), 'thumb')) {
            //     $thumbnailSize += $file->getSize();
            // } else {
            //     $mainSize += $file->getSize();
            // }
        }

        // Convert the sizes from bytes to MB and KB
        $totalSizeMB = number_format($totalSize / 1024 / 1024, 2) . ' MB'; // MB
        $client_usage = [
            'total_size' => $totalSizeMB,
        ];

        return view('admin.pages.admins.show', compact('tenant', 'users', 'all_usage', 'client_usage'));
    }


    public function edit(int $id) {
    }

    public function update(Client_Edit_Validation $request, int $id) {
        DB::beginTransaction();
        try {
            // Find the tenant or fail if not found
            $tenant = Tenant::findOrFail($id);
            $client_name = $tenant->client_name;
            // Validate the request
            $validatedData = $request->validated();
            if ($request->hasFile('logo')) {
                try {
                    $logo = $request->file('logo');

                    // Get the original extension
                    $extension = $logo->getClientOriginalExtension(); // "png", "jpg", etc.

                    // Set the logo name
                    $logoName = 'logo.' . $extension;

                    // Define the directory structure (corrected path)
                    $tenantLogoPath = $tenant->domain . '/logo';  // No need to include 'tenants/'

                    // Ensure the directory exists
                    $logoDirectory = storage_path('app/tenants/' . $tenantLogoPath);  // Ensure the full path includes 'tenants/'
                    if (!is_dir($logoDirectory)) {
                        mkdir($logoDirectory, 0775, true);  // Create directory if it doesn't exist
                    }

                    // Store the file in the specified location
                    $logoPath = $logo->storeAs($tenantLogoPath, $logoName, 'tenant');

                    // Check if the file exists
                    if (Storage::disk('tenant')->exists($logoPath)) {
                        // Get the full path
                        $fullPath = Storage::disk('tenant')->path($logoPath);
                        log_message(['message' => 'File uploaded and exists', 'full_path' => $fullPath]);
                    } else {
                        log_message('Error uploading logo: No File');
                    }
                    $frontlogoPath = $logo->storeAs($tenantLogoPath, $logoName, 'front_tenant');

                    // Check if the file exists
                    if (Storage::disk('front_tenant')->exists($frontlogoPath)) {
                        // Get the full path
                        $fullPath = Storage::disk('front_tenant')->path($frontlogoPath);
                        log_message(['message' => 'File uploaded and exists', 'full_path' => $fullPath]);
                    } else {
                        log_message('Error uploading logo: No File');
                    }

                    // Save the file path to the database or log it
                    $validatedData['logo'] = $logoPath;
                } catch (Exception $e) {
                    log_message('Error uploading logo: ' . $e->getMessage());
                    return back()->withErrors(['error' => ['title' => 'An error occurred while uploading the logo.']]);
                }
            } else {
                log_message('Logo file upload failed for tenant: ' . $client_name);
            }
            // Update the tenant with the validated data
            $tenant->update($validatedData);
            DB::statement("SET search_path TO {$tenant->database}");
            $client_tenant = Client_Tenant::findOrFail($id);
            $client_tenant->update($validatedData);
            DB::statement("SET search_path TO common");
            DB::commit();

            // Optionally, you can return a response or redirect
            return redirect()->route('admin.tenants.index')->with(
                'success',
                [
                    'title' => __('lang.success_title'),
                    'text' => __('lang.success', ['attribute' => $client_name]),
                ]
            );
        } catch (Exception $ex) {
            log_message($ex);
            log_message('Error occurred during tenant creation: ', ['exception' => $ex->getMessage()]);

            DB::rollBack();

            return redirect()->route('admin.tenants.index')->with('error', [
                'title' => __('lang.error_title'),
                'text' => __('lang.error', ['attribute' => $ex->getMessage()]),
            ]);
        } finally {
            // Always reset search path back to common in case of failure
            DB::statement("SET search_path TO common");
        }
    }


    public function destroy($id) {
        try {
            DB::beginTransaction();

            // Find the tenant by ID
            $tenant = Tenant::findOrFail($id);

            // Get the tenant's domain to remove associated files
            $tenantDomain = $tenant->domain;

            // Paths
            $tenantLogPath = "{$tenantDomain}/logo"; // storage/tenants/{client_name}/log
            $frontTenantLogoPath = "{$tenantDomain}/logo"; // storage/app/public/tenants/{client_name}/logo

            // Delete log folder from tenant disk
            if (Storage::disk('tenant')->exists($tenantLogPath)) {
                Storage::disk('tenant')->deleteDirectory($tenantLogPath);
                log_message("Deleted: storage/tenants/{$tenantDomain}/logo");
            }

            // Delete logo folder from front_tenant disk
            if (Storage::disk('front_tenant')->exists($frontTenantLogoPath)) {
                Storage::disk('front_tenant')->deleteDirectory($frontTenantLogoPath);
                log_message("Deleted: storage/app/public/tenants/{$tenantDomain}/logo");
            }
            

            // Drop tenant-specific schema and database
            $this->dropSchema($tenant->database); // Make sure this method exists to handle schema drop
            // Delete the tenant record itself
            $tenant->delete();

            // Commit the transaction
            DB::commit();

            // Redirect with success message
            return redirect()->route('admin.tenants.index')->with('success', [
                'title' => __('lang.success_title'),
                'text' => __('lang.tenant_deleted', ['tenant' => $tenant->client_name]), // Ensure this lang exists
            ]);
        } catch (Exception $ex) {
            // Log errors
            log_message('Error occurred during tenant deletion: ', ['exception' => $ex->getMessage()]);

            // Rollback transaction in case of error
            DB::rollBack();

            // Redirect with error message
            return redirect()->route('admin.tenants.index')->with('error', [
                'title' => __('lang.error_title'),
                'text' => __('lang.error', ['attribute' => $ex->getMessage()]),
            ]);
        }
    }

    public function reset_basic($domain) {
        $randomPassword = Str::random(8);
        $hashedPassword = Hash::make($randomPassword);
        $username = $domain; // Assuming the username is in $data['database']

        $htpasswdFilePath = '.htpasswd';
        $htpasswdLine = "{$username}:{$hashedPassword}";

        // Check if the file exists
        if (Storage::disk('tenant')->exists($htpasswdFilePath)) {
            // Read the existing contents of the file
            $fileContents = Storage::disk('tenant')->get($htpasswdFilePath);

            // Check if the username exists in the file
            $lines = explode(PHP_EOL, $fileContents);
            $userExists = false;

            foreach ($lines as &$line) {
                // If the username already exists, replace the line
                if (strpos($line, "{$username}:") === 0) {
                    $line = $htpasswdLine;
                    $userExists = true;
                    break;
                }
            }

            // If the username doesn't exist, add the new entry
            if (!$userExists) {
                $lines[] = $htpasswdLine;
            }

            // Save the updated contents back to the file
            Storage::disk('tenant')->put($htpasswdFilePath, implode(PHP_EOL, $lines) . PHP_EOL);
        } else {
            // If the file doesn't exist, create it with the new entry
            Storage::disk('tenant')->put($htpasswdFilePath, $htpasswdLine . PHP_EOL);
        }
        return back()->with('success', [
            'title' => __('lang.success_title'),
            'text' => __('lang.success2', ['attribute' => 'Basic認証リセット']), // Ensure this lang exists
            'basic_pass' => $randomPassword,
        ]);
    }
    protected function dropSchema($database) {
        try {
            DB::statement("DROP SCHEMA IF EXISTS {$database} CASCADE");
        } catch (Exception $ex) {
            log_message('Error while dropping schema for tenant: ' . $database, ['exception' => $ex->getMessage()]);
        }
    }


    private function createSchema($name) {
        DB::statement("CREATE SCHEMA \"$name\"");
    }

    private function runTenantMigrations($schema) {
        // Set the search path to the tenant's schema
        DB::statement("SET search_path TO \"$schema\"");

        // Run the migrations
        Artisan::call('migrate', [
            '--path' => 'database/migrations/tenant',
            '--force' => true,
        ]);

        // Reset the search path
        DB::statement("SET search_path TO public");
    }

    private function createCustomFolder($domain) {
        $customFolder = tenant_path($domain);
        $customFrontFolder = front_tenant_path($domain);

        // Create the main tenant folder if it doesn't exist
        if (!file_exists($customFolder)) {
            mkdir($customFolder, 0755, true);

            // Create additional subdirectories if needed
            mkdir(tenant_path($domain, 'files'), 0755, true);
            mkdir(tenant_path($domain, 'cache'), 0755, true);

            // Ensure the web server has write permissions
            chmod($customFolder, 0775);
        }

        // Create the main tenant folder if it doesn't exist
        if (!file_exists($customFrontFolder)) {
            mkdir($customFrontFolder, 0755, true);

            // Create additional subdirectories if needed
            mkdir(front_tenant_path($domain, 'files'), 0755, true);
            mkdir(front_tenant_path($domain, 'cache'), 0755, true);

            // Ensure the web server has write permissions
            chmod($customFrontFolder, 0775);
        }

        // Create a 'logo' subfolder inside the tenant folder
        $logoFolder = tenant_path($domain, 'logo');
        if (!file_exists($logoFolder)) {
            mkdir($logoFolder, 0755, true);
        }
         // Create a 'logo' subfolder inside the tenant folder
         $logoFolder = front_tenant_path($domain, 'logo');
         if (!file_exists($logoFolder)) {
             mkdir($logoFolder, 0755, true);
         }

        // Create a .gitignore file to prevent tenant data from being committed
        $gitignorePath = storage_path('tenants/.gitignore');
        if (!file_exists($gitignorePath)) {
            file_put_contents($gitignorePath, "*\n!.gitignore\n");
        }
         // Create a .gitignore file to prevent tenant data from being committed
         $gitignorePath = storage_path('app/public/tenants/.gitignore');
         if (!file_exists($gitignorePath)) {
             file_put_contents($gitignorePath, "*\n!.gitignore\n");
         }
    }
}
