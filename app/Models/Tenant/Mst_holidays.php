<?php

namespace App\Models\Tenant;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Mst_holidays extends Authenticatable {
    use Notifiable;
    protected $table = 'mst_holidays';
    // Automatically set update_user_id before updating the record
    public static function boot() {
        parent::boot();
    }

    protected $fillable = [
        'name',
        'month',
        'day',
    ];
}
