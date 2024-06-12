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
        Schema::create('product_jadi_rekaps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_jadi_id')->nullable()->constrained('product_jadis')->onDelete('set null');
            $table->boolean('jenis');
            $table->integer('jumlah_kemasan');
            $table->integer('jumlah_packaging');
            $table->integer('sisa_kemasan');
            $table->foreignId('rencana_produksi_id')->nullable()->constrained('rencana_produksis')->onDelete('set null');
            $table->foreignId('invoice_jual_id')->nullable()->constrained('invoice_juals')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_jadi_rekaps');
    }
};
