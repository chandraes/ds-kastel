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
        Schema::create('kemasans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->foreignId('satuan_id')->nullable()->constrained('satuans')->onDelete('set null');
            $table->float('konversi_liter', 10, 2);
            $table->integer('stok')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kemasans');
    }
};
