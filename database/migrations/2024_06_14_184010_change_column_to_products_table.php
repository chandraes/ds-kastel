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
        Schema::table('products', function (Blueprint $table) {
            // add unique column to column 'kode'
            $table->string('kode')->unique()->change();
        });
        Schema::table('kategori_products', function (Blueprint $table) {
            // add unique column to column 'kode'
            $table->string('kode')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // remove unique column to column 'kode'
            $table->string('kode')->change();

        });
        Schema::table('kategori_products', function (Blueprint $table) {
            // remove unique column to column 'kode'
            $table->string('kode')->change();
        });
    }
};
