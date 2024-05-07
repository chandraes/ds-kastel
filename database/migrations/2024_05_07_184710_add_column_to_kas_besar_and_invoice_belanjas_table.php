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
        Schema::table('kas_besars', function (Blueprint $table) {
            $table->integer('nomor_bb')->nullable()->after('nomor_kode_kas_kecil');
            $table->foreignId('invoice_belanja_id')->nullable()->constrained()->after('nomor_bb');
        });

        Schema::table('invoice_belanjas', function (Blueprint $table) {
            $table->integer('nomor_bb')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kas_besars', function (Blueprint $table) {
            $table->dropForeign(['invoice_belanja_id']);
            $table->dropColumn('invoice_belanja_id');
            $table->dropColumn('nomor_bb');
        });


        Schema::table('invoice_belanjas', function (Blueprint $table) {
            $table->integer('nomor_bb')->nullable()->after('id');
        });
    }
};
