<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->decimal('rating', 2, 1);

            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('product_id');

            $table->unique(['customer_id', 'product_id']);

            $table->foreign('customer_id')
                  ->references('id')
                  ->on('customer_profiles')
                  ->restrictOnDelete()
                  ->cascadeOnUpdate();

            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->restrictOnDelete()
                  ->cascadeOnUpdate();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};
