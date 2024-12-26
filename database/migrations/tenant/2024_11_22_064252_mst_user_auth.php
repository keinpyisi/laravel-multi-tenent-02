<?php


use App\Models\Base\MstAuthFunc;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        //
        Schema::create('mst_user_auth', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(MstAuthFunc::class)->default('0');
            $table->integer('auth_type');
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
