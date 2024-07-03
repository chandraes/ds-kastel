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
        Schema::table('purchase_orders', function (Blueprint $table) {
            // remove column tanggal and grand_total
            $table->dropColumn('tanggal');
            $table->dropColumn('grand_total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->date('tanggal')->nullable();
            $table->decimal('grand_total', 15, 2)->default(0);
        });
    }
};
