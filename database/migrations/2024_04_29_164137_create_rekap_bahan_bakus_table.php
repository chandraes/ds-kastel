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
        Schema::create('rekap_bahan_bakus', function (Blueprint $table) {
            $table->id();
            $table->boolean('jenis');
            $table->integer('bahan_baku_id');
            $table->index('bahan_baku_id');
            $table->string('nama');
            $table->float('jumlah');
            $table->foreignId('satuan_id')->nullable()->constrained('satuans')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_bahan_bakus');
    }
};
