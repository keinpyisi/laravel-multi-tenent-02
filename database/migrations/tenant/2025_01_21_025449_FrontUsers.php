<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Tenant\Tenant;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Schema::create('front_users', function (Blueprint $table) {
            $table->id();
            $table->string('login_id')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('user_name');
            $table->string('password');
            $table->foreignIdFor(Tenant::class)->default('0');
            $table->string('note')->nullable();
            $table->boolean('del_flag')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
