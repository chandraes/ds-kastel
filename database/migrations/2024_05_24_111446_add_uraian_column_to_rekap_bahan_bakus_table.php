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
            $table->string('uraian')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rekap_bahan_bakus', function (Blueprint $table) {
            $table->dropColumn('uraian');
        });
    }
};
