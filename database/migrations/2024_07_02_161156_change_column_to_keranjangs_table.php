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
            $table->dropColumn('add_fee');
        });


        Schema::table('rekap_bahan_bakus', function (Blueprint $table) {
            $table->dropColumn('add_fee');
        });

        Schema::table('invoice_belanjas', function (Blueprint $table) {
            $table->integer('add_fee')->default(0)->after('ppn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('keranjangs', function (Blueprint $table) {
            $table->integer('add_fee')->default(0);
        });

        Schema::table('rekap_bahan_bakus', function (Blueprint $table) {
            $table->integer('add_fee')->default(0);
        });

        Schema::table('invoice_belanjas', function (Blueprint $table) {
            $table->dropColumn('add_fee');
        });
    }
};
