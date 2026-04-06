<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
    Schema::create('geladeira_produto', function (Blueprint $table) {
        $table->id();
        $table->foreignId('geladeira_id')
              ->constrained('geladeiras')
              ->onDelete('cascade');
        $table->foreignId('produto_id')
              ->constrained('food_table')
              ->onDelete('cascade');
        $table->integer('quantidade');
        $table->timestamps();
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('geladeira_produto');
    }
};
