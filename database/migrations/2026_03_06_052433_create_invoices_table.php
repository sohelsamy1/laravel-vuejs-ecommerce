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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->decimal('total', 10, 2);
            $table->decimal('vat', 10, 2);
            $table->decimal('payable', 10, 2);
            $table->text('cus_details');
            $table->text('ship_details');
            $table->string('tran_id', 100)->unique();
            $table->string('val_id', 100)->nullable();
            $table->enum('delivery_status',['Pending','Processing','Completed']);
            $table->enum('payment_status', ['Pending', 'Paid', 'Failed']);

            $table->unsignedBigInteger('user_id');
             $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->restrictOnDelete()
                  ->cascadeOnUpdate();

              $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
