<?php

namespace App\Http\Controllers;

use App\Models\db\KategoriBahan;
use App\Models\db\Satuan;
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
        $satuan = Satuan::all();

        return view('billing.form-transaksi.bahan-baku.beli', [
            'kategori' => $kategori,
            'keranjang' => $keranjang,
            'satuan' => $satuan
        ]);
    }

    public function keranjang_store(Request $request)
    {
        $data = $request->validate([
            'apa_konversi' => 'required',
            'bahan_baku_id' => 'required|exists:bahan_bakus,id',
            'jumlah' => 'required|numeric|min:1',
            'harga' => 'required',
            'satuan_id' => 'required|exists:satuans,id',
            'add_fee' => 'required'
        ]);

        $data['user_id'] = auth()->id();
        $data['harga'] = str_replace('.', '', $data['harga']);
        $data['total'] = $data['jumlah'] * $data['harga'];
        $data['add_fee'] = str_replace('.', '', $data['add_fee']);

        unset($data['apa_konversi']);

        Keranjang::create($data);

        return redirect()->route('billing.form-transaksi.bahan-baku.beli')->with('success', 'Berhasil ditambahkan ke keranjang');
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
