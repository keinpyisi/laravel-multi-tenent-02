<?php

namespace App\Models\Base;

use Exception;
use App\Models\Tenant\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tenant extends Model {
    protected $connection = 'pgsql';
    protected $table = 'base_tenants.tenants';

    const DELETED = 1;
    const ACTIVE = 0;
    protected $fillable = [
        'client_name',
        'account_name',
        'domain',
        'database',
        'kana',
        'logo',
        'genre',
        'person_in_charge',
        'tel',
        'address',
        'post_code',
        'fax_number',
        'e_mail',
        'homepage',
        'support_mail',
        'note',
        'insert_user_id',
        'update_user_id',
        'del_flag',
        'tenent_unique_key'
    ];

    protected $casts = [
        'insert_user_id' => 'integer',
        'update_user_id' => 'integer',
        'del_flag' => 'boolean',
    ];
    protected $appends = ['maintenance_settings'];  // Ensure this is added

    public static function boot() {
        parent::boot();

        static::updating(function ($user) {
            // Set the update_user_id to the currently authenticated user's ID
            if (isset(auth('admin')->user()->id)) {
                $user->update_user_id = auth('admin')->user()->id;
            }
        });
        static::creating(function ($user) {
            // Set the update_user_id to the currently authenticated user's ID
            if (isset(auth('admin')->user()->id)) {
                $user->insert_user_id = auth('admin')->user()->id;
            }
        });
    }

    // If you want to use created_at and updated_at
    public $timestamps = true;
    public function tenent_users(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function scopeActiveWith(Builder $query): Builder {
        return $query->where('del_flag', $this::ACTIVE);
    }

    public function getMaintenanceSettingsAttribute() {
        $tenantSlug = $this->database;  // Assuming `slug` is a property in your Tenant model
        $settingPath = $tenantSlug . '/files/_settings/';
        $jsonFileName = 'maintenance.json';
        $fullJsonPath = $settingPath . $jsonFileName;

        // Check if the maintenance.json file exists and read its contents
        if (!Storage::disk('tenant')->exists($fullJsonPath)) {
            return null;  // Return null if the file doesn't exist
        }

        try {
            $existingJsonContent = Storage::disk('tenant')->get($fullJsonPath);
            $config = json_decode($existingJsonContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Error decoding existing JSON: ' . json_last_error_msg());
                return null;
            }

            return $config;  // Return the decoded JSON content
        } catch (Exception $e) {
            Log::error('Error reading existing maintenance settings: ' . $e->getMessage());
            return null;
        }
    }
    // If you're using UUID, uncomment the following line
    // use Illuminate\Database\Eloquent\Concerns\HasUuids;
}
