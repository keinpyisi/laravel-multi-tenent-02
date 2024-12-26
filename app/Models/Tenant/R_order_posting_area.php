<?php

namespace App\Models\Tenant;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class R_order_posting_area extends Authenticatable {
    use Notifiable;
    protected $table = 'r_order_posting_area';
    // Automatically set update_user_id before updating the record
    public static function boot() {
        parent::boot();
    }

    protected $fillable = [
        'unit_count',
        'mst_orders_id',
        'mst_posting_areas_id',
    ];

    /**
     * 関連する注文を取得
     */
    public function order() {
        return $this->belongsTo(Mst_orders::class, 'mst_orders_id');
    }

    /**
     * 関連するポスティングエリアを取得
     */
    public function postingArea() {
        return $this->belongsTo(Mst_posting_areas::class, 'mst_posting_areas_id');
    }
}
