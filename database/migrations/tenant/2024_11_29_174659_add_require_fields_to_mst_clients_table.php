<?php

use App\Models\Tenant\Mst_stores;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->string('theme_color')->nullable();
            $table->integer('posting_unit_price')->nullable();
            $table->integer('approval_count')->nullable();
            $table->foreignIdFor(Mst_stores::class)->default('0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
