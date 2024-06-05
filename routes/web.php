<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/login')->with('status', 'Please login to continue.');
});

Auth::routes([
    'register' => false,
]);

Route::group(['middleware' => ['auth']], function() {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::group(['middleware' => ['role:su,admin']], function() {
        // ROUTE PENGATURAN
        // Route::view('pengaturan', 'pengaturan.index')->name('pengaturan');
        Route::prefix('pengaturan')->group(function () {
            Route::get('/', [App\Http\Controllers\PengaturanController::class, 'index_view'])->name('pengaturan');
            Route::get('/wa', [App\Http\Controllers\WaController::class, 'index'])->name('pengaturan.wa');
            Route::get('/wa/get-wa-group', [App\Http\Controllers\WaController::class, 'get_group_wa'])->name('pengaturan.wa.get-group-wa');
            Route::patch('/wa/{group_wa}/update', [App\Http\Controllers\WaController::class, 'update'])->name('pengaturan.wa.update');

            Route::get('/akun', [App\Http\Controllers\PengaturanController::class, 'index'])->name('pengaturan.akun');
            Route::post('/akun/store', [App\Http\Controllers\PengaturanController::class, 'store'])->name('pengaturan.akun.store');
            Route::patch('/akun/{akun}/update', [App\Http\Controllers\PengaturanController::class, 'update'])->name('pengaturan.akun.update');
            Route::delete('/akun/{akun}/delete', [App\Http\Controllers\PengaturanController::class, 'destroy'])->name('pengaturan.akun.delete');

            Route::post('/password-konfirmasi', [App\Http\Controllers\PengaturanController::class, 'password_konfirmasi'])->name('pengaturan.password-konfirmasi');
            Route::post('/password-konfirmasi/cek', [App\Http\Controllers\PengaturanController::class, 'password_konfirmasi_cek'])->name('pengaturan.password-konfirmasi-cek');
        });

        Route::get('/histori-pesan', [App\Http\Controllers\HistoriController::class, 'index'])->name('histori-pesan');
        Route::post('/histori-pesan/resend/{pesanWa}', [App\Http\Controllers\HistoriController::class, 'resend'])->name('histori.resend');
        Route::delete('/histori-pesan/delete-sended', [App\Http\Controllers\HistoriController::class, 'delete_sended'])->name('histori.delete-sended');
        // END ROUTE PENGATURAN
    });

        // ROUTE DB
    Route::view('db', 'db.index')->name('db')->middleware('role:su,admin,user');
    Route::prefix('db')->group(function () {

        Route::group(['middleware' => ['role:su,admin']], function() {

            Route::prefix('kemasan')->group(function(){
                Route::get('/', [App\Http\Controllers\DatabaseController::class, 'kemasan'])->name('db.kemasan');
                Route::post('/store', [App\Http\Controllers\DatabaseController::class, 'kemasan_store'])->name('db.kemasan.store');
                Route::patch('/update/{kemasan}', [App\Http\Controllers\DatabaseController::class, 'kemasan_update'])->name('db.kemasan.update');
                Route::delete('/delete/{kemasan}', [App\Http\Controllers\DatabaseController::class, 'kemasan_delete'])->name('db.kemasan.delete');
            });

            Route::prefix('konsumen')->group(function(){
                Route::get('/', [App\Http\Controllers\DatabaseController::class, 'konsumen'])->name('db.konsumen');
                Route::post('/store', [App\Http\Controllers\DatabaseController::class, 'konsumen_store'])->name('db.konsumen.store');
                Route::patch('/{konsumen}/update', [App\Http\Controllers\DatabaseController::class, 'konsumen_update'])->name('db.konsumen.update');
                Route::delete('/{konsumen}/delete', [App\Http\Controllers\DatabaseController::class, 'konsumen_delete'])->name('db.konsumen.delete');
            });

            Route::get('/investor', [App\Http\Controllers\InvestorController::class, 'index'])->name('db.investor');
            Route::patch('/investor/{investor}/update', [App\Http\Controllers\InvestorController::class, 'update'])->name('db.investor.update');

            Route::get('/rekening', [App\Http\Controllers\RekeningController::class, 'index'])->name('db.rekening');
            Route::patch('/rekening/{rekening}/update', [App\Http\Controllers\RekeningController::class, 'update'])->name('db.rekening.update');

            Route::prefix('investor-modal')->group(function (){
                Route::get('/', [App\Http\Controllers\InvestorModalController::class, 'index'])->name('db.investor-modal');
                Route::post('/store', [App\Http\Controllers\InvestorModalController::class, 'store'])->name('db.investor-modal.store');
                Route::patch('/{investor}/update', [App\Http\Controllers\InvestorModalController::class, 'update'])->name('db.investor-modal.update');
                Route::delete('/{investor}/delete', [App\Http\Controllers\InvestorModalController::class, 'destroy'])->name('db.investor-modal.delete');
            });

            Route::prefix('pengelola')->group(function(){
                Route::get('/', [App\Http\Controllers\DatabaseController::class, 'pengelola'])->name('db.pengelola');
                Route::post('/store', [App\Http\Controllers\DatabaseController::class, 'pengelola_store'])->name('db.pengelola.store');
                Route::patch('/{pengelola}/update', [App\Http\Controllers\DatabaseController::class, 'pengelola_update'])->name('db.pengelola.update');
                Route::delete('/{pengelola}/delete', [App\Http\Controllers\DatabaseController::class, 'pengelola_delete'])->name('db.pengelola.delete');
            });

            Route::prefix('bahan-baku')->group(function(){
                Route::get('/', [App\Http\Controllers\BahanBakuController::class, 'index'])->name('db.bahan-baku');
                Route::post('/store', [App\Http\Controllers\BahanBakuController::class, 'store'])->name('db.bahan-baku.store');
                Route::delete('/{bahan}/delete', [App\Http\Controllers\BahanBakuController::class, 'destroy'])->name('db.bahan-baku.delete');
                Route::patch('/update/{bahan}', [App\Http\Controllers\BahanBakuController::class, 'update'])->name('db.bahan-baku.update');

                Route::prefix('kategori')->group(function(){
                    Route::post('/store', [App\Http\Controllers\BahanBakuController::class, 'kategori_store'])->name('db.bahan-baku.kategori.store');
                    Route::patch('/{kategori}/update', [App\Http\Controllers\BahanBakuController::class, 'kategori_update'])->name('db.bahan-baku.kategori.update');
                    Route::delete('/{kategori}/delete', [App\Http\Controllers\BahanBakuController::class, 'kategori_destroy'])->name('db.bahan-baku.kategori.delete');
                });
            });

            Route::prefix('packaging')->group(function(){
                Route::get('/', [App\Http\Controllers\DatabaseController::class, 'packaging'])->name('db.packaging');
                Route::post('/store', [App\Http\Controllers\DatabaseController::class, 'packaging_store'])->name('db.packaging.store');
                Route::patch('/update/{packaging}', [App\Http\Controllers\DatabaseController::class, 'packaging_update'])->name('db.packaging.update');
                Route::delete('/delete/{packaging}', [App\Http\Controllers\DatabaseController::class, 'packaging_delete'])->name('db.packaging.delete');
            });

            Route::prefix('satuan')->group(function(){
                Route::get('/', [App\Http\Controllers\DatabaseController::class, 'satuan'])->name('db.satuan');
                Route::post('/store', [App\Http\Controllers\DatabaseController::class, 'satuan_store'])->name('db.satuan.store');
                Route::patch('/update/{satuan}', [App\Http\Controllers\DatabaseController::class, 'satuan_update'])->name('db.satuan.update');
                Route::delete('/delete/{satuan}', [App\Http\Controllers\DatabaseController::class, 'satuan_delete'])->name('db.satuan.delete');
            });

            Route::prefix('supplier')->group(function(){
                Route::get('/', [App\Http\Controllers\DatabaseController::class, 'supplier'])->name('db.supplier');
                Route::post('/store', [App\Http\Controllers\DatabaseController::class, 'supplier_store'])->name('db.supplier.store');
                Route::patch('/update/{supplier}', [App\Http\Controllers\DatabaseController::class, 'supplier_update'])->name('db.supplier.update');
                Route::delete('/delete/{supplier}', [App\Http\Controllers\DatabaseController::class, 'supplier_delete'])->name('db.supplier.delete');
            });

            Route::prefix('product')->group(function(){
                Route::get('/', [App\Http\Controllers\ProductController::class, 'index'])->name('db.product');
                Route::get('/create', [App\Http\Controllers\ProductController::class, 'create'])->name('db.product.create');
                Route::post('/store', [App\Http\Controllers\ProductController::class, 'store'])->name('db.product.store');
                Route::get('/create-komposisi/{product}', [App\Http\Controllers\ProductController::class, 'create_komposisi'])->name('db.product.create-komposisi');
                Route::post('/store-komposisi', [App\Http\Controllers\ProductController::class, 'store_komposisi'])->name('db.product.store-komposisi');
                Route::delete('/delete-komposisi/{product}/{bahan}', [App\Http\Controllers\ProductController::class, 'delete_komposisi'])->name('db.product.delete-komposisi');
                Route::post('/kosongkan-komposisi/{product}', [App\Http\Controllers\ProductController::class, 'kosongkan_komposisi'])->name('db.product.kosongkan-komposisi');

                Route::delete('/delete/{product}', [App\Http\Controllers\ProductController::class, 'delete'])->name('db.product.delete');
                Route::patch('/update/{product}', [App\Http\Controllers\ProductController::class, 'update'])->name('db.product.update');

                Route::post('/kategori/store', [App\Http\Controllers\ProductController::class, 'kategori_store'])->name('db.product.kategori.store');
                Route::delete('/kategori/delete/{kategori}', [App\Http\Controllers\ProductController::class, 'kategori_delete'])->name('db.product.kategori.delete');
                Route::patch('/kategori/update/{kategori}', [App\Http\Controllers\ProductController::class, 'kategori_update'])->name('db.product.kategori.update');
            });

            Route::prefix('product-jadi')->group(function(){
                Route::get('/', [App\Http\Controllers\ProductController::class, 'product_jadi'])->name('db.product-jadi');
                Route::post('/store', [App\Http\Controllers\ProductController::class, 'product_jadi_store'])->name('db.product-jadi.store');
            });
        });
    });


    Route::group(['middleware' => ['role:su,admin,user,investor']], function() {
        Route::get('rekap', [App\Http\Controllers\RekapController::class, 'index'])->name('rekap');
        Route::prefix('rekap')->group(function() {
            Route::prefix('kas-besar')->group(function(){
                Route::get('/', [App\Http\Controllers\RekapController::class, 'kas_besar'])->name('rekap.kas-besar');
                Route::get('/print/{bulan}/{tahun}', [App\Http\Controllers\RekapController::class, 'kas_besar_print'])->name('rekap.kas-besar.print');
                Route::get('/detail-tagihan/{invoice}', [App\Http\Controllers\RekapController::class, 'detail_tagihan'])->name('rekap.kas-besar.detail-tagihan');
                Route::get('/detail-belanja/{invoice}', [App\Http\Controllers\RekapController::class, 'detail_belanja'])->name('rekap.kas-besar.detail-belanja');
            });

            Route::prefix('kas-kecil')->group(function(){
                Route::get('/', [App\Http\Controllers\RekapController::class, 'kas_kecil'])->name('rekap.kas-kecil');
                Route::get('/print/{bulan}/{tahun}', [App\Http\Controllers\RekapController::class, 'kas_kecil_print'])->name('rekap.kas-kecil.print');
                Route::get('/{kas}/void', [App\Http\Controllers\RekapController::class, 'void_kas_kecil'])->name('rekap.kas-kecil.void');
            });

            Route::get('/invoice', [App\Http\Controllers\RekapController::class, 'rekap_invoice'])->name('rekap.invoice');
            Route::get('/invoice/detail-project', [App\Http\Controllers\RekapController::class, 'rekap_invoice_detail_project'])->name('rekap.invoice.detail-project');

            Route::get('/statistik/{customer}', [App\Http\Controllers\StatistikController::class, 'index'])->name('statistik.index');
            Route::get('/statistik/{customer}/print', [App\Http\Controllers\StatistikController::class, 'print'])->name('statistik.print');

            Route::prefix('invoice-belanja')->group(function(){
                Route::get('/', [App\Http\Controllers\InvoiceController::class, 'index'])->name('rekap.invoice-belanja');
                Route::get('/detail/{invoice}', [App\Http\Controllers\InvoiceController::class, 'detail'])->name('rekap.invoice-belanja.detail');
            });

            Route::get('kas-project', [App\Http\Controllers\RekapController::class, 'kas_project'])->name('rekap.kas-project');
            Route::post('/kas-project/void/{kasProject}', [App\Http\Controllers\RekapController::class, 'void_kas_project'])->name('rekap.kas-project.void');
            Route::get('/kas-project/print/{project}/{bulan}/{tahun}', [App\Http\Controllers\RekapController::class, 'kas_project_print'])->name('rekap.kas-project.print');

            Route::prefix('kas-investor')->group(function(){
                Route::get('/', [App\Http\Controllers\RekapController::class, 'rekap_investor'])->name('rekap.kas-investor');
                Route::get('/show/{investor}', [App\Http\Controllers\RekapController::class, 'rekap_investor_show'])->name('rekap.kas-investor.show');
                Route::get('/detail/{investor}', [App\Http\Controllers\RekapController::class, 'rekap_investor_detail'])->name('rekap.kas-investor.detail');
                Route::get('/detail-deviden/{investor}/show', [App\Http\Controllers\RekapController::class, 'rekap_investor_detail_deviden_show'])->name('rekap.kas-investor.detail-deviden.show');
                Route::get('/detail-deviden/{investor}', [App\Http\Controllers\RekapController::class, 'rekap_investor_detail_deviden'])->name('rekap.kas-investor.detail-deviden');
            });

        });
    });

    // END ROUTE REKAP
    Route::group(['middleware' => ['role:su,admin,user']], function() {
        Route::get('/billing', [App\Http\Controllers\BillingController::class, 'index'])->name('billing');
        Route::prefix('billing')->group(function() {

            Route::prefix('form-deposit')->group(function() {
                Route::get('/masuk', [App\Http\Controllers\FormDepositController::class, 'masuk'])->name('form-deposit.masuk');
                Route::post('/masuk/store', [App\Http\Controllers\FormDepositController::class, 'masuk_store'])->name('form-deposit.masuk.store');
                Route::get('/keluar', [App\Http\Controllers\FormDepositController::class, 'keluar'])->name('form-deposit.keluar');
                Route::post('/keluar/store', [App\Http\Controllers\FormDepositController::class, 'keluar_store'])->name('form-deposit.keluar.store');
                Route::get('/keluar-all', [App\Http\Controllers\FormDepositController::class, 'keluar_all'])->name('form-deposit.keluar-all');
                Route::post('/keluar-all/store', [App\Http\Controllers\FormDepositController::class, 'keluar_all_store'])->name('form-deposit.keluar-all.store');
            });

            Route::prefix('form-kas-kecil')->group(function(){
                Route::get('/masuk', [App\Http\Controllers\FormKasKecilController::class, 'masuk'])->name('form-kas-kecil.masuk');
                Route::post('/masuk/store', [App\Http\Controllers\FormKasKecilController::class, 'masuk_store'])->name('form-kas-kecil.masuk.store');
                Route::get('/keluar', [App\Http\Controllers\FormKasKecilController::class, 'keluar'])->name('form-kas-kecil.keluar');
                Route::post('/keluar/store', [App\Http\Controllers\FormKasKecilController::class, 'keluar_store'])->name('form-kas-kecil.keluar.store');
            });

            Route::prefix('form-lain')->group(function(){
                Route::get('/masuk', [App\Http\Controllers\FormLainController::class, 'masuk'])->name('form-lain.masuk');
                Route::post('/masuk/store', [App\Http\Controllers\FormLainController::class, 'masuk_store'])->name('form-lain.masuk.store');
                Route::get('/keluar', [App\Http\Controllers\FormLainController::class, 'keluar'])->name('form-lain.keluar');
                Route::post('/keluar/store', [App\Http\Controllers\FormLainController::class, 'keluar_store'])->name('form-lain.keluar.store');
            });

            Route::prefix('produksi')->group(function(){
                Route::get('/', [App\Http\Controllers\ProduksiController::class, 'index'])->name('billing.produksi');
                Route::get('/get-kemasan', [App\Http\Controllers\ProduksiController::class, 'getKemasan'])->name('billing.produksi.get-kemasan');
                Route::get('/get-komposisi', [App\Http\Controllers\ProduksiController::class, 'getKomposisi'])->name('billing.produksi.get-komposisi');
                Route::post('/store', [App\Http\Controllers\ProduksiController::class, 'store'])->name('billing.produksi.store');
            });

            Route::prefix('stok-bahan-jadi')->group(function(){
                Route::get('/', [App\Http\Controllers\StokBahanJadiController::class, 'index'])->name('billing.stok-bahan-jadi');
                Route::prefix('keranjang')->group(function(){
                    Route::post('/store', [App\Http\Controllers\StokBahanJadiController::class, 'keranjang_store'])->name('billing.stok-bahan-jadi.keranjang.store');
                    Route::post('/update', [App\Http\Controllers\StokBahanJadiController::class, 'keranjang_update'])->name('billing.stok-bahan-jadi.keranjang.update');
                    Route::post('/set-jumlah', [App\Http\Controllers\StokBahanJadiController::class, 'keranjang_set'])->name('billing.stok-bahan-jadi.keranjang.set-jumlah');
                    Route::post('/empty', [App\Http\Controllers\StokBahanJadiController::class, 'keranjang_empty'])->name('billing.stok-bahan-jadi.keranjang.empty');
                });

                Route::prefix('checkout')->group(function(){
                    Route::get('/', [App\Http\Controllers\StokBahanJadiController::class, 'checkout'])->name('billing.stok-bahan-jadi.checkout');
                    Route::get('/konsumen', [App\Http\Controllers\StokBahanJadiController::class, 'get_konsumen'])->name('billing.stok-bahan-jadi.checkout.konsumen');
                });


                Route::prefix('/rencana')->group(function(){
                    Route::get('/', [App\Http\Controllers\StokBahanJadiController::class, 'rencana_stok'])->name('billing.stok-bahan-jadi.rencana');
                    Route::post('/lanjutkan/{rencanaProduksi}', [App\Http\Controllers\StokBahanJadiController::class, 'lanjut_stok'])->name('billing.stok-bahan-jadi.rencana.lanjutkan');
                });

                Route::prefix('produksi-ke')->group(function(){
                    Route::post('/{rencanaProduksi}', [App\Http\Controllers\StokBahanJadiController::class, 'produksi_ke'])->name('billing.stok-bahan-jadi.produksi-ke');
                    Route::post('/store/{rencanaProduksi}', [App\Http\Controllers\StokBahanJadiController::class, 'store_produksi_ke'])->name('billing.stok-bahan-jadi.produksi-ke.store');
                    Route::get('/edit/{rencanaProduksi}', [App\Http\Controllers\StokBahanJadiController::class, 'edit_produksi_ke'])->name('billing.stok-bahan-jadi.edit-produksi-ke');
                    Route::post('/edit/{rencanaProduksi}', [App\Http\Controllers\StokBahanJadiController::class, 'update_produksi_ke'])->name('billing.stok-bahan-jadi.edit-produksi-ke.update');
                });

            });

            Route::prefix('jual-product')->group(function(){

            });

            Route::prefix('transaksi')->group(function(){
                Route::get('/', [App\Http\Controllers\FormTransaksiController::class, 'index'])->name('billing.form-transaksi');

                Route::prefix('form-bahan-baku')->group(function(){
                    Route::get('/beli', [App\Http\Controllers\FormTransaksiController::class, 'bahan_baku_beli'])->name('billing.form-transaksi.bahan-baku.beli');
                    Route::get('/get-barang', [App\Http\Controllers\BahanBakuController::class, 'get_barang'])->name('billing.form-transaksi.bahan-baku.get-barang');
                    Route::get('/get-supplier', [App\Http\Controllers\BahanBakuController::class, 'get_supplier'])->name('billing.form-transaksi.bahan-baku.get-supplier');

                    Route::prefix('keranjang')->group(function(){
                        Route::delete('/delete/{keranjang}', [App\Http\Controllers\FormTransaksiController::class, 'keranjang_delete'])->name('billing.form-transaksi.bahan-baku.keranjang.delete');
                        Route::post('/store', [App\Http\Controllers\FormTransaksiController::class, 'keranjang_store'])->name('billing.form-transaksi.bahan-baku.keranjang.store');
                        Route::post('/empty', [App\Http\Controllers\FormTransaksiController::class, 'keranjang_empty'])->name('billing.form-transaksi.bahan-baku.keranjang.empty');
                        Route::post('/checkout', [App\Http\Controllers\FormTransaksiController::class, 'keranjang_checkout'])->name('billing.form-transaksi.bahan-baku.keranjang.checkout');
                    });

                    Route::get('/beli-tempo', [App\Http\Controllers\FormTransaksiController::class, 'bahan_baku_beli_tempo'])->name('billing.form-transaksi.bahan-baku.beli-tempo');

                    Route::prefix('keranjang-tempo')->group(function(){
                        Route::post('/store', [App\Http\Controllers\FormTransaksiController::class, 'keranjang_tempo_store'])->name('billing.form-transaksi.bahan-baku.keranjang-tempo.store');
                        Route::post('/empty', [App\Http\Controllers\FormTransaksiController::class, 'keranjang_tempo_empty'])->name('billing.form-transaksi.bahan-baku.keranjang-tempo.empty');
                        Route::post('/checkout', [App\Http\Controllers\FormTransaksiController::class, 'keranjang_tempo_checkout'])->name('billing.form-transaksi.bahan-baku.keranjang-tempo.checkout');
                    });

                    Route::prefix('hutang-belanja')->group(function(){
                        Route::get('/', [App\Http\Controllers\FormTransaksiController::class, 'hutang_belanja'])->name('billing.form-transaksi.bahan-baku.hutang-belanja');
                        Route::post('/bayar/{invoice}', [App\Http\Controllers\FormTransaksiController::class, 'hutang_belanja_bayar'])->name('billing.form-transaksi.bahan-baku.hutang-belanja.bayar');
                    });

                });
            });

            Route::get('/nota-tagihan', [App\Http\Controllers\NotaTagihanController::class, 'index'])->name('nota-tagihan.index');
            Route::post('/nota-tagihan/cicilan/{invoice}', [App\Http\Controllers\NotaTagihanController::class, 'cicilan'])->name('nota-tagihan.cicilan');
            Route::post('/nota-tagihan/cutoff/{invoice}', [App\Http\Controllers\NotaTagihanController::class, 'cutoff'])->name('nota-tagihan.cutoff');
            Route::post('/nota-tagihan/pelunasan/{invoice}', [App\Http\Controllers\NotaTagihanController::class, 'pelunasan'])->name('nota-tagihan.pelunasan');

            Route::prefix('nota-ppn-masukan')->group(function(){
                Route::get('/', [App\Http\Controllers\BillingController::class, 'nota_ppn_masukan'])->name('nota-ppn-masukan');
                Route::post('/claim/{invoice}', [App\Http\Controllers\BillingController::class, 'claim_ppn'])->name('nota-ppn-masukan.claim');

            });

            Route::prefix('invoice-tagihan')->group(function () {
                Route::get('/', [App\Http\Controllers\BillingController::class, 'invoice_tagihan'])->name('invoice-tagihan');
            });

            Route::prefix('invoice-ppn')->group(function() {
                Route::get('/', [App\Http\Controllers\BillingController::class, 'invoice_ppn'])->name('invoice-ppn');
                Route::post('/bayar/{invoice}', [App\Http\Controllers\BillingController::class, 'invoice_ppn_bayar'])->name('invoice-ppn.bayar');
            });

            Route::prefix('ppn-susulan')->group(function() {
                Route::get('/', [App\Http\Controllers\BillingController::class, 'ppn_masuk_susulan'])->name('ppn-susulan');
                Route::post('/store', [App\Http\Controllers\BillingController::class, 'ppn_masuk_susulan_store'])->name('ppn-susulan.store');
            });

        });

    });

});
