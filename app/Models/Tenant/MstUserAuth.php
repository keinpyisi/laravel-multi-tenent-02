<?php

namespace App\Models\Tenant;

use App\Models\Base\MstAuthFunc;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MstUserAuth extends Model {
    use HasFactory;

    protected $table = 'mst_user_auth';
    public static function boot() {
        parent::boot();

        static::updating(function ($auth) {
            // Set the update_user_id to the currently authenticated user's ID
            if (isset(auth('tenants')->user()->id)) {
                $auth->update_user_id = auth('tenants')->user()->id;
            }
        });
    }

    protected $fillable = [
        'mst_auth_func_id',
        'auth_type',
        'insert_user_id',
        'update_user_id',
        'del_flag',
    ];

    protected $casts = [
        'mst_auth_func_id' => 'integer',
        'auth_type' => 'integer',
        'insert_user_id' => 'integer',
        'update_user_id' => 'integer',
        'del_flag' => 'boolean',
    ];

    /**
     * Get the auth function that owns the user auth.
     */
    public function authFunction(): BelongsTo {
        return $this->belongsTo(MstAuthFunc::class, 'mst_auth_func_id');
    }

    /**
     * Scope a query to only include non-deleted records.
     */
    public function scopeActive($query) {
        return $query->where('del_flag', false);
    }
}
