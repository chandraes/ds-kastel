<?php

use App\Models\db\RekapBahanBaku;
use App\Models\transaksi\InvoiceBelanja;
use App\Models\transaksi\InvoiceBelanjaDetail;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        InvoiceBelanjaDetail::truncate();
        RekapBahanBaku::truncate();
        InvoiceBelanja::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Schema::table('invoice_belanjas', function (Blueprint $table) {
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->after('id');
            $table->integer('dp')->default(0)->after('total');
            $table->integer('sisa')->default(0)->after('dp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_belanjas', function (Blueprint $table) {
            $table->dropColumn('supplier_id');
            $table->dropColumn('dp');
            $table->dropColumn('sisa');
        });
    }
};
