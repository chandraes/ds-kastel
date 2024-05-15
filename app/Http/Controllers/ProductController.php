<?php

namespace App\Http\Controllers;

use App\Models\db\KategoriProduct;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $data = KategoriProduct::with('products')->get();

        return view('db.product.index', [
            'data' => $data
        ]);
    }

    public function kategori_store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required',
            'kode' => 'required',
        ]);

        KategoriProduct::create($data);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    public function kategori_update(Request $request, KategoriProduct $kategori)
    {
        $data = $request->validate([
            'nama' => 'required',
            'kode' => 'required',
        ]);

        $kategori->update($data);

        return redirect()->back()->with('success', 'Data berhasil diupdate');
    }

    public function kategori_delete(KategoriProduct $kategori)
    {
        $check = $kategori->products->count();

        if ($check > 0) {
            return redirect()->back()->with('error', 'Kategori masih memiliki product. Hapus terlebih dahulu product yang terkait dengan kategori ini!');
        }

        $kategori->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}
