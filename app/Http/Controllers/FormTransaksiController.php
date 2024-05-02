<?php

namespace App\Http\Controllers;

use App\Models\db\KategoriBahan;
use App\Models\transaksi\Keranjang;
use Illuminate\Http\Request;

class FormTransaksiController extends Controller
{
    public function index()
    {
        return view('billing.form-transaksi.index');
    }

    public function bahan_baku_beli()
    {
        $kategori = KategoriBahan::all();
        $keranjang = Keranjang::with(['bahan_baku'])->where('user_id', auth()->id())->get();

        return view('billing.form-transaksi.bahan-baku.beli', [
            'kategori' => $kategori,
            'keranjang' => $keranjang
        ]);
    }

    public function keranjang_store(Request $request)
    {
        $data = $request->validate([
            'bahan_baku_id' => 'required|exists:bahan_bakus,id',
            'jumlah' => 'required|numeric|min:1',
            'harga' => 'required',
            'satuan_id' => 'required|exists:satuans,id',
            'add_fee' => 'required'
        ]);
    }

    public function keranjang_empty()
    {
        $count = Keranjang::where('user_id', auth()->id())->count();

        if ($count == 0) {
            return redirect()->back()->with('error', 'Keranjang kosong');
        }

        Keranjang::where('user_id', auth()->id())->delete();

        return redirect()->route('billing.form-transaksi.bahan-baku.beli')->with('success', 'Keranjang berhasil dikosongkan');
    }


}
