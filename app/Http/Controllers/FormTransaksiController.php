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


}
