<?php

namespace App\Models\Tenant;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class R_orders_approve extends Authenticatable {
    use Notifiable;
    protected $table = 'r_orders_approve';
    // Automatically set update_user_id before updating the record
    public static function boot() {
        parent::boot();
    }

    protected $fillable = [
        'user_id',
        'mst_stores_id',
        'mst_orders_id',
    ];

    // User モデルとのリレーション
    public function user() {
        return $this->belongsTo(User::class);
    }

    // MstStore モデルとのリレーション
    public function store() {
        return $this->belongsTo(Mst_stores::class, 'mst_stores_id');
    }

    // MstOrder モデルとのリレーション
    public function order() {
        return $this->belongsTo(Mst_orders::class, 'mst_orders_id');
    }
}
