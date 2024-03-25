<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class DatabaseController extends Controller
{
    public function product()
    {
        $data = Product::all();
        return view('db.product.index', [
            'data' => $data
        ]);
    }

    public function product_store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required'
        ]);

        Product::create($data);

        return redirect()->route('db.product')->with('success', 'Data berhasil ditambahkan');
    }

    public function product_update(Product $product, Request $request)
    {
        $data = $request->validate([
            'nama' => 'required'
        ]);

        $product->update($data);

        return redirect()->route('db.product')->with('success', 'Data berhasil diupdate');
    }

    public function product_delete(Product $product)
    {
        $product->delete();

        return redirect()->route('db.product')->with('success', 'Data berhasil dihapus');
    }
}
