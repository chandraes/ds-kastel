<?php

namespace App\Http\Controllers;

use App\Models\db\Kemasan;
use App\Models\db\Product;
use App\Models\db\ProductKomposisi;
use Illuminate\Http\Request;

class ProduksiController extends Controller
{
    public function index()
    {
        $data = Product::with(['kategori'])->get();

        return view('billing.produksi.index', [
            'data' => $data
        ]);
    }

    public function getKemasan(Request $request)
    {
        $data = Kemasan::where('product_id', $request->product_id)->get();
        $komposisi = ProductKomposisi::where('product_id', $request->product_id)->get();

        if ($komposisi->isEmpty()) {
            return response()->json([
                'status' => 0,
                'message' => 'Data komposisi tidak ditemukan',
                'data' => null
            ]);
        }
        $result = [
            'status' => $data->isEmpty() ? 0 : 1,
            'message' => $data->isEmpty() ? 'Data kemasan tidak ditemukan' : 'Data kemasan ditemukan',
            'data' => $data->isEmpty() ? null : $data
        ];

        return response()->json($result);
    }

    public function getKomposisi(Request $request)
    {
        $komposisi = ProductKomposisi::with(['product','bahan_baku', 'bahan_baku.kategori', 'bahan_baku.satuan'])->where('product_id', $request->product_id)->get();
        $kemasan = Kemasan::with(['packaging'])->find($request->kemasan_id);

        $data = $komposisi->map(function ($item) {
            return $item;
        });

        $result = [
            'status' => $data->isEmpty() ? 0 : 1,
            'message' => $data->isEmpty() ? 'Data komposisi tidak ditemukan' : 'Data komposisi ditemukan',
            'data' => $data->isEmpty() ? null : $data,
            'kemasan' => $kemasan
        ];

        return response()->json($result);
    }
}
