<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('tenent_unique_key');
            $table->string('client_name');
            $table->string('account_name');
            $table->string('domain')->unique();
            $table->string('database')->unique();
            $table->string('kana')->nullable();
            $table->string('genre')->nullable();
            $table->string('person_in_charge')->nullable();
            $table->string('tel')->nullable();
            $table->string('address')->nullable();
            $table->string('post_code')->nullable();
            $table->string('fax_number')->nullable();
            $table->string('e_mail')->nullable();
            $table->string('homepage')->nullable();
            $table->string('logo')->nullable();
            $table->string('support_mail')->nullable();
            $table->string('note')->nullable();
            $table->integer('insert_user_id')->nullable();
            $table->integer('update_user_id')->nullable();
            $table->boolean('del_flag')->default(false);
            $table->timestamps();
        });
        //
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        //
    }
};
