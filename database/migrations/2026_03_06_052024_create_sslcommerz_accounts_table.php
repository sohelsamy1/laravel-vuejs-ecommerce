<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sslcommerz_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('store_id');
            $table->text('store_passwd');
            $table->string('currency');
            $table->text('success_url');
            $table->text('fail_url');
            $table->text('cancel_url');
            $table->text('ipn_url');
            $table->text('init_url');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sslcommerz_accounts');
    }
};
