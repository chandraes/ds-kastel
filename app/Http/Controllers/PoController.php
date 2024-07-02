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

    }

    public function rekap()
    {
        return view('po.rekap');
    }
}
