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
        Schema::create('invoice_belanjas', function (Blueprint $table) {
            $table->id();
            $table->string('uraian');
            $table->float('diskon', 20, 2)->default(0);
            $table->float('ppn', 20, 2)->default(0);
            $table->float('total', 20, 2);
            $table->string('nama_rek');
            $table->string('no_rek');
            $table->string('bank');
            $table->boolean('ppn_masukan')->default(0);
            $table->boolean('tempo')->default(0);
            $table->date('jatuh_tempo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_belanjas');
    }
};
