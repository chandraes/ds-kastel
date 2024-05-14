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
        Schema::dropIfExists('products');
        
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->string('nama');
            $table->foreignId('kategori_product_id')->constrained()->onDelete('cascade');
            $table->float('konversi_liter');
            $table->integer('konversi_dus');
            $table->integer('stok')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
