<?php

namespace App\Http\Controllers;

use App\Models\Konsumen;
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

    public function konsumen()
    {
        $data = Konsumen::all();

        return view('db.konsumen.index', [
            'data' => $data
        ]);
    }

    public function konsumen_store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required',
            'singkatan' => 'required',
            'cp' => 'required',
            'no_hp' => 'required',
            'npwp' => 'required',
            'alamat' => 'required',
            'ppn' => 'nullable',
            'pph' => 'nullable'
        ]);

        $data['ppn'] = $request->filled('ppn') ? 1 : 0;
        $data['pph'] = $request->filled('pph') ? 1 : 0;

        Konsumen::create($data);

        return redirect()->route('db.konsumen')->with('success', 'Data berhasil ditambahkan');
    }

    public function konsumen_update(Konsumen $konsumen, Request $request)
    {
        $data = $request->validate([
            'nama' => 'required',
            'singkatan' => 'required',
            'cp' => 'required',
            'no_hp' => 'required',
            'npwp' => 'required',
            'alamat' => 'required',
            'ppn' => 'nullable',
            'pph' => 'nullable'
        ]);

        $data['ppn'] = $request->filled('ppn') ? 1 : 0;
        $data['pph'] = $request->filled('pph') ? 1 : 0;

        $konsumen->update($data);

        return redirect()->route('db.konsumen')->with('success', 'Data berhasil diupdate');
    }

    public function konsumen_delete(Konsumen $konsumen)
    {
        $konsumen->delete();

        return redirect()->route('db.konsumen')->with('success', 'Data berhasil dihapus');
    }
}
