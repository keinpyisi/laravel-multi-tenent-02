<?php

namespace App\Models\Tenant;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class R_user_permissions extends Authenticatable {
    use Notifiable;
    protected $table = 'r_user_permissions';
    // Automatically set update_user_id before updating the record
    public static function boot() {
        parent::boot();
    }

    protected $fillable = [
        'mst_permissions_id',
        'user_id',
    ];

    // MstPermission モデルとのリレーション
    public function permission() {
        return $this->belongsTo(Mst_permissions::class, 'mst_permissions_id');
    }

    // User モデルとのリレーション
    public function user() {
        return $this->belongsTo(User::class);
    }
}
