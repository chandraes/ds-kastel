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
        Schema::table('invoice_belanjas', function (Blueprint $table) {
            $table->integer('dp_ppn')->default(0)->after('dp');
            $table->integer('sisa_ppn')->default(0)->after('sisa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_belanjas', function (Blueprint $table) {
            $table->dropColumn('dp_ppn');
            $table->dropColumn('sisa_ppn');
        });
    }
};
