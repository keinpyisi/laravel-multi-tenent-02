<?php

namespace App\Models\Tenant;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Mst_stores extends Authenticatable {
    use Notifiable;
    protected $table = 'mst_stores';
    // Automatically set update_user_id before updating the record
    public static function boot() {
        parent::boot();
    }

    protected $fillable = [
        'store_cd',
        'name',
        'area',
        'size',
        'address',
        'ph_number',
        'monthly_price',
        'del_flag',
    ];

    public function orders() {
        return $this->hasMany(Mst_orders::class, 'mst_stores_id');
    }

    // ROrdersApprove モデルとのリレーションシップを追加
    public function orderApprovals() {
        return $this->hasMany(R_orders_approve::class, 'mst_stores_id');
    }

    // 承認された注文とのリレーションシップ
    public function approvedOrders() {
        return $this->belongsToMany(Mst_orders::class, 'r_orders_approve', 'mst_stores_id', 'mst_orders_id')
            ->withTimestamps();
    }

    // 承認を行ったユーザーとのリレーションシップ
    public function approvingUsers() {
        return $this->belongsToMany(User::class, 'r_orders_approve', 'mst_stores_id', 'user_id')
            ->withTimestamps();
    }

    // 店舗が特定の注文を承認しているかどうかを確認するメソッド
    public function hasApprovedOrder(Mst_orders $order) {
        return $this->orderApprovals()->where('mst_orders_id', $order->id)->exists();
    }

    // 店舗の承認済み注文の数を取得するメソッド
    public function approvedOrdersCount() {
        return $this->orderApprovals()->count();
    }

    // 特定のユーザーによる店舗の承認を取得するメソッド
    public function approvalsByUser(User $user) {
        return $this->orderApprovals()->where('user_id', $user->id)->get();
    }
}
