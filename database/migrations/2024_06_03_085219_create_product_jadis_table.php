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
        Schema::create('product_jadis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');
            $table->foreignId('kemasan_id')->nullable()->constrained('kemasans')->onDelete('set null');
            $table->unique(['product_id', 'kemasan_id'], 'product_kemasan_unique');
            $table->integer('stock_kemasan')->default(0);
            $table->integer('stock_packaging')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_jadis');
    }
};
