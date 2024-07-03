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
        Schema::dropIfExists('purchase_order_items');
        Schema::dropIfExists('purchase_order_notes');
        Schema::dropIfExists('purchase_orders');

        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->integer('nomor');
            $table->string('tanggal');
            $table->string('kepada');
            $table->text('alamat');
            $table->string('telepon');
            $table->integer('grand_total');
            $table->integer('status')->default(1);
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->cascadeOnDelete();
            $table->string('kategori')->nullable();
            $table->string('nama_barang')->nullable();
            $table->integer('jumlah');
            $table->integer('harga_satuan');
            $table->integer('total');
            $table->timestamps();
        });

        Schema::create('purchase_order_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->cascadeOnDelete();
            $table->text('note');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_notes');
        Schema::dropIfExists('purchase_order_items');
        Schema::dropIfExists('purchase_orders');

    }
};
