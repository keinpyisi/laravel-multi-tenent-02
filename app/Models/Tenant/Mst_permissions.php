<?php

namespace App\Models\Tenant;


use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Mst_permissions extends Authenticatable {
    use Notifiable;
    protected $table = 'mst_permissions';
    // Automatically set update_user_id before updating the record
    public static function boot() {
        parent::boot();
    }

    protected $fillable = [];

    public function users() {
        return $this->belongsToMany(User::class, 'r_user_permissions', 'mst_permissions_id', 'user_id')
            ->withTimestamps();
    }
}
