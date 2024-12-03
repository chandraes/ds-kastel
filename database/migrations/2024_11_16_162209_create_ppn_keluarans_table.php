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
        Schema::create('ppn_keluarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_jual_id')->nullable()->constrained('invoice_juals')->onDelete('set null');
            $table->string('uraian')->nullable();
            $table->integer('nominal');
            $table->integer('saldo');
            $table->boolean('is_faktur')->default(0);
            $table->string('no_faktur')->nullable();
            $table->boolean('dipungut')->default(1);
            $table->boolean('is_expired')->default(0);
            $table->boolean('is_keranjang')->default(0);
            $table->boolean('is_finish')->default(0);
            $table->timestamps();
        });

        Schema::table('invoice_juals', function (Blueprint $table) {
            $table->boolean('ppn_dipungut')->default(1)->after('ppn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppn_keluarans');
        Schema::table('invoice_juals', function (Blueprint $table) {
            $table->dropColumn('ppn_dipungut');
        });
    }
};
