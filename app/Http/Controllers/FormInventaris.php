<?php

namespace App\Http\Controllers;

use App\Models\db\InventarisJenis;
use App\Models\db\InventarisKategori;
use App\Models\db\Pajak;
use Illuminate\Http\Request;

class FormInventaris extends Controller
{
    public function index()
    {
        $ppn = Pajak::where('untuk', 'ppn')->first()->persen;
        $data = InventarisKategori::all();
        return view('billing.form-inventaris.beli', [
            'ppn' => $ppn,
            'data' => $data
        ]);
    }

    public function getJenis(Request $request)
    {
        $data = InventarisJenis::with('kategori')->where('kategori_id', $request->kategori_id)->get();
        $result = [
            'status' => $data->isEmpty() ? 0 : 1,
            'message' => $data->isEmpty() ? 'Data jenis inventaris tidak ditemukan' : 'Data jenis inventaris ditemukan',
            'data' => $data->isEmpty() ? null : $data
        ];

        return response()->json($result);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            
        ]);
    }
}
