<?php

namespace App\Http\Controllers;

use App\Models\db\BahanBaku;
use Illuminate\Http\Request;

class PoController extends Controller
{
    public function index()
    {
        return view('po.index');
    }

    public function form()
    {
        $bahan = BahanBaku::with(['kategori'])->get();

        return view('po.form-po',[
            'bahan' => $bahan
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kepada' => 'required',
            'alamat' => 'required',
            'telepon' => 'required',
            'kategori' => 'required|array',
            'kategori.*' => 'required',
            'nama_barang' => 'required|array',
            'nama_barang.*' => 'required',
            'jumlah' => 'required|array',
            'jumlah.*' => 'required',
            'harga_satuan' => 'required|array',
            'harga_satuan.*' => 'required',
        ]);
    }

    public function rekap()
    {
        return view('po.rekap');
    }
}
