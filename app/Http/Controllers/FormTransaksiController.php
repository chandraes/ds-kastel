<?php

namespace App\Http\Controllers;

use App\Models\db\KategoriBahan;
use App\Models\db\Satuan;
use App\Models\db\Supplier;
use App\Models\transaksi\InvoiceBelanja;
use App\Models\transaksi\Keranjang;
use Illuminate\Http\Request;

class FormTransaksiController extends Controller
{
    public function index()
    {
        $hb = InvoiceBelanja::where('tempo', 1)->count();
        return view('billing.form-transaksi.index', [
            'hb' => $hb,
        ]);
    }

    public function hutang_belanja()
    {
        $data = InvoiceBelanja::where('tempo', 1)->get();

        return view('billing.form-transaksi.hutang-belanja.index', [
            'data' => $data,
        ]);
    }

    public function hutang_belanja_bayar(InvoiceBelanja $invoice)
    {

        $db = new InvoiceBelanja();

        $store = $db->bayar_hutang($invoice);

        return redirect()->back()->with($store['status'], $store['message']);
    }

    public function bahan_baku_beli()
    {
        if (Supplier::where('status', 1)->count() == 0) {
            return redirect()->route('db.supplier')->with('error', 'Supplier belum ada, silahkan tambahkan supplier terlebih dahulu');
        }
        $supplier = Supplier::where('status', 1)->get();
        $kategori = KategoriBahan::all();
        $keranjang = Keranjang::with(['bahan_baku'])->where('user_id', auth()->id())->where('tempo', 0)->get();
        $satuan = Satuan::all();

        return view('billing.form-transaksi.bahan-baku.beli', [
            'kategori' => $kategori,
            'keranjang' => $keranjang,
            'satuan' => $satuan,
            'supplier' => $supplier
        ]);
    }

    public function keranjang_store(Request $request)
    {
        $data = $request->validate([
            'apa_konversi' => 'required',
            'bahan_baku_id' => 'required|exists:bahan_bakus,id',
            'jumlah' => 'required|numeric|min:1',
            'harga' => 'required',
            'satuan_id' => 'required_if:apa_konversi,==,0',
            'add_fee' => 'required'
        ]);

        $data['user_id'] = auth()->user()->id;

        if($data['apa_konversi'] == 1){
            $data['satuan_id'] = 2;
        }

        $data['harga'] = str_replace('.', '', $data['harga']);
        $data['total'] = $data['jumlah'] * $data['harga'];
        $data['add_fee'] = str_replace('.', '', $data['add_fee']);

        unset($data['apa_konversi']);

        Keranjang::create($data);

        return redirect()->route('billing.form-transaksi.bahan-baku.beli')->with('success', 'Berhasil ditambahkan ke keranjang');
    }

    public function keranjang_delete(Keranjang $keranjang)
    {
        $keranjang->delete();

        return redirect()->back()->with('success', 'Berhasil dihapus dari keranjang');
    }

    public function keranjang_empty()
    {
        $count = Keranjang::where('user_id', auth()->id())->where('tempo', 0)->count();

        if ($count == 0) {
            return redirect()->back()->with('error', 'Keranjang kosong');
        }

        Keranjang::where('user_id', auth()->id())->where('tempo', 0)->delete();

        return redirect()->route('billing.form-transaksi.bahan-baku.beli')->with('success', 'Keranjang berhasil dikosongkan');
    }

    public function keranjang_checkout(Request $request)
    {
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes
        ini_set('memory_limit', '512M');

        $data = $request->validate([
            'uraian' => 'required',
            'ppn' => 'required',
            'diskon' => 'required',
            'nama_rek' => 'required',
            'no_rek' => 'required',
            'bank' => 'required',
            'supplier_id' => 'required|exists:suppliers,id'
        ]);

        $db = new Keranjang();

        if ($db->where('user_id', auth()->id())->where('tempo', 0)->count() == 0) {
            return redirect()->back()->with('error', 'Keranjang kosong');
        }

        $store = $db->checkout($data);

        return redirect()->back()->with($store['status'], $store['message']);
    }

    public function bahan_baku_beli_tempo()
    {
        if (Supplier::where('status', 1)->count() == 0) {
            return redirect()->route('db.supplier')->with('error', 'Supplier belum ada, silahkan tambahkan supplier terlebih dahulu');
        }
        $supplier = Supplier::where('status', 1)->get();
        $kategori = KategoriBahan::all();
        $keranjang = Keranjang::with(['bahan_baku'])->where('user_id', auth()->id())->where('tempo', 1)->get();
        $satuan = Satuan::all();

        return view('billing.form-transaksi.bahan-baku.tempo.index', [
            'kategori' => $kategori,
            'keranjang' => $keranjang,
            'satuan' => $satuan,
            'supplier' => $supplier
        ]);
    }

    public function keranjang_tempo_store(Request $request)
    {
        $data = $request->validate([
            'apa_konversi' => 'required',
            'bahan_baku_id' => 'required|exists:bahan_bakus,id',
            'jumlah' => 'required|numeric|min:1',
            'harga' => 'required',
            'satuan_id' => 'required_if:apa_konversi,==,0',
            'add_fee' => 'required'
        ]);

        $data['user_id'] = auth()->user()->id;

        if($data['apa_konversi'] == 1){
            $data['satuan_id'] = 2;
        }

        $data['tempo'] = 1;
        $data['harga'] = str_replace('.', '', $data['harga']);
        $data['total'] = $data['jumlah'] * $data['harga'];
        $data['add_fee'] = str_replace('.', '', $data['add_fee']);

        unset($data['apa_konversi']);

        Keranjang::create($data);

        return redirect()->back()->with('success', 'Berhasil ditambahkan ke keranjang');
    }

    public function keranjang_tempo_empty()
    {
        $count = Keranjang::where('user_id', auth()->id())->where('tempo', 1)->count();

        if ($count == 0) {
            return redirect()->back()->with('error', 'Keranjang kosong');
        }

        Keranjang::where('user_id', auth()->id())->where('tempo', 1)->delete();

        return redirect()->back()->with('success', 'Keranjang berhasil dikosongkan');
    }

    public function keranjang_tempo_checkout(Request $request)
    {
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes
        ini_set('memory_limit', '512M');

        $data = $request->validate([
            'uraian' => 'required',
            'dp' => 'required',
            'jatuh_tempo' => 'required',
            'ppn' => 'required',
            'diskon' => 'required',
            'nama_rek' => 'required',
            'no_rek' => 'required',
            'bank' => 'required',
            'supplier_id' => 'required|exists:suppliers,id'
        ]);

        $db = new Keranjang();

        if ($db->where('user_id', auth()->id())->where('tempo', 1)->count() == 0) {
            return redirect()->back()->with('error', 'Keranjang kosong');
        }

        $store = $db->checkoutTempo($data);

        return redirect()->back()->with($store['status'], $store['message']);
    }


}
