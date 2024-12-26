<?php

namespace App\Http\Controllers\Admin\Json;


use Exception;
use App\Models\Base\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MaintainenceJson extends Controller {
    private Request $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function get_all(Request $request) {
        try {
            DB::statement("SET search_path TO base_tenants");
            $tenents = Tenant::activeWith();            // Check if 'data' is provided in the request and search both login_id and user_name
            if ($request->has('data')) {
                $searchTerm = $request->input('data');
                $tenents->where(function ($q) use ($searchTerm) {
                    $q->where('client_name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('account_name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('note', 'like', '%' . $searchTerm . '%')
                        ->orWhere('tenent_unique_key', 'like', '%' . $searchTerm . '%');
                });
            }
            // Paginate the results
            $tenents = $tenents->paginate(100);
            return json_send(JsonResponse::HTTP_OK, $tenents);
        } catch (Exception $ex) {
            log_message('Error occurred during tenent data: ', ['exception' => $ex->getMessage()]);
            return json_send(JsonResponse::HTTP_INTERNAL_SERVER_ERROR, ['error' => $ex->getMessage()]);
        } finally {
            // Always reset search path back to base_tenants in case of failure
            DB::statement("SET search_path TO base_tenants");
        }
    }
    public function get_one($tenent_name) {
        $bladePath = resource_path('views/admin/pages/admins/maintenance/model/tenent_maintainence_modal.blade.php');
        // Check if the file exists
        if (File::exists($bladePath)) {
            // Read the raw HTML content of the Blade file
            $htmlContent = View::make('admin.pages.admins.maintenance.model.tenent_maintainence_modal', [
                'errors' => session('errors') instanceof \Illuminate\Support\MessageBag
                    ? session('errors')
                    : new \Illuminate\Support\MessageBag() // Fallback to empty MessageBag if it's not set
            ])->render();

            $settingPath = $tenent_name . '/files/_settings/';
            $jsonFileName = 'maintenance.json';
            $fullJsonPath = $settingPath . $jsonFileName;

            // Check if the maintenance.json file exists and read its contents
            $json_data = [];
            if (Storage::disk('tenant')->exists($fullJsonPath)) {
                try {
                    $existingJsonContent = Storage::disk('tenant')->get($fullJsonPath);
                    $json_data = json_decode($existingJsonContent, true);

                    if (json_last_error() !== JSON_ERROR_NONE) {
                        log_message('Error decoding existing JSON: ' . json_last_error_msg());
                        $json_data = [];
                    } else {
                        log_message('Existing maintenance settings loaded successfully');
                    }
                } catch (Exception $e) {
                    log_message('Error reading existing maintenance settings: ' . $e->getMessage());
                }
            }
            // Return the rendered HTML content as a JSON response
            return json_send(JsonResponse::HTTP_OK, ['modal_html' => $htmlContent, 'data' => $json_data]);
        } else {
            return json_send(JsonResponse::HTTP_NOT_FOUND, ['error' => 'Blade file not found']);
        }
    }

    public function update(Request $request, string $tenent) {
        try {
            Log::info($request->all());
            // Parse and validate the maintenance term
            $termParts = explode(' to ', $request->maintenance_term);
            if (count($termParts) !== 2) {
                log_message('Invalid maintenance term format');
            }

            $startDate = Carbon::parse($termParts[0]);
            $endDate = Carbon::parse($termParts[1]);

            if ($startDate >= $endDate) {
                log_message('End date must be after start date: ');
            }


            // Get all request data
            $requestData = $request->all();

            // Parse the maintenance term
            $termParts = explode(' to ', $requestData['maintenance_term']);
            $startDate = Carbon::parse($termParts[0]);
            $endDate = Carbon::parse($termParts[1]);
            // Convert IP addresses to array
            $ipAddresses = array_filter(preg_split('/\r\n|\r|\n/', $requestData['allow_ip']));

            // Create the maintenance data array
            $maintenanceData = [
                'maintenance_term' => [
                    'maintanance_term_start' => $startDate->toDateTimeString(),
                    'maintanance_term_end' => $endDate->toDateTimeString()
                ],
                'allow_ip' => $ipAddresses
            ];

            // Merge the maintenance data with the request data
            $allData = array_merge($requestData, $maintenanceData);

            // Convert data to JSON
            $jsonData = json_encode($allData, JSON_PRETTY_PRINT);

            $settingPath = $tenent . '/files/_settings/';
            // Define the filename for the JSON file
            $jsonFileName = 'maintenance.json';
            // Store the JSON file
            try {
                $jsonFilePath = Storage::disk('tenant')->put($settingPath . $jsonFileName, $jsonData);

                if ($jsonFilePath) {
                    Storage::disk('tenant')->path($settingPath . $jsonFileName);
                } else {
                    log_message('Error saving maintenance settings: Storage operation failed');
                }
            } catch (Exception $e) {
                log_message('Error saving maintenance settings: ' . $e->getMessage());
            }
            return json_send(JsonResponse::HTTP_OK, $tenent);
        } catch (Exception $ex) {
            log_message('Error occurred during tenant creation: ', ['exception' => $ex->getMessage()]);
            return json_send(JsonResponse::HTTP_INTERNAL_SERVER_ERROR, ['error' => $ex->getMessage()]);
        }
    }
}
