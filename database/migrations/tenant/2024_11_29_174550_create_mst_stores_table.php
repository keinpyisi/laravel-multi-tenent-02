<?php

use App\Models\Tenant\User;
use App\Models\Tenant\Mst_stores;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('r_approval_users', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->default('0');
            $table->foreignIdFor(Mst_stores::class)->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('r_approval_users');
    }
};
