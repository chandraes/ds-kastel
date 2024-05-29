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
        Schema::create('produksi_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rencana_produksi_id')->constrained('rencana_produksis')->onDelete('cascade');
            $table->integer('detail_ke');
            $table->integer('total_kemasan')->default(0);
            $table->integer('total_packaging')->default(0);
            $table->timestamps();
        });

        Schema::table('rencana_produksis', function (Blueprint $table) {
            $table->boolean('approved')->default(0)->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produksi_details');
        Schema::table('rencana_produksis', function (Blueprint $table) {
            $table->dropColumn('approved');
        });
    }
};
