<?php

use App\Models\Tenant\Mst_orders;
use Illuminate\Support\Facades\Schema;
use App\Models\Tenant\Mst_posting_areas;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('r_order_posting_area', function (Blueprint $table) {
            $table->id();
            $table->integer('unit_count');
            $table->foreignIdFor(Mst_orders::class)->default('0');
            $table->foreignIdFor(Mst_posting_areas::class)->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('r_order_posting_area');
    }
};
