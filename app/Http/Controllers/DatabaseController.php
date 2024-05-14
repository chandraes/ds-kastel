<?php

namespace App\Http\Controllers;

use App\Models\db\Satuan;
use App\Models\db\Supplier;
use App\Models\Konsumen;
use App\Models\Pengelola;
use App\Models\Product;
use Illuminate\Http\Request;

class DatabaseController extends Controller
{
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

    public function satuan()
    {
        $data = Satuan::all();

        return view('db.satuan.index', [
            'data' => $data
        ]);
    }

    public function satuan_store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required'
        ]);

        Satuan::create($data);

        return redirect()->route('db.satuan')->with('success', 'Data berhasil ditambahkan');
    }

    public function satuan_update(Satuan $satuan, Request $request)
    {
        $data = $request->validate([
            'nama' => 'required'
        ]);

        $satuan->update($data);

        return redirect()->route('db.satuan')->with('success', 'Data berhasil diupdate');
    }

    public function satuan_delete(Satuan $satuan)
    {
        $satuan->delete();

        return redirect()->route('db.satuan')->with('success', 'Data berhasil dihapus');
    }

    public function supplier()
    {
        $data = Supplier::all();

        return view('db.supplier.index', [
            'data' => $data
        ]);
    }

    public function supplier_store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'npwp' => 'required',
            'cp' => 'required',
            'no_hp' => 'required',
            'no_rek' => 'required',
            'bank' => 'required',
            'nama_rek' => 'required',
            'status' => 'required',
        ]);

        $db = new Supplier();

        $store = $db->createSupplier($data);

        return redirect()->route('db.supplier')->with($store['status'], $store['message']);
    }

    public function supplier_update(Request $request, Supplier $supplier)
    {
        $data = $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'npwp' => 'required',
            'cp' => 'required',
            'no_hp' => 'required',
            'no_rek' => 'required',
            'bank' => 'required',
            'nama_rek' => 'required',
            'status' => 'required',
        ]);

        $supplier->update($data);

        return redirect()->route('db.supplier')->with('success', 'Data berhasil diupdate');
    }

    public function supplier_delete(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()->route('db.supplier')->with('success', 'Data berhasil dihapus');
    }
}
