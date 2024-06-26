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
            $table->index(['dp_ppn', 'tempo'], 'idx_dp_ppn_tempo');
            $table->index(['sisa_ppn', 'tempo'], 'idx_sisa_ppn_tempo');
            $table->index(['ppn', 'tempo'], 'idx_ppn_tempo');

            // Adding indexes for ordering if needed
            $table->index('created_at', 'idx_created_at');
            $table->index('updated_at', 'idx_updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_belanjas', function (Blueprint $table) {
            $table->dropIndex('idx_dp_ppn_tempo');
            $table->dropIndex('idx_sisa_ppn_tempo');
            $table->dropIndex('idx_ppn_tempo');
            $table->dropIndex('idx_created_at');
            $table->dropIndex('idx_updated_at');
        });
    }
};
