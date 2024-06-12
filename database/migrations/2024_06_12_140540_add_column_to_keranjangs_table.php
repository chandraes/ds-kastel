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
        Schema::table('keranjangs', function (Blueprint $table) {
            // make foreign bahan_baku_id to nullable
            $table->foreignId('bahan_baku_id')->nullable()->change();
            $table->foreignId('kemasan_id')->nullable()->constrained('kemasans')->onDelete('set null');
            $table->foreignId('packaging_id')->nullable()->constrained('packagings')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('keranjangs', function (Blueprint $table) {
            // make foreign bahan_baku_id to not nullable
            $table->foreignId('bahan_baku_id')->nullable(false)->change();
            $table->dropForeign(['kemasan_id']);
            $table->dropForeign(['packaging_id']);
        });
    }
};
