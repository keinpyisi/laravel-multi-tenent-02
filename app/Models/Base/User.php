<?php

namespace App\Models\Base;

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {
    protected $connection = 'pgsql';
    protected $table = 'base_tenants.users';
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'login_id',
        'user_name',
        'auth_id',
        'insert_user_id',
        'update_user_id',
        'del_flag'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Mutator to hash password before saving
    public function setPasswordAttribute($value) {
        $this->attributes['password'] = Hash::make($value);
    }
    // In your User model
    public function getUpdatedAtAttribute($value) {
        return Carbon::parse($value)->format('Y-m-d H:i:s');
    }


    // In the User model

    public function updatedBy() {
        return $this->belongsTo($this::class, 'update_user_id');
    }
}
