<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StokBahanJadiController extends Controller
{
    public function index()
    {
        return view('billing.stok-bahan-jadi.index');
    }

    public function detail(Request $request)
    {
        return view('billing.stok-bahan-jadi.detail');
    }

    public function produksi_ke(Request $request)
    {
        $product = $request->jumlah_produksi;
        
        return view('billing.stok-bahan-jadi.test',
        [
            'product' => $product
        ]);
    }
}
