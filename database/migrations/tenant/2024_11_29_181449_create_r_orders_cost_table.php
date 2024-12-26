<?php

use App\Models\Tenant\Mst_orders;
use App\Models\Tenant\Mst_stores;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('r_orders_cost', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Mst_orders::class)->default('0');
            $table->foreignIdFor(Mst_stores::class)->default('0');
            $table->integer('month')->nullable();
            $table->integer('now_cost')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('r_orders_cost');
    }
};
