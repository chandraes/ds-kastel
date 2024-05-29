<?php

namespace App\Http\Controllers;

use App\Models\PasswordKonfirmasi;
use App\Models\Produksi\ProduksiDetail;
use App\Models\Produksi\RencanaProduksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokBahanJadiController extends Controller
{
    public function index()
    {
        $data = RencanaProduksi::with(['produksi_detail', 'product', 'product.kategori', 'kemasan', 'kemasan.satuan'])
                                ->withSum('produksi_detail as sum_kemasan', 'total_kemasan')
                                ->withSum('produksi_detail as sum_packaging', 'total_packaging')
                                ->where('approved', 1)->get();

        $data = $data->groupBy(function($item, $key) {
            return $item->product->kategori->id . $item->product_id;
        });

        return view('billing.stok-bahan-jadi.index', [
            'data' => $data
        ]);
    }

    public function rencana_stok(Request $request)
    {
        $data = RencanaProduksi::with(['product', 'kemasan', 'produksi_detail'])
                                ->withSum('produksi_detail as sum_kemasan', 'total_kemasan')
                                ->withSum('produksi_detail as sum_packaging', 'total_packaging')
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

        return view('billing.rencana-stok.produksi-ke', [
            'data' => $rencanaProduksi->load('produksi_detail')
        ]);

    }

    public function store_produksi_ke(RencanaProduksi $rencanaProduksi, Request $request)
    {
        $data = $request->validate([
            'id' => 'required',
            'id.*' => 'required|exists:produksi_details,id',
            'total_kemasan' => 'required',
            'total_kemasan.*' => 'required|integer',
            'total_packaging' => 'required',
            'total_packaging.*' => 'required|integer',
        ]);

        for($i = 0; $i < count($data['id']); $i++) {
            $rencanaProduksi->produksi_detail()->find($data['id'][$i])->update([
                'total_kemasan' => $data['total_kemasan'][$i],
                'total_packaging' => $data['total_packaging'][$i]
            ]);
        }

        return redirect()->route('billing.stok-bahan-jadi.rencana')->with('success', 'Data berhasil diupdate');
    }

    public function edit_produksi_ke(RencanaProduksi $rencanaProduksi)
    {
        $data = $rencanaProduksi->load('produksi_detail');

        return view('billing.rencana-stok.edit-produksi-ke', [
            'data' => $data
        ]);
    }

    public function update_produksi_ke(RencanaProduksi $rencanaProduksi, Request $request)
    {
        $data = $request->validate([
            'id' => 'required',
            'id.*' => 'required|exists:produksi_details,id',
            'total_kemasan' => 'required',
            'total_kemasan.*' => 'required|integer',
            'total_packaging' => 'required',
            'total_packaging.*' => 'required|integer',
        ]);

        for($i = 0; $i < count($data['id']); $i++) {
            $rencanaProduksi->produksi_detail()->find($data['id'][$i])->update([
                'total_kemasan' => $data['total_kemasan'][$i],
                'total_packaging' => $data['total_packaging'][$i]
            ]);
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

        $rencanaProduksi->update([
            'approved' => 1
        ]);

        return redirect()->route('billing.stok-bahan-jadi.rencana')->with('success', 'Data berhasil disimpan');
    }
}
