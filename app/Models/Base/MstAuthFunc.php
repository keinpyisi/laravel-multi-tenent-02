<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MstAuthFunc extends Model {
    use HasFactory;
    protected $connection = 'pgsql';
    protected $table = 'base_tenants.mst_auth_func';

    protected $fillable = [
        'auth_func_id',
        'auth_func_name',
        'auth_func_group',
        'auth_func_type',
        'insert_user_id',
        'update_user_id',
        'del_flag',
    ];

    protected $casts = [
        'del_flag' => 'boolean',
    ];

    // If you want to use custom primary key (other than 'id')
    // protected $primaryKey = 'func_cd';
    // public $incrementing = false;
    // protected $keyType = 'string';

    // If you don't want to use timestamps
    // public $timestamps = false;
}
