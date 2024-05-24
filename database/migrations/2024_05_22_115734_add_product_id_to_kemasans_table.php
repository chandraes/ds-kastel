<?php

use App\Models\db\Kemasan;
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
        Schema::dropIfExists('product_kemasans');
        Kemasan::truncate();

        Schema::table('kemasans', function (Blueprint $table) {
            $table->foreignId('product_id')->after('id')->constrained('products')->onDelete('cascade');
            $table->integer('harga')->after('packaging_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kemasans', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');
            $table->dropColumn('harga');
        });
    }
};
