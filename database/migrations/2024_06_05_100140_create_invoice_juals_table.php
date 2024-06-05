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
        Schema::table('konsumens', function (Blueprint $table) {
            $table->boolean('active')->default(1)->after('id');
        });
        
        Schema::create('invoice_juals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('konsumen_id')->constrained('konsumens')->onDelete('cascade');
            $table->bigInteger('no_invoice');
            $table->string('invoice');
            $table->bigInteger('total');
            $table->bigInteger('ppn');
            $table->boolean('lunas')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_juals');
        Schema::table('konsumens', function (Blueprint $table) {
            $table->dropColumn('active');
        });
    }
};
