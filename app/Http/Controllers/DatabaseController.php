<?php

namespace App\Http\Controllers;

use App\Models\Pengelola;
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

    public function pengelola()
    {
        $data = Pengelola::all();

        return view('db.pengelola.index', [
            'data' => $data
        ]);
    }

    public function pengelola_store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required',
            'no_hp' => 'required',
            'persentase' => 'required',
            'no_rek' => 'required',
            'bank' => 'required',
            'nama_rek' => 'required'
        ]);

        $check = Pengelola::sum('persentase') + $data['persentase'];

        if ($check > 100) {
            return redirect()->route('db.pengelola')->with('error', 'Persentase tidak boleh melebihi 100%');
        }

        Pengelola::create($data);

        return redirect()->route('db.pengelola')->with('success', 'Data berhasil ditambahkan');
    }

    public function pengelola_update(Pengelola $pengelola, Request $request)
    {
        $data = $request->validate([
            'nama' => 'required',
            'no_hp' => 'required',
            'persentase' => 'required',
            'no_rek' => 'required',
            'bank' => 'required',
            'nama_rek' => 'required'
        ]);

        $check = Pengelola::whereNot('id', $pengelola->id)->sum('persentase') + $data['persentase'];

        if ($check > 100) {
            return redirect()->route('db.pengelola')->with('error', 'Persentase tidak boleh melebihi 100%');
        }

        $pengelola->update($data);

        return redirect()->route('db.pengelola')->with('success', 'Data berhasil diupdate');
    }

    public function pengelola_delete(Pengelola $pengelola)
    {
        $pengelola->delete();

        return redirect()->route('db.pengelola')->with('success', 'Data berhasil dihapus');
    }
}
