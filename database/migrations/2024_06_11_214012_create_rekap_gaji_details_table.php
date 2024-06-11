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
        Schema::create('rekap_gaji_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rekap_gaji_id')->constrained('rekap_gajis')->onDelete('cascade');
            $table->string('nik');
            $table->string('nama');
            $table->string('jabatan');
            $table->bigInteger('gaji_pokok');
            $table->bigInteger('tunjangan_jabatan');
            $table->bigInteger('tunjangan_keluarga');
            $table->integer('bpjs_tk');
            $table->integer('bpjs_k');
            $table->integer('potongan_bpjs_tk');
            $table->integer('potongan_bpjs_kesehatan');
            $table->integer('pendapatan_kotor');
            $table->integer('pendapatan_bersih');
            $table->string('no_rek');
            $table->string('nama_rek');
            $table->string('bank');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_gaji_details');
    }
};
