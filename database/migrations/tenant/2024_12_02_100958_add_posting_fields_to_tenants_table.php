<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('tenants', function (Blueprint $table) {
            //
            $table->string('theme_color')->nullable();
            $table->integer('posting_unit_price')->nullable();
            $table->integer('approval_count')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('tenants', function (Blueprint $table) {
            //
        });
    }
};
