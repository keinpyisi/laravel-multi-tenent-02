<?php

namespace App\Models\Tenant;

use Exception;
use App\Models\Tenant\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tenant extends Model {
    protected $table = 'tenants';
    const DELETED = 1;
    const ACTIVE = 0;
    protected $fillable = [
        'id',
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
        'tenent_unique_key',
        'theme_color',
        'posting_unit_price',
        'approval_count'
    ];

    protected $casts = [
        'insert_user_id' => 'integer',
        'update_user_id' => 'integer',
        'del_flag' => 'boolean',
    ];
    protected $appends = ['maintenance_settings'];  // Ensure this is added

    // If you want to use created_at and updated_at
    public $timestamps = true;
    // Relationship to users
    public function tenantUsers(): HasMany {
        return $this->hasMany(User::class, 'tenant_id');
    }

    // Scope to get active tenants
    public function scopeActiveWith(Builder $query): Builder {
        return $query->where('del_flag', self::ACTIVE);
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
