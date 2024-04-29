<?php

namespace App\Http\Controllers;

use App\Models\db\BahanBaku;
use App\Models\db\KategoriBahan;
use Illuminate\Http\Request;

class BahanBakuController extends Controller
{
    public function index()
    {
        $kategori = KategoriBahan::with(['bahanBaku'])->get();
        return view('db.bahan-baku.index', [
            'kategori' => $kategori,
        ]);
    }

    public function kategori_store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required'
        ]);

        KategoriBahan::create([
            'nama' => $data['nama']
        ]);

        return redirect()->route('db.bahan-baku')->with('success', 'Kategori bahan berhasil ditambahkan');
    }

    public function kategori_update(Request $request, KategoriBahan $kategori)
    {
        $data = $request->validate([
            'nama' => 'required'
        ]);

        $kategori->update([
            'nama' => $data['nama']
        ]);

        return redirect()->route('db.bahan-baku')->with('success', 'Kategori bahan berhasil diubah');
    }

    public function kategori_destroy(KategoriBahan $kategori)
    {
        $kategori->delete();
        return redirect()->route('db.bahan-baku')->with('success', 'Kategori bahan berhasil dihapus');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kategori_bahan_id' => 'required',
            'nama' => 'required',
            'konversi' => 'required',
        ]);

        BahanBaku::create([
            'kategori_bahan_id' => $data['kategori_bahan_id'],
            'nama' => $data['nama'],
            'konversi' => $data['konversi'],
        ]);


        return redirect()->route('db.bahan-baku')->with('success', 'Bahan baku berhasil ditambahkan');
    }

    public function update(BahanBaku $bahan, Request $request)
    {
        $data = $request->validate([
            'kategori_bahan_id' => 'required',
            'nama' => 'required',
            'konversi' => 'required',
        ]);

        $bahan->update([
            'kategori_bahan_id' => $data['kategori_bahan_id'],
            'nama' => $data['nama'],
            'konversi' => $data['konversi'],
        ]);

        return redirect()->route('db.bahan-baku')->with('success', 'Bahan baku berhasil diubah');
    
    }

    public function destroy(BahanBaku $bahan)
    {
        $bahan->delete();
        return redirect()->route('db.bahan-baku')->with('success', 'Bahan baku berhasil dihapus');
    }
}
