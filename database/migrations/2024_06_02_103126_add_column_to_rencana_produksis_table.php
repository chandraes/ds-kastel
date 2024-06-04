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
        Schema::table('rencana_produksis', function (Blueprint $table) {
            $table->integer('real_packaging')->default(0)->after('rencana_packaging');
        });

        Schema::table('produksi_details', function (Blueprint $table) {
            $table->dropColumn('total_packaging');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rencana_produksis', function (Blueprint $table) {
            $table->dropColumn('real_packaging');
        });

        Schema::table('produksi_details', function (Blueprint $table) {
            $table->integer('total_packaging')->default(0);
        });
    }
};
