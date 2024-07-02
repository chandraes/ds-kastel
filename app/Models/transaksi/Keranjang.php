<?php

namespace App\Models\transaksi;

use App\Models\db\BahanBaku;
use App\Models\db\Kemasan;
use App\Models\db\Packaging;
use App\Models\db\Pajak;
use App\Models\db\RekapBahanBaku;
use App\Models\db\Satuan;
use App\Models\GroupWa;
use App\Models\KasBesar;
use App\Models\PesanWa;
use App\Models\User;
use App\Services\StarSender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Keranjang extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $appends = ['nf_jumlah'];

    public function getNfJumlahAttribute()
    {
        return number_format($this->jumlah, 0, ',','.');
    }

    public function kemasan()
    {
        return $this->belongsTo(Kemasan::class);
    }

    public function packaging()
    {
        return $this->belongsTo(Packaging::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bahan_baku()
    {
        return $this->belongsTo(BahanBaku::class);
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }

    public function checkout($data)
    {
        $kas = new KasBesar();

        $belanja = $this->where('user_id', auth()->user()->id)->where('tempo', 0)->get();
        $data['add_fee'] = str_replace('.', '', $data['add_fee']);
        $data['diskon'] = str_replace('.', '', $data['diskon']);

        if($data['ppn'] == 1)
        {
            $ppn = Pajak::where('untuk', 'ppn')->first()->persen;
            $data['ppn'] = ($ppn/100) * ($belanja->sum('total')-$data['diskon']);

        } else {
            $data['ppn'] = 0;
        }

        $data['total'] = $belanja->sum('total') + $data['add_fee'] + $data['ppn'] - $data['diskon'];

        $saldo = $kas->saldoTerakhir();

        if ($saldo < $data['total']) {
            return [
                'status' => 'error',
                'message' => 'Saldo tidak mencukupi'
            ];
        }

        $pesan = '';

        try {

            DB::beginTransaction();
            $jenis = 1;
            $store_inv = $this->invoice_checkout($data, $jenis);

            $store = $this->kas_checkout($data, $store_inv->id);

            $this->update_bahan();

            $this->where('user_id', auth()->user()->id)->where('tempo', 0)->delete();

            DB::commit();

            $ppnMasukan = InvoiceBelanja::where('ppn_masukan', 0)->sum('ppn');

            $pesan = "🔴🔴🔴🔴🔴🔴🔴🔴🔴\n".
                        "*FORM BELI BAHAN BAKU*\n".
                        "🔴🔴🔴🔴🔴🔴🔴🔴🔴\n\n".
                        "Uraian :  *".$store->uraian."*\n\n".
                        "Nilai    :  *Rp. ".number_format($store->nominal, 0, ',', '.')."*\n\n".
                        "Ditransfer ke rek:\n\n".
                        "Bank      : ".$store->bank."\n".
                        "Nama    : ".$store->nama_rek."\n".
                        "No. Rek : ".$store->no_rek."\n\n".
                        "==========================\n".
                        "Sisa Saldo Kas Besar : \n".
                        "Rp. ".number_format($store->saldo, 0, ',', '.')."\n\n".
                        "Total Modal Investor : \n".
                        "Rp. ".number_format($store->modal_investor_terakhir, 0, ',', '.')."\n\n".
                        "Total PPn Masukan : \n".
                        "Rp. ".number_format($ppnMasukan, 0, ',', '.')."\n\n".
                        "Terima kasih 🙏🙏🙏\n";

            $group = GroupWa::where('untuk', 'kas-besar')->first()->nama_group;

            $this->sendWa($group, $pesan);

            $result = [
                'status' => 'success',
                'message' => 'Data berhasil disimpan!'
            ];

        } catch (\Exception $e) {

            DB::rollBack();

            $result = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];

            return $result;
        }

        return $result;

    }

    private function kas_checkout($data, $invoice_id)
    {
        $db = new KasBesar();

        $kas = [
            'uraian' => $data['uraian'],
            'jenis' => 0,
            'nominal' => $data['total'],
            'saldo' => $db->saldoTerakhir() - $data['total'],
            'no_rek' => $data['no_rek'],
            'nama_rek' => $data['nama_rek'],
            'bank' => $data['bank'],
            'modal_investor_terakhir' => $db->modalInvestorTerakhir(),
            'invoice_belanja_id' => $invoice_id,
        ];

        $store = $db->create($kas);

        return $store;

    }

    private function update_bahan()
    {
        $keranjang = $this->where('user_id', auth()->user()->id)->where('jenis', 1)->where('tempo', 0)->get();

        // Get all the bahan_baku_ids from the keranjang
        $bahan_baku_ids = $keranjang->pluck('bahan_baku_id')->toArray();

        // Get all the BahanBaku records at once
        $bahan_bakus = BahanBaku::whereIn('id', $bahan_baku_ids)->get()->keyBy('id');

        foreach ($keranjang as $item) {
            $bahan = $bahan_bakus[$item->bahan_baku_id];

            $bahan->stock += $item->jumlah;
            $bahan->save();
        }

        return true;
    }

    private function update_kemasan($tempo)
    {
        $keranjang = $this->where('user_id', auth()->user()->id)->where('jenis', 2)->where('tempo', $tempo)->get();

        // Get all the bahan_baku_ids from the keranjang
        $bahan_baku_ids = $keranjang->pluck('kemasan_id')->toArray();

        // Get all the BahanBaku records at once
        $bahan_bakus = Kemasan::whereIn('id', $bahan_baku_ids)->get()->keyBy('id');

        foreach ($keranjang as $item) {
            $bahan = $bahan_bakus[$item->kemasan_id];

            $bahan->stok += $item->jumlah;
            $bahan->save();
        }

        return true;
    }

    private function update_packaging($tempo)
    {
        $keranjang = $this->where('user_id', auth()->user()->id)->where('jenis', 3)->where('tempo', $tempo)->get();

        // Get all the bahan_baku_ids from the keranjang
        $bahan_baku_ids = $keranjang->pluck('packaging_id')->toArray();

        // Get all the BahanBaku records at once
        $bahan_bakus = Packaging::whereIn('id', $bahan_baku_ids)->get()->keyBy('id');

        foreach ($keranjang as $item) {
            $bahan = $bahan_bakus[$item->packaging_id];

            $bahan->stok += $item->jumlah;
            $bahan->save();
        }

        return true;
    }

    private function invoice_checkout($data, $jenis)
    {
        $db = new InvoiceBelanja();

        $data['ppn'] = str_replace('.', '', $data['ppn']);

        $data['ppn_masukan'] = $data['ppn'] == 0 ? 1 : 0;

        $invoice = [
            'nomor_bb' => $db->generateKode(),
            'uraian' => $data['uraian'],
            'ppn' => $data['ppn'],
            'add_fee' => $data['add_fee'],
            'diskon' => str_replace('.', '', $data['diskon']),
            'total' => $data['total'],
            'nama_rek' => $data['nama_rek'],
            'no_rek' => $data['no_rek'],
            'bank' => $data['bank'],
            'ppn_masukan' => $data['ppn_masukan'],
            'supplier_id' => $data['supplier_id'],
        ];

        $store = $db->create($invoice);

        $keranjang = $this->where('user_id', auth()->user()->id)->where('jenis', $jenis)->where('tempo', 0)->get();

        foreach ($keranjang as $item) {

            $baseRekap = [
                'jenis' => 1, //Pembelian
                'uraian' => $data['uraian'],
                'jumlah' => $item->jumlah,
                'harga' => $item->harga,
                'satuan_id' => $item->satuan_id,
                'add_fee' => $item->add_fee,
            ];

            switch ($jenis) {
                case 1:
                    $baseRekap['bahan_baku_id'] = $item->bahan_baku_id;
                    $baseRekap['nama'] = $item->bahan_baku->nama;
                    break;
                case 2:
                    $baseRekap['kemasan_id'] = $item->kemasan_id;
                    $baseRekap['nama'] = $item->kemasan->nama;
                    break;
                case 3:
                    $baseRekap['packaging_id'] = $item->packaging_id;
                    $baseRekap['nama'] = $item->packaging->nama;
                    break;
            }

            $rekap = RekapBahanBaku::create($baseRekap);

            $store->detail()->create([
                'invoice_belanja_id' => $store->id,
                'rekap_bahan_baku_id' => $rekap->id,
            ]);
        }

        return $store;
    }

    private function sendWa($tujuan, $pesan)
    {
        $send = new StarSender($tujuan, $pesan);
        $res = $send->sendGroup();

        $status = ($res == 'true') ? 1 : 0;

        PesanWa::create([
            'pesan' => $pesan,
            'tujuan' => $tujuan,
            'status' => $status,
        ]);

        return true;
    }

    public function checkoutTempo($data)
    {
        $kas = new KasBesar();

        $belanja = $this->where('user_id', auth()->user()->id)->where('jenis', 1)->where('tempo', 1)->get();
        $ppn = Pajak::where('untuk', 'ppn')->first()->persen;

        if($data['ppn'] == 1)
        {
            $data['ppn'] = ($ppn/100) * ($belanja->sum('total') + $belanja->sum('add_fee'));
        }

        $data['dp'] = str_replace('.', '', $data['dp']);

        if($data['dp_ppn'] == 1)
        {
            $data['dp_ppn'] = ($ppn/100) * $data['dp'];
            $data['sisa_ppn'] = $data['ppn'] - $data['dp_ppn'];
        } else {
            $data['dp_ppn'] = 0;
            $data['sisa_ppn'] = $data['ppn'];
        }

        $data['tempo'] = 1;

        if ($kas->saldoTerakhir() < ($data['dp'] + $data['dp_ppn'])) {
            return [
                'status' => 'error',
                'message' => 'Saldo kas besar tidak mencukupi. Saldo saat ini : '.number_format($kas->saldoTerakhir(), 0, ',', '.')
            ];
        }

        $data['jatuh_tempo'] = Carbon::createFromFormat('d-m-Y', $data['jatuh_tempo'])->format('Y-m-d');
        $data['diskon'] = str_replace('.', '', $data['diskon']);

        $data['total'] = $belanja->sum('total') + $belanja->sum('add_fee') + $data['ppn'] - $data['diskon'];

        $data['sisa'] = $data['total'] - $data['dp'] - $data['dp_ppn'];

        // $data['jatuh_tempo'] = convert from d-m-Y to Y-m-d

        $pesan = '';

        try {

            DB::beginTransaction();
            $jenis = 1;

            $store_inv = $this->invoice_checkout_tempo($data, $jenis);

            if ($data['dp'] > 0) {

                $store = $this->kas_checkout_tempo($data, $store_inv->id);

                $dbInvoice = new InvoiceBelanja();
                $ppnMasukan = $dbInvoice->sumNilaiPpn();

                $pesan = "🔴🔴🔴🔴🔴🔴🔴🔴🔴\n".
                            "*FORM BELI BAHAN BAKU*\n".
                            "🔴🔴🔴🔴🔴🔴🔴🔴🔴\n\n".
                            "Uraian :  *".$store->uraian."*\n\n".
                            "Nilai    :  *Rp. ".number_format($store->nominal, 0, ',', '.')."*\n\n".
                            "Ditransfer ke rek:\n\n".
                            "Bank      : ".$store->bank."\n".
                            "Nama    : ".$store->nama_rek."\n".
                            "No. Rek : ".$store->no_rek."\n\n".
                            "==========================\n".
                            "Sisa Saldo Kas Besar : \n".
                            "Rp. ".number_format($store->saldo, 0, ',', '.')."\n\n".
                            "Total Modal Investor : \n".
                            "Rp. ".number_format($store->modal_investor_terakhir, 0, ',', '.')."\n\n".
                            "Total PPn Masukan : \n".
                            "Rp. ".number_format($ppnMasukan, 0, ',', '.')."\n\n".
                            "Terima kasih 🙏🙏🙏\n";

                $group = GroupWa::where('untuk', 'kas-besar')->first()->nama_group;

                $this->sendWa($group, $pesan);

            }

            $this->update_bahan_tempo();

            $this->where('user_id', auth()->user()->id)->where('jenis', 1)->where('tempo', 1)->delete();

            DB::commit();

            $result = [
                'status' => 'success',
                'message' => 'Data berhasil disimpan!'
            ];

        } catch (\Exception $e) {

            DB::rollBack();

            $result = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];

            return $result;
        }

        return $result;
    }

    private function invoice_checkout_tempo($data, $jenis)
    {
        $db = new InvoiceBelanja();

        $data['ppn'] = str_replace('.', '', $data['ppn']);

        $data['ppn_masukan'] = $data['ppn'] == 0 ? 1 : 0;

        $invoice = [
            'nomor_bb' => $db->generateKode(),
            'uraian' => $data['uraian'],
            'ppn' => $data['ppn'],
            'diskon' => str_replace('.', '', $data['diskon']),
            'total' => $data['total'],
            'dp' => $data['dp'],
            'dp_ppn' => $data['dp_ppn'],
            'sisa_ppn' => $data['sisa_ppn'],
            'sisa' => $data['sisa'],
            'nama_rek' => $data['nama_rek'],
            'no_rek' => $data['no_rek'],
            'bank' => $data['bank'],
            'ppn_masukan' => $data['ppn_masukan'],
            'supplier_id' => $data['supplier_id'],
            'tempo' => 1,
            'jatuh_tempo' => $data['jatuh_tempo'],
        ];

        $store = $db->create($invoice);

        $keranjang = $this->where('user_id', auth()->user()->id)->where('jenis', $jenis)->where('tempo', 1)->get();

        foreach ($keranjang as $item) {

            $baseRekap = [
                'jenis' => 0, //Pembelian
                'jumlah' => $item->jumlah,
                'harga' => $item->harga,
                'satuan_id' => $item->satuan_id,
                'add_fee' => $item->add_fee,
            ];

            switch ($jenis) {
                case 1:
                    $baseRekap['bahan_baku_id'] = $item->bahan_baku_id;
                    $baseRekap['nama'] = $item->bahan_baku->nama;
                    break;
                case 2:
                    $baseRekap['kemasan_id'] = $item->kemasan_id;
                    $baseRekap['nama'] = $item->kemasan->nama;
                    break;
                case 3:
                    $baseRekap['packaging_id'] = $item->packaging_id;
                    $baseRekap['nama'] = $item->packaging->nama;
                    break;
            }

            $rekap = RekapBahanBaku::create($baseRekap);

            $store->detail()->create([
                'invoice_belanja_id' => $store->id,
                'rekap_bahan_baku_id' => $rekap->id,
            ]);
        }

        return $store;
    }

    private function kas_checkout_tempo($data, $invoice_id)
    {
        $db = new KasBesar();

        $kas = [
            'uraian' => 'DP '.$data['uraian'],
            'jenis' => 0,
            'nominal' => $data['dp']+ $data['dp_ppn'],
            'saldo' => $db->saldoTerakhir() - ($data['dp'] + $data['dp_ppn']),
            'no_rek' => $data['no_rek'],
            'nama_rek' => $data['nama_rek'],
            'bank' => $data['bank'],
            'modal_investor_terakhir' => $db->modalInvestorTerakhir(),
            'invoice_belanja_id' => $invoice_id,
        ];

        $store = $db->create($kas);

        return $store;

    }

    private function update_bahan_tempo()
    {
        $keranjang = $this->where('user_id', auth()->user()->id)->where('jenis', 1)->where('tempo', 1)->get();

        // Get all the bahan_baku_ids from the keranjang
        $bahan_baku_ids = $keranjang->pluck('bahan_baku_id')->toArray();

        // Get all the BahanBaku records at once
        $bahan_bakus = BahanBaku::whereIn('id', $bahan_baku_ids)->get()->keyBy('id');

        foreach ($keranjang as $item) {
            $bahan = $bahan_bakus[$item->bahan_baku_id];

            $bahan->stock += $item->jumlah;
            $bahan->save();
        }

        return true;
    }

    public function checkoutKemasanTempo($data)
    {
        $kas = new KasBesar();

        $belanja = $this->where('user_id', auth()->user()->id)->where('jenis', 2)->where('tempo', 1)->get();
        $ppn = Pajak::where('untuk', 'ppn')->first()->persen;

        if($data['ppn'] == 1)
        {
            $data['ppn'] = ($ppn/100) * ($belanja->sum('total') + $belanja->sum('add_fee'));
        }

        $data['dp'] = str_replace('.', '', $data['dp']);

        if($data['dp_ppn'] == 1)
        {
            $data['dp_ppn'] = ($ppn/100) * $data['dp'];
            $data['sisa_ppn'] = $data['ppn'] - $data['dp_ppn'];
        } else {
            $data['dp_ppn'] = 0;
            $data['sisa_ppn'] = $data['ppn'];
        }

        $data['tempo'] = 1;

        if ($kas->saldoTerakhir() < ($data['dp'] + $data['dp_ppn'])) {
            return [
                'status' => 'error',
                'message' => 'Saldo kas besar tidak mencukupi. Saldo saat ini : '.number_format($kas->saldoTerakhir(), 0, ',', '.')
            ];
        }

        $data['jatuh_tempo'] = Carbon::createFromFormat('d-m-Y', $data['jatuh_tempo'])->format('Y-m-d');
        $data['diskon'] = str_replace('.', '', $data['diskon']);

        $data['total'] = $belanja->sum('total') + $belanja->sum('add_fee') + $data['ppn'] - $data['diskon'];

        $data['sisa'] = $data['total'] - $data['dp'] - $data['dp_ppn'];

        $pesan = '';

        try {

            DB::beginTransaction();

            $jenis = 2;

            $store_inv = $this->invoice_checkout_tempo($data, $jenis);

            if ($data['dp'] > 0) {
                $store = $this->kas_checkout_tempo($data, $store_inv->id);

                $dbInvoice = new InvoiceBelanja();
                $ppnMasukan = $dbInvoice->sumNilaiPpn();

                $pesan = "🔴🔴🔴🔴🔴🔴🔴🔴🔴\n".
                            "*FORM BELI KEMASAN*\n".
                            "🔴🔴🔴🔴🔴🔴🔴🔴🔴\n\n".
                            "Uraian :  *".$store->uraian."*\n\n".
                            "Nilai    :  *Rp. ".number_format($store->nominal, 0, ',', '.')."*\n\n".
                            "Ditransfer ke rek:\n\n".
                            "Bank      : ".$store->bank."\n".
                            "Nama    : ".$store->nama_rek."\n".
                            "No. Rek : ".$store->no_rek."\n\n".
                            "==========================\n".
                            "Sisa Saldo Kas Besar : \n".
                            "Rp. ".number_format($store->saldo, 0, ',', '.')."\n\n".
                            "Total Modal Investor : \n".
                            "Rp. ".number_format($store->modal_investor_terakhir, 0, ',', '.')."\n\n".
                            "Total PPn Masukan : \n".
                            "Rp. ".number_format($ppnMasukan, 0, ',', '.')."\n\n".
                            "Terima kasih 🙏🙏🙏\n";

                $group = GroupWa::where('untuk', 'kas-besar')->first()->nama_group;

                $this->sendWa($group, $pesan);

            }

            $this->update_kemasan(1);

            $this->where('user_id', auth()->user()->id)->where('jenis', 2)->where('tempo', 1)->delete();

            DB::commit();

            $result = [
                'status' => 'success',
                'message' => 'Data berhasil disimpan!'
            ];

        } catch (\Exception $e) {

            DB::rollBack();

            $result = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];

            return $result;
        }

        return $result;
    }

    public function checkoutKemasan($data)
    {
        $kas = new KasBesar();

        $belanja = $this->where('user_id', auth()->user()->id)->where('jenis', 2)->where('tempo', 0)->get();
        $data['add_fee'] = str_replace('.', '', $data['add_fee']);
        if($data['ppn'] == 1)
        {
            $ppn = Pajak::where('untuk', 'ppn')->first()->persen;
            $data['ppn'] = ($ppn/100) * ($belanja->sum('total'));

        }else {
            $data['ppn'] = 0;
        }

        $data['diskon'] = str_replace('.', '', $data['diskon']);

        $data['total'] = $belanja->sum('total') + $data['add_fee'] + $data['ppn'] - $data['diskon'];

        $saldo = $kas->saldoTerakhir();

        if ($saldo < $data['total']) {
            return [
                'status' => 'error',
                'message' => 'Saldo tidak mencukupi'
            ];
        }

        $pesan = '';

        try {

            DB::beginTransaction();
            $jenis = 2;
            $store_inv = $this->invoice_checkout($data, $jenis);

            $store = $this->kas_checkout($data, $store_inv->id);

            $this->update_kemasan(0);

            $this->where('user_id', auth()->user()->id)->where('jenis', 2)->where('tempo', 0)->delete();

            DB::commit();

            $dbInvoice = new InvoiceBelanja();
            $ppnMasukan = $dbInvoice->sumNilaiPpn();

            $pesan = "🔴🔴🔴🔴🔴🔴🔴🔴🔴\n".
                        "*FORM BELI KEMASAN*\n".
                        "🔴🔴🔴🔴🔴🔴🔴🔴🔴\n\n".
                        "Uraian :  *".$store->uraian."*\n\n".
                        "Nilai    :  *Rp. ".number_format($store->nominal, 0, ',', '.')."*\n\n".
                        "Ditransfer ke rek:\n\n".
                        "Bank      : ".$store->bank."\n".
                        "Nama    : ".$store->nama_rek."\n".
                        "No. Rek : ".$store->no_rek."\n\n".
                        "==========================\n".
                        "Sisa Saldo Kas Besar : \n".
                        "Rp. ".number_format($store->saldo, 0, ',', '.')."\n\n".
                        "Total Modal Investor : \n".
                        "Rp. ".number_format($store->modal_investor_terakhir, 0, ',', '.')."\n\n".
                        "Total PPn Masukan : \n".
                        "Rp. ".number_format($ppnMasukan, 0, ',', '.')."\n\n".
                        "Terima kasih 🙏🙏🙏\n";

            $group = GroupWa::where('untuk', 'kas-besar')->first()->nama_group;

            $this->sendWa($group, $pesan);

            $result = [
                'status' => 'success',
                'message' => 'Data berhasil disimpan!'
            ];

        } catch (\Exception $e) {

            DB::rollBack();

            $result = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];

            return $result;
        }

        return $result;
    }

    public function checkoutPackaging($data)
    {
        $kas = new KasBesar();

        $belanja = $this->where('user_id', auth()->user()->id)->where('jenis', 3)->where('tempo', 0)->get();
        $data['add_fee'] = str_replace('.', '', $data['add_fee']);

        if($data['ppn'] == 1)
        {
            $ppn = Pajak::where('untuk', 'ppn')->first()->persen;
            $data['ppn'] = ($ppn/100) * ($belanja->sum('total'));
        } else {
            $data['ppn'] = 0;
        }

        $data['diskon'] = str_replace('.', '', $data['diskon']);

        $data['total'] = $belanja->sum('total') + $data['add_fee'] + $data['ppn'] - $data['diskon'];

        $saldo = $kas->saldoTerakhir();

        if ($saldo < $data['total']) {
            return [
                'status' => 'error',
                'message' => 'Saldo tidak mencukupi'
            ];
        }

        $pesan = '';

        try {

            DB::beginTransaction();
            $jenis = 3;
            $store_inv = $this->invoice_checkout($data, $jenis);

            $store = $this->kas_checkout($data, $store_inv->id);

            $this->update_packaging(0);

            $this->where('user_id', auth()->user()->id)->where('jenis', 3)->where('tempo', 0)->delete();

            DB::commit();

            $dbInvoice = new InvoiceBelanja();
            $ppnMasukan = $dbInvoice->sumNilaiPpn();

            $pesan = "🔴🔴🔴🔴🔴🔴🔴🔴🔴\n".
                        "*FORM BELI PACKAGING*\n".
                        "🔴🔴🔴🔴🔴🔴🔴🔴🔴\n\n".
                        "Uraian :  *".$store->uraian."*\n\n".
                        "Nilai    :  *Rp. ".number_format($store->nominal, 0, ',', '.')."*\n\n".
                        "Ditransfer ke rek:\n\n".
                        "Bank      : ".$store->bank."\n".
                        "Nama    : ".$store->nama_rek."\n".
                        "No. Rek : ".$store->no_rek."\n\n".
                        "==========================\n".
                        "Sisa Saldo Kas Besar : \n".
                        "Rp. ".number_format($store->saldo, 0, ',', '.')."\n\n".
                        "Total Modal Investor : \n".
                        "Rp. ".number_format($store->modal_investor_terakhir, 0, ',', '.')."\n\n".
                        "Total PPn Masukan : \n".
                        "Rp. ".number_format($ppnMasukan, 0, ',', '.')."\n\n".
                        "Terima kasih 🙏🙏🙏\n";

            $group = GroupWa::where('untuk', 'kas-besar')->first()->nama_group;

            $this->sendWa($group, $pesan);

            $result = [
                'status' => 'success',
                'message' => 'Data berhasil disimpan!'
            ];

        } catch (\Exception $e) {

            DB::rollBack();

            $result = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];

            return $result;
        }

        return $result;
    }

    public function checkoutPackagingTempo($data)
    {
        $kas = new KasBesar();

        $belanja = $this->where('user_id', auth()->user()->id)->where('jenis', 3)->where('tempo', 1)->get();
        $ppn = Pajak::where('untuk', 'ppn')->first()->persen;

        if($data['ppn'] == 1)
        {
            $data['ppn'] = ($ppn/100) * ($belanja->sum('total') + $belanja->sum('add_fee'));
        }

        $data['dp'] = str_replace('.', '', $data['dp']);

        if($data['dp_ppn'] == 1)
        {
            $data['dp_ppn'] = ($ppn/100) * $data['dp'];
            $data['sisa_ppn'] = $data['ppn'] - $data['dp_ppn'];
        } else {
            $data['dp_ppn'] = 0;
            $data['sisa_ppn'] = $data['ppn'];
        }

        $data['tempo'] = 1;

        if ($kas->saldoTerakhir() < ($data['dp'] + $data['dp_ppn'])) {
            return [
                'status' => 'error',
                'message' => 'Saldo kas besar tidak mencukupi. Saldo saat ini : '.number_format($kas->saldoTerakhir(), 0, ',', '.')
            ];
        }

        $data['jatuh_tempo'] = Carbon::createFromFormat('d-m-Y', $data['jatuh_tempo'])->format('Y-m-d');
        $data['diskon'] = str_replace('.', '', $data['diskon']);

        $data['total'] = $belanja->sum('total') + $belanja->sum('add_fee') + $data['ppn'] - $data['diskon'];

        $data['sisa'] = $data['total'] - $data['dp'] - $data['dp_ppn'];

        $pesan = '';

        try {

            DB::beginTransaction();

            $jenis = 3;

            $store_inv = $this->invoice_checkout_tempo($data, $jenis);

            if ($data['dp'] > 0) {

                $store = $this->kas_checkout_tempo($data, $store_inv->id);

                $dbInvoice = new InvoiceBelanja();
                $ppnMasukan = $dbInvoice->sumNilaiPpn();

                $pesan = "🔴🔴🔴🔴🔴🔴🔴🔴🔴\n".
                            "*FORM BELI PACKAGING*\n".
                            "🔴🔴🔴🔴🔴🔴🔴🔴🔴\n\n".
                            "Uraian :  *".$store->uraian."*\n\n".
                            "Nilai    :  *Rp. ".number_format($store->nominal, 0, ',', '.')."*\n\n".
                            "Ditransfer ke rek:\n\n".
                            "Bank      : ".$store->bank."\n".
                            "Nama    : ".$store->nama_rek."\n".
                            "No. Rek : ".$store->no_rek."\n\n".
                            "==========================\n".
                            "Sisa Saldo Kas Besar : \n".
                            "Rp. ".number_format($store->saldo, 0, ',', '.')."\n\n".
                            "Total Modal Investor : \n".
                            "Rp. ".number_format($store->modal_investor_terakhir, 0, ',', '.')."\n\n".
                            "Total PPn Masukan : \n".
                            "Rp. ".number_format($ppnMasukan, 0, ',', '.')."\n\n".
                            "Terima kasih 🙏🙏🙏\n";

                $group = GroupWa::where('untuk', 'kas-besar')->first()->nama_group;

                $this->sendWa($group, $pesan);

            }

            $this->update_packaging(1);

            $this->where('user_id', auth()->user()->id)->where('jenis', 3)->where('tempo', 1)->delete();

            DB::commit();

            $result = [
                'status' => 'success',
                'message' => 'Data berhasil disimpan!'
            ];

        } catch (\Exception $e) {

            DB::rollBack();

            $result = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];

            return $result;
        }

        return $result;
    }

}
