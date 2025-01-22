<?php

namespace App\Models\Tenant\Back;

use Carbon\Carbon;
use App\Models\Tenant\Tenant;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {
    use HasFactory, Notifiable,HasRoles,HasApiTokens;
    protected $table = 'users';
    protected $guard = 'tenants';
    // Automatically set update_user_id before updating the record
    public static function boot() {
        parent::boot();

        static::updating(function ($user) {
            // Set the update_user_id to the currently authenticated user's ID
            if (isset(auth('tenants')->user()->id)) {
                $user->update_user_id = auth('tenants')->user()->id;
            }
        });
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'login_id',
        'tenant_id',
        'user_name',
        'update_user_id',
        'theme_color',
        'posting_unit_price',
        'approval_count'

    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    // Mutator to hash password before saving
    public function setPasswordAttribute($value) {
        $this->attributes['password'] = Hash::make($value);
    }
    public function getUpdatedAtAttribute($value) {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function tenant(): HasOne {
        return $this->hasOne(Tenant::class, "id", "tenant_id");
    }
}
