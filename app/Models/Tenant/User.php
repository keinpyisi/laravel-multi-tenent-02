<?php

namespace App\Models\Tenant;

use Carbon\Carbon;
use App\Models\Tenant\Tenant;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {
    use Notifiable;
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
        'mst_user_auth_id',
        'user_name',
        'update_user_id',
        'theme_color',
        'posting_unit_price',
        'approval_count',
        'mst_stores_id'

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
    public function store(): HasOne {
        return $this->hasOne(Mst_stores::class, "id", "mst_stores_id");
    }
    public function orders() {
        return $this->hasMany(Mst_orders::class, 'user_id');
    }

    // ROrdersApprove モデルとのリレーションシップを追加
    public function orderApprovals() {
        return $this->hasMany(R_orders_approve::class);
    }

    // 承認した注文とのリレーションシップ
    public function approvedOrders() {
        return $this->belongsToMany(Mst_orders::class, 'r_orders_approve', 'user_id', 'mst_orders_id')
            ->withTimestamps();
    }

    // 承認を行った店舗とのリレーションシップ
    public function approvedStores() {
        return $this->belongsToMany(Mst_stores::class, 'r_orders_approve', 'user_id', 'mst_stores_id')
            ->withTimestamps();
    }

    // ユーザーが特定の注文を承認しているかどうかを確認するメソッド
    public function hasApprovedOrder(Mst_orders $order) {
        return $this->orderApprovals()->where('mst_orders_id', $order->id)->exists();
    }

    // ユーザーが特定の店舗の注文を承認しているかどうかを確認するメソッド
    public function hasApprovedStoreOrder(Mst_stores $store) {
        return $this->orderApprovals()->where('mst_stores_id', $store->id)->exists();
    }

    // ユーザーの承認済み注文の数を取得するメソッド
    public function approvedOrdersCount() {
        return $this->orderApprovals()->count();
    }

    // 特定の期間内のユーザーの承認を取得するメソッド
    public function approvalsBetween($startDate, $endDate) {
        return $this->orderApprovals()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
    }

    public function permissions() {
        return $this->belongsToMany(Mst_permissions::class, 'r_user_permissions', 'user_id', 'mst_permissions_id')
            ->withTimestamps();
    }

    public function hasPermission($permissionId) {
        return $this->permissions()->where('mst_permissions.id', $permissionId)->exists();
    }
}
