<?php

namespace App\Http\Controllers;

use App\Models\Investor;
use App\Models\InvestorModal;
use App\Models\KasBesar;
use App\Models\Produksi\RencanaProduksi;
use App\Models\transaksi\InvoiceBelanja;
use App\Models\transaksi\InvoiceJual;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function index()
    {
        $np = InvoiceBelanja::where('ppn_masukan', 0)->where('tempo', 0)->count();
        $rp = RencanaProduksi::where('approved', 0)->count();
        $ij = InvoiceJual::where('lunas', 0)->count();

        return view('billing.index', [
            'np' => $np,
            'rp' => $rp,
            'ij' => $ij,
        ]);
    }

    public function nota_ppn_masukan()
    {
        $data = InvoiceBelanja::where('ppn_masukan', 0)->where('tempo', 0)->get();

        return view('billing.ppn-masukan.index', [
            'data' => $data,
        ]);
    }

    public function claim_ppn(InvoiceBelanja $invoice)
    {
        $db = new InvoiceBelanja();

        $store = $db->claim_ppn($invoice);

        return redirect()->back()->with($store['status'], $store['message']);
    }

    public function invoice_jual()
    {
        $data = InvoiceJual::with(['konsumen', 'detail'])->where('lunas', 0)->get();

        return view('billing.invoice-jual.index', [
            'data' => $data,
        ]);
    }

    public function invoice_jual_pelunasan(InvoiceJual $invoice)
    {
        $db = new InvoiceJual();

        $res = $db->pelunasan($invoice->id);

        return redirect()->back()->with($res['status'], $res['message']);
    }

    public function invoice_jual_detail(InvoiceJual $invoice)
    {
        $data = $invoice->detail;

        $groupedData = $data->groupBy(function($item, $key) {
            return $item->product_jadi->product->kategori->id;
        });

        return view('billing.invoice-jual.detail', [
            'groupedData' => $groupedData,
            'invoice' => $invoice->load('konsumen'),

        ]);
    }

    public function ppn_masuk_susulan()
    {
        $data = Investor::all();
        $im = InvestorModal::where('persentase', '>', 0)->get();

        $pp = Investor::where('nama', 'pengelola')->first()->persentase;
        $pi = Investor::where('nama', 'investor')->first()->persentase;

        return view('billing.ppn-susulan.index', [
            'data' => $data,
            'im' => $im,
            'pp' => $pp,
            'pi' => $pi,
        ]);
    }

    public function ppn_masuk_susulan_store(Request $request)
    {
        $data = $request->validate([
                    'nominal' => 'required',
                ]);

        $db = new KasBesar();

        $store = $db->ppn_masuk_susulan($data['nominal']);

        return redirect()->back()->with($store['status'], $store['message']);

    }
}
