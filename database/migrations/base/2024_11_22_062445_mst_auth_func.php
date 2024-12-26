<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        //
        Schema::create('mst_auth_func', function (Blueprint $table) {
            $table->id();
            $table->integer('auth_func_id');
            $table->string('auth_func_name')->unique();
            $table->string('auth_func_group');
            $table->string('auth_func_type');
            $table->integer('insert_user_id')->nullable();
            $table->integer('update_user_id')->nullable();
            $table->boolean('del_flag')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        //
    }
};
