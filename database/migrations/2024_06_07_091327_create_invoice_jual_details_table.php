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
        Schema::create('invoice_jual_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_jual_id')->constrained('invoice_juals')->onDelete('cascade');
            $table->foreignId('product_jadi_id')->constrained('product_jadis')->onDelete('cascade');
            $table->bigInteger('harga');
            $table->bigInteger('jumlah');
            $table->bigInteger('total');
            $table->timestamps();
        });

        Schema::table('kas_besars', function (Blueprint $table) {
            $table->foreignId('invoice_jual_id')->nullable()->constrained('invoice_juals')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_jual_details');
        Schema::table('kas_besars', function (Blueprint $table) {
            $table->dropForeign(['invoice_jual_id']);
            $table->dropColumn('invoice_jual_id');
        });
    }
};
