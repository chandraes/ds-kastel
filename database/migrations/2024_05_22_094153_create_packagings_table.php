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
        Schema::create('packagings', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->foreignId('satuan_id')->nullable()->constrained('satuans')->onDelete('set null');
            $table->integer('konversi_kemasan');
            $table->integer('stok')->default(0);
            $table->timestamps();
        });

        Schema::table('kemasans', function (Blueprint $table) {
            $table->foreignId('packaging_id')->nullable()->after('stok')->constrained('packagings')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kemasans', function (Blueprint $table) {
            $table->dropForeign(['packaging_id']);
            $table->dropColumn('packaging_id');
        });

        Schema::dropIfExists('packagings');
    }
};
