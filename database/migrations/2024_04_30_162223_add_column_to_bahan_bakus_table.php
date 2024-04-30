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
        Schema::table('bahan_bakus', function (Blueprint $table) {
            $table->boolean('apa_konversi')->default(0)->after('id');
            $table->foreignId('satuan_id')->nullable()->after('stock')->constrained('satuans');
            $table->float('konversi')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bahan_bakus', function (Blueprint $table) {
            $table->dropColumn('apa_konversi');
            $table->dropForeign(['satuan_id']);
            $table->dropColumn('satuan_id');
            $table->float('konversi')->change();
        });
    }
};
