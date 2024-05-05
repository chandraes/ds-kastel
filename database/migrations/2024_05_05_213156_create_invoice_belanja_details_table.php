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
        Schema::create('invoice_belanja_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_belanja_id')->constrained()->cascadeOnDelete();
            $table->foreignId('rekap_bahan_baku_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_belanja_details');
    }
};
