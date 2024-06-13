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
        Schema::table('rekap_bahan_bakus', function (Blueprint $table) {
            // change column bahan_baku_id to nullable and on delete set null
            $table->foreignId('bahan_baku_id')->nullable()->change();
            $table->foreignId('kemasan_id')->nullable()->constrained('kemasans')->onDelete('set null')->after('bahan_baku_id');
            $table->foreignId('packaging_id')->nullable()->constrained('packagings')->onDelete('set null')->after('kemasan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rekap_bahan_bakus', function (Blueprint $table) {
            $table->dropForeign(['kemasan_id']);
            $table->dropColumn('kemasan_id');
            $table->dropForeign(['packaging_id']);
            $table->dropColumn('packaging_id');
        });
    }
};
