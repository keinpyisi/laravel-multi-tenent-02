<?php

namespace App\Models\Tenant;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;

class R_approval_users extends Authenticatable {
    use Notifiable;
    protected $table = 'r_approval_users';
    // Automatically set update_user_id before updating the record
    public static function boot() {
        parent::boot();
    }

    protected $fillable = [
        'user_id',
        'mst_stores_id'
    ];

    public function user(): HasOne {
        return $this->hasOne(User::class, "id", "user_id");
    }
    public function store(): HasOne {
        return $this->hasOne(Mst_stores::class, "id", "mst_stores_id");
    }
}
