<?php

namespace App\Models\Tenant;

use App\Models\Tenant\User;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Mst_orders extends Authenticatable {
    use Notifiable;
    protected $table = 'mst_orders';
    // Automatically set update_user_id before updating the record
    public static function boot() {
        parent::boot();
    }

    protected $fillable = [
        'order_unique_key',
        'title',
        'registered_date',
        'chirashi_sales_start_date',
        'chirashi_sales_end_date',
        'will_do_post',
        'posting_total_unit',
        'posting_delivery_start',
        'posting_delivery_end',
        'store_total_unit',
        'store_delivery_date',
        'status',
        'total_cost',
        'printing_cost',
        'posting_cost',
        'estimate_cost',
        'data_registered_end_date',
        'first_time_approval_date',
        'mst_stores_id',
        'user_id',
        'draft_flag',
        'del_flag',
    ];

    protected $casts = [
        'registered_date' => 'datetime',
        'chirashi_sales_start_date' => 'datetime',
        'chirashi_sales_end_date' => 'datetime',
        'will_do_post' => 'boolean',
        'posting_delivery_start' => 'datetime',
        'posting_delivery_end' => 'datetime',
        'store_delivery_date' => 'datetime',
        'data_registered_end_date' => 'datetime',
        'first_time_approval_date' => 'datetime',
        'draft_flag' => 'boolean',
        'del_flag' => 'boolean',
    ];

    // リレーションシップ
    public function store() {
        return $this->belongsTo(Mst_stores::class, 'mst_stores_id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    // ROrdersApprove モデルとのリレーションシップを追加
    public function approvals() {
        return $this->hasMany(R_orders_approve::class, 'mst_orders_id');
    }

    // 承認したユーザーとのリレーションシップ
    public function approvedBy() {
        return $this->belongsToMany(User::class, 'r_orders_approve', 'mst_orders_id', 'user_id')
            ->withTimestamps();
    }

    // 承認した店舗とのリレーションシップ
    public function approvedByStores() {
        return $this->belongsToMany(Mst_stores::class, 'r_orders_approve', 'mst_orders_id', 'mst_stores_id')
            ->withTimestamps();
    }

    // 注文が承認されているかどうかを確認するメソッド
    public function isApproved() {
        return $this->approvals()->exists();
    }

    // 特定のユーザーによって承認されているかどうかを確認するメソッド
    public function isApprovedBy(User $user) {
        return $this->approvals()->where('user_id', $user->id)->exists();
    }

    // 特定の店舗によって承認されているかどうかを確認するメソッド
    public function isApprovedByStore(Mst_stores $store) {
        return $this->approvals()->where('mst_stores_id', $store->id)->exists();
    }
    public function postingAreas() {
        return $this->belongsToMany(Mst_posting_areas::class, 'r_order_posting_area', 'mst_orders_id', 'mst_posting_areas_id')
            ->withPivot('unit_count')
            ->withTimestamps();
    }
}
