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
        Schema::table('kemasans', function (Blueprint $table) {
            $table->unique(['product_id', 'kemasan_kategori_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kemasans', function (Blueprint $table) {
            $table->dropUnique(['product_id', 'kemasan_kategori_id']);
        });
    }
};
