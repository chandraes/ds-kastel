<?php

namespace App\Http\Controllers;

use App\Models\db\Kemasan;
use App\Models\PasswordKonfirmasi;
use App\Models\Produksi\ProductJadi;
use App\Models\Produksi\ProduksiDetail;
use App\Models\Produksi\RencanaProduksi;
use App\Models\transaksi\KeranjangJual;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokBahanJadiController extends Controller
{
    public function index()
    {
        $db = new ProductJadi();
        $data = $db->with(['product.kategori', 'kemasan'])
                    ->get();

        $groupedData = $data->groupBy(function($item, $key) {
            return $item->product->kategori->id;
        });

        $keranjang = KeranjangJual::where('user_id', auth()->user()->id)
                                    ->with(['product_jadi.product'])
                                    ->get();

        return view('billing.stok-bahan-jadi.index', [
            'groupedData' => $groupedData,
            'keranjang' => $keranjang
        ]);
    }

    public function keranjang_store(Request $request)
    {
        $data = $request->validate([
            'product_jadi_id' => 'required|exists:product_jadis,id',
            'jumlah' => 'required'
        ]);
        $product = ProductJadi::find($data['product_jadi_id']);

        if($data['jumlah'] == 0 || $data['jumlah'] > $product->stock_packaging)
        {
            return redirect()->back()->with('error', 'Jumlah tidak melebihi stok');
        }


        $data['user_id'] = auth()->user()->id;
        $data['jumlah'] = str_replace('.', '', $data['jumlah']);

        $data['harga'] = $product->kemasan->harga;

        KeranjangJual::create($data);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    public function rencana_stok(Request $request)
    {
        $data = RencanaProduksi::with(['product', 'kemasan', 'produksi_detail'])
                                ->withSum('produksi_detail as sum_kemasan', 'total_kemasan')
                                ->where('approved', 0)
                                ->get();

                // dd($data);
        $data = $data->groupBy(function($item, $key) {
            return $item->product->kategori->id . $item->product_id;
        });


        return view('billing.rencana-stok.index', [
            'data' => $data
        ]);
    }

    public function produksi_ke(RencanaProduksi $rencanaProduksi, Request $request)
    {
        $data = $request->validate([
            'jumlah_produksi' => 'required|integer'
        ]);

        try {
            DB::beginTransaction();

            for($i = 1; $i <= $data['jumlah_produksi']; $i++) {
                $rencanaProduksi->produksi_detail()->create([
                    'detail_ke' => $i
                ]);
            }

            DB::commit();
        } catch (\Throwable $th) {

            DB::rollBack();

            return redirect()->back()->with('error', 'Gagal membuat produksi');
        }

        $konversi = $rencanaProduksi->packaging ? $rencanaProduksi->packaging->konversi_kemasan : 1;

        return view('billing.rencana-stok.produksi-ke', [
            'data' => $rencanaProduksi->load('produksi_detail'),
            'konversi' => $konversi
        ]);

    }

    public function store_produksi_ke(RencanaProduksi $rencanaProduksi, Request $request)
    {
        $data = $request->validate([
            'id' => 'required',
            'id.*' => 'required|exists:produksi_details,id',
            'total_kemasan' => 'required',
            'total_kemasan.*' => 'required|integer',
            'real_packaging' => 'required',
        ]);

        try{
            DB::beginTransaction();

            for($i = 0; $i < count($data['id']); $i++) {
                $rencanaProduksi->produksi_detail()->find($data['id'][$i])->update([
                    'total_kemasan' => $data['total_kemasan'][$i],
                ]);
            }

            $rencanaProduksi->update([
                'real_packaging' => $data['real_packaging']
            ]);

            DB::commit();

        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->with('error', $th->getMessage());
        }

        return redirect()->route('billing.stok-bahan-jadi.rencana')->with('success', 'Data berhasil diupdate');
    }

    public function edit_produksi_ke(RencanaProduksi $rencanaProduksi)
    {
        $data = $rencanaProduksi->load(['produksi_detail', 'packaging']);

        $konversi = $data->packaging ? $data->packaging->konversi_kemasan : 1;

        return view('billing.rencana-stok.edit-produksi-ke', [
            'data' => $data,
            'konversi' => $konversi
        ]);
    }

    public function update_produksi_ke(RencanaProduksi $rencanaProduksi, Request $request)
    {
        $data = $request->validate([
            'id' => 'required',
            'id.*' => 'required|exists:produksi_details,id',
            'total_kemasan' => 'required',
            'total_kemasan.*' => 'required|integer',
            'real_packaging' => 'required',
        ]);

        try{
            DB::beginTransaction();

            for($i = 0; $i < count($data['id']); $i++) {
                $rencanaProduksi->produksi_detail()->find($data['id'][$i])->update([
                    'total_kemasan' => $data['total_kemasan'][$i],
                ]);
            }

            $rencanaProduksi->update([
                'real_packaging' => $data['real_packaging']
            ]);

            DB::commit();

        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->with('error', $th->getMessage());
        }

        return redirect()->route('billing.stok-bahan-jadi.rencana')->with('success', 'Data berhasil diupdate');
    }

    public function lanjut_stok(RencanaProduksi $rencanaProduksi, Request $request)
    {
        $data = $request->validate([
            'password' => 'required',
        ]);

        $pass = PasswordKonfirmasi::first();

        if (!$pass || $data['password'] != $pass->password) {
            return redirect()->back()->with('error', 'Password salah / belum diatur');
        }

        $data = $rencanaProduksi->load('produksi_detail');

        try {
            DB::beginTransaction();

            $store = ProductJadi::firstOrCreate(
                ['product_id' => $data->product_id, 'kemasan_id' => $data->kemasan_id],
                ['stock_kemasan' => $data->produksi_detail->sum('total_kemasan'), 'stock_packaging' => $data->real_packaging]
            );

            if (!$store->wasRecentlyCreated) {
                $store->increment('stock_kemasan', $data->produksi_detail->sum('total_kemasan'));
                $store->increment('stock_packaging', $data->real_packaging);
            }

            $data->update([
                'approved' => 1
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->with('error', $th->getMessage());
        }

        return redirect()->route('billing.stok-bahan-jadi.rencana')->with('success', 'Data berhasil disimpan');
    }
}
