<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
    Schema::create('fridge_products', function (Blueprint $table) {
        $table->id();
        $table->foreignId('fridge_id')
              ->constrained('fridges')
              ->onDelete('cascade');
        $table->foreignId('product_id')
              ->constrained('products')
              ->onDelete('cascade');
        $table->integer('quantidade');
        $table->timestamps();
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('fridge_products');
    }
};
