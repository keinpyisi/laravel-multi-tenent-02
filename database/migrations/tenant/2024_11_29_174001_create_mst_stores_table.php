<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('mst_stores', function (Blueprint $table) {
            $table->id();
            $table->string('store_cd');
            $table->string('name');
            $table->string('area')->nullable();
            $table->integer('size')->default(0);
            $table->text('address')->nullable();
            $table->string('ph_number')->nullable();
            $table->integer('monthly_price')->default(0);
            $table->integer('store_delivery_price')->default(0);
            $table->boolean('del_flag')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('mst_stores');
    }
};
