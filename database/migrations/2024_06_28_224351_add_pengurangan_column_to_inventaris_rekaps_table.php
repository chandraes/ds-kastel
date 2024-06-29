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
        Schema::table('inventaris_rekaps', function (Blueprint $table) {
            $table->integer('pengurangan')->default(0)->after('jumlah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventaris_rekaps', function (Blueprint $table) {
            $table->dropColumn('pengurangan');
        });
    }
};
