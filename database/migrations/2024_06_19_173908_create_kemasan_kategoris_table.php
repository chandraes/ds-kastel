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
        Schema::create('kemasan_kategoris', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->timestamps();
        });

        Schema::table('kemasans', function (Blueprint $table) {
            $table->foreignId('kemasan_kategori_id')->nullable()->after('product_id')->constrained('kemasan_kategoris')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kemasans', function (Blueprint $table) {
            $table->dropForeign(['kemasan_kategori_id']);
            $table->dropColumn('kemasan_kategori_id');
        });

        Schema::dropIfExists('kemasan_kategoris');
    }
};
