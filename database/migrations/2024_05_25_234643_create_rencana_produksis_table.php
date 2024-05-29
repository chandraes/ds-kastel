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
        Schema::create('rencana_produksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');
            $table->foreignId('kemasan_id')->nullable()->constrained('kemasans')->onDelete('set null');
            $table->foreignId('packaging_id')->nullable()->constrained('packagings')->onDelete('set null');
            $table->string('kode_produksi');
            $table->integer('nomor_produksi');
            $table->date('tanggal_produksi');
            $table->date('tanggal_expired');
            $table->integer('rencana_produksi');
            $table->integer('rencana_kemasan');
            $table->integer('rencana_packaging');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rencana_produksis');
    }
};
