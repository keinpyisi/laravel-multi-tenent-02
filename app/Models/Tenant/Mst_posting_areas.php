<?php

namespace App\Models\Tenant;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Mst_posting_areas extends Authenticatable {
    use Notifiable;
    protected $table = 'mst_posting_areas';
    // Automatically set update_user_id before updating the record
    public static function boot() {
        parent::boot();
    }

    protected $fillable = [
        'area_name',
        'unit_count',
    ];

    public function orders() {
        return $this->belongsToMany(Mst_orders::class, 'r_order_posting_area', 'mst_posting_areas_id', 'mst_orders_id')
            ->withPivot('unit_count')
            ->withTimestamps();
    }
}
