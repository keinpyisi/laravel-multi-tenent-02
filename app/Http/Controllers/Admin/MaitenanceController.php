<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Admin\Maintenance_Validation;


class MaitenanceController extends Controller {
    private Request $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function index() {
        $header_js_defines = [
            'resources/js/maitenance/index.js',
        ];
        $header_css_defines = [
            //'resources/css/clients/index.css',
        ];

        // Share the variable globally
        view()->share('header_js_defines', $header_js_defines);
        view()->share('header_css_defines', $header_css_defines);
        $settingPath = 'admins/files/_settings/';
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
        // Return the view with the paginated tenants
        return view('admin.pages.admins.maintenance.index', compact('json_data'));
    }

    public function store(Maintenance_Validation  $request) {

        try {
            // Parse and validate the maintenance term
            $termParts = explode(' to ', $request->maintenance_term);
            if (count($termParts) !== 2) {
                return back()->withErrors(['error' => ['title' => 'Invalid maintenance term format']]);
            }
            try {
                $startDate = Carbon::parse($termParts[0]);
                $endDate = Carbon::parse($termParts[1]);

                if ($startDate >= $endDate) {
                    return back()->withErrors(['error' => ['title' => 'End date must be after start date']]);
                }
            } catch (Exception $ex) {
                return redirect()->route('admin.maitenance.index')->with('error', [
                    'title' => __('lang.error_title'),
                    'text' => __('lang.error', ['attribute' => $ex->getMessage()]),
                ]);
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

            $settingPath = 'admins/files/_settings/';
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
            return redirect()->route('admin.maitenance.index')->with(
                'success',
                [
                    'title' => __('lang.success_title'),
                    'text' => __('lang.success', ['attribute' => 'メンテナンス']),
                ]
            );
        } catch (Exception $ex) {
            return redirect()->route('admin.maitenance.index')->with('error', [
                'title' => __('lang.error_title'),
                'text' => __('lang.error', ['attribute' => $ex->getMessage()]),
            ]);
        }
    }
}
