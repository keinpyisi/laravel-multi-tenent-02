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
        Schema::create('mst_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_unique_key');
            $table->integer('title');
            $table->timestamp('registered_date')->nullable();
            $table->timestamp('chirashi_sales_start_date')->nullable();
            $table->timestamp('chirashi_sales_end_date')->nullable();
            $table->boolean('will_do_post')->default(0);
            $table->integer('posting_total_unit')->nullable();
            $table->timestamp('posting_delivery_start')->nullable();
            $table->timestamp('posting_delivery_end')->nullable();
            $table->integer('store_total_unit')->nullable();
            $table->timestamp('store_delivery_date')->nullable();
            $table->string('status');
            $table->integer('total_cost')->nullable();
            $table->integer('printing_cost')->nullable();
            $table->integer('posting_cost')->nullable();
            $table->integer('estimate_cost')->nullable();
            $table->timestamp('data_registered_end_date')->nullable();
            $table->timestamp('first_time_approval_date')->nullable();
            $table->foreignIdFor(Mst_stores::class)->default('0');
            $table->foreignIdFor(User::class)->default('0');
            $table->boolean('draft_flag')->default(0);
            $table->boolean('del_flag')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('mst_orders');
    }

    // protected $casts = [
    //     'registered_date' => 'datetime',
    // ];
};
