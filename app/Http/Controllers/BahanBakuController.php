<?php

namespace App\Http\Controllers;

use App\Models\db\BahanBaku;
use App\Models\db\KategoriBahan;
use App\Models\db\Satuan;
use Illuminate\Http\Request;

class BahanBakuController extends Controller
{
    public function index()
    {
        $kategori = KategoriBahan::with(['bahanBaku'])->get();
        $data = BahanBaku::with(['kategori', 'satuan'])->get();
        $satuan = Satuan::all();
        return view('db.bahan-baku.index', [
            'kategori' => $kategori,
            'data' => $data,
            'satuan' => $satuan
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
            'apa_konversi' => 'required',
            'kategori_bahan_id' => 'required',
            'nama' => 'required',
            'konversi' => 'required_if:apa_konversi,1',
            'satuan_id' => 'required_if:apa_konversi,0',
        ]);

        if ($data['apa_konversi'] == 1) {
            $data['satuan_id'] = 1;
        }

        BahanBaku::create($data);
        return redirect()->route('db.bahan-baku')->with('success', 'Bahan baku berhasil ditambahkan');
    }

    public function update(BahanBaku $bahan, Request $request)
    {
        $data = $request->validate([
            'apa_konversi' => 'required',
            'kategori_bahan_id' => 'required',
            'nama' => 'required',
            'konversi' => 'required_if:apa_konversi,1',
            'satuan_id' => 'required_if:apa_konversi,0',
        ]);

        $bahan->update($data);

        return redirect()->route('db.bahan-baku')->with('success', 'Bahan baku berhasil diubah');

    }

    public function destroy(BahanBaku $bahan)
    {
        $bahan->delete();
        return redirect()->route('db.bahan-baku')->with('success', 'Bahan baku berhasil dihapus');
    }

    public function get_barang(Request $request)
    {
        $data = BahanBaku::where('kategori_bahan_id', $request->kategori_bahan_id)->get()->toArray();
        return response()->json($data);
    }
}
