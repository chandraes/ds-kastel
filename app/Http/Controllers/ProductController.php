<?php

namespace App\Http\Controllers;

use App\Models\db\BahanBaku;
use App\Models\db\KategoriProduct;
use App\Models\db\Kemasan;
use App\Models\db\Product;
use App\Models\db\ProductKomposisi;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {

        $data = KategoriProduct::has('product')->with(['product', 'product.komposisi'])->get();
        $bahan = BahanBaku::all();
        return view('db.product.index', [
            'data' => $data,
            'bahan' => $bahan
        ]);
    }

    public function create()
    {
        $kategori = KategoriProduct::all();
        return view('db.product.create', [
            'kategori' => $kategori
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required',
            'kode' => 'required',
            'kategori_product_id' => 'required',
            'konversi_liter' => 'required',
        ]);

        $store = Product::create($data);

        return redirect()->route('db.product')->with('success', 'Data berhasil disimpan');
    }

    public function update(Product $product, Request $request)
    {
        $data = $request->validate([
            'nama' => 'required',
            'kode' => 'required',
            'kategori_product_id' => 'required',
            'konversi_liter' => 'required',
        ]);

        $product->update($data);

        return redirect()->route('db.product')->with('success', 'Data berhasil diupdate');
    }

    public function delete(Product $product)
    {
        $product->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

    public function store_komposisi(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'bahan_baku_id' => 'required|exists:bahan_bakus,id',
            'jumlah' => 'required',
        ]);

        $check = ProductKomposisi::where('product_id', $data['product_id'])->sum('jumlah');

        if(($check + $data['jumlah']) > 100)
        {
            return redirect()->back()->with('error', 'Jumlah komposisi tidak boleh melebihi 100%');
        }

        $store = ProductKomposisi::create($data);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    public function delete_komposisi(Product $product, BahanBaku $bahan)
    {
        ProductKomposisi::where('product_id', $product->id)->where('bahan_baku_id', $bahan->id)->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
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

    public function product_jadi()
    {
        $data = KategoriProduct::has('product.product_jadi')->with(['product', 'product.product_jadi', 'product.product_jadi.kemasan'])->get();
        $product = Product::all();
        $kemasan = Kemasan::all();
        // dd($data);
        return view('db.product-jadi.index', [
            'data' => $data,
            'product' => $product,
            'kemasan' => $kemasan
        ]);
    }
}
