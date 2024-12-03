<?php

namespace App\Models\transaksi;

use App\Models\db\BahanBaku;
use App\Models\db\Kemasan;
use App\Models\db\Packaging;
use App\Models\db\RekapBahanBaku;
use App\Models\db\Supplier;
use App\Models\GroupWa;
use App\Models\KasBesar;
use App\Models\Pajak\PpnMasukan;
use App\Models\Pajak\RekapPpn;
use App\Models\PesanWa;
use App\Models\Rekening;
use App\Services\StarSender;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InvoiceBelanja extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $appends = ['tanggal', 'nf_diskon', 'nf_ppn', 'nf_total', 'id_jatuh_tempo', 'kode', 'nf_dp', 'nf_sisa', 'dpp', 'nf_dp_ppn', 'nf_sisa_ppn',
                         'total_dp', 'nf_total_dp', 'total_dp', 'nf_add_fee'];


    public function dataTahun()
    {
        return $this->selectRaw('YEAR(created_at) as tahun')->groupBy('tahun')->get();
    }

    public function invoiceByMonth($month, $year, $filter = null)
    {
        return $this->with(['supplier'])->whereMonth('created_at', $month)
                    ->whereYear('created_at', $year)
                    ->where('tempo', 0)->get();
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function generateKode()
    {
        $kode = $this->max('nomor_bb') + 1;

        return $kode;
    }

    public function getKodeAttribute()
    {
        return 'BB'.sprintf('%02d', $this->nomor_bb);
    }

    public function getDppAttribute()
    {
        $dpp = $this->total + $this->diskon - $this->ppn;

        return number_format($dpp, 0, ',', '.');
    }

    public function getNfDpPpnAttribute()
    {
        return number_format($this->dp_ppn, 0, ',', '.');
    }

    public function getNfAddFeeAttribute()
    {
        return number_format($this->add_fee, 0, ',', '.');
    }

    public function getNfSisaPpnAttribute()
    {
        return number_format($this->sisa_ppn, 0, ',', '.');
    }

    public function getTanggalAttribute()
    {
        return date('d-m-Y', strtotime($this->created_at));
    }

    public function getNfDiskonAttribute()
    {
        return number_format($this->diskon, 0, ',', '.');
    }

    public function getNfDpAttribute()
    {
        return number_format($this->dp, 0, ',', '.');
    }

    public function getNfSisaAttribute()
    {
        return number_format($this->sisa, 0, ',', '.');
    }

    public function getNfPpnAttribute()
    {
        return number_format($this->ppn, 0, ',', '.');
    }

    public function getTotalDpAttribute()
    {
        return $this->dp + $this->dp_ppn;
    }

    public function getNfTotalDpAttribute()
    {
        return number_format($this->total_dp, 0, ',', '.');
    }

    public function getNfTotalAttribute()
    {
        return number_format($this->total, 0, ',', '.');
    }

    public function getIdJatuhTempoAttribute()
    {
        return date('d-m-Y', strtotime($this->jatuh_tempo));
    }

    public function detail()
    {
        return $this->hasMany(InvoiceBelanjaDetail::class, 'invoice_belanja_id');
    }

    public function rekap()
    {
        return $this->hasManyThrough(RekapBahanBaku::class, InvoiceBelanjaDetail::class, 'invoice_belanja_id', 'id', 'id', 'rekap_bahan_baku_id');
    }

    public function claim_ppn($invoice)
    {
        $data = InvoiceBelanja::find($invoice->id);

        DB::beginTransaction();

        try {

            $store = $this->store_kas($invoice->id);

            $data->ppn_masukan = 1;
            $data->save();

            DB::commit();

        } catch (\Throwable $th) {
            DB::rollBack();
            return [
                'status' => 'error',
                'message' => $th->getMessage()
            ];
        }

        $pesan =    "🔵🔵🔵🔵🔵🔵🔵🔵🔵\n".
                    "*Klaim PPn Masukan*\n".
                    "🔵🔵🔵🔵🔵🔵🔵🔵🔵\n\n".
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
                    "Terima kasih 🙏🙏🙏\n";

        $group = GroupWa::where('untuk', 'kas-besar')->first()->nama_group;

        $this->sendWa($group, $pesan);

        return [
            'status' => 'success',
            'message' => 'Berhasil claim PPN Masukan'
        ];
    }

    public function store_kas($id)
    {
        $data = $this->find($id);

        $db = new KasBesar();
        $rekening = Rekening::where('untuk', 'kas-besar')->first();

        $store = $db->create([
            'uraian' => 'Klaim PPn ' . $data['uraian'],
            'jenis' => 1,
            'nominal' => $data['ppn'],
            'saldo' => $data['ppn']+$db->saldoTerakhir(),
            'no_rek' => $rekening->no_rek,
            'nama_rek' => $rekening->nama_rek,
            'bank' => $rekening->bank,
            'modal_investor_terakhir' => $db->modalInvestorTerakhir()
        ]);

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
    }

    public function bayar_hutang($data)
    {
        $db = new KasBesar();

        $invoice = $this->find($data['id']);

        if ($db->saldoTerakhir() < $invoice->sisa) {
            return [
                'status' => 'error',
                'message' => 'Saldo kas besar tidak mencukupi'
            ];
        }

        DB::beginTransaction();

        try {

            $store = $db->create([
                'uraian' => 'Pelunasan '.$invoice->uraian,
                'jenis' => 0,
                'nominal' => $invoice->sisa,
                'saldo' => $db->saldoTerakhir() - $invoice->sisa,
                'no_rek' => $invoice->no_rek,
                'nama_rek' => $invoice->nama_rek,
                'bank' => $invoice->bank,
                'modal_investor_terakhir' => $db->modalInvestorTerakhir(),
                'invoice_belanja_id' => $invoice->id
            ]);

            $invoice->sisa = 0;
            $invoice->tempo = 0;
            $invoice->save();

            DB::commit();

            $ppnMasukanDb = new PpnMasukan();
            $dbRekapPpn = new RekapPpn();
            $saldoTerakhirPpn = $dbRekapPpn->saldoTerakhir();
            $ppnMasukan = $ppnMasukanDb->totalPpnMasukan() + $saldoTerakhirPpn;

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
                'message' => 'Berhasil membayar hutang'
            ];

        } catch (\Throwable $th) {
            DB::rollBack();
            return [
                'status' => 'error',
                'message' => $th->getMessage()
            ];
        }

        return $result;
    }

    public function ppn_masukan()
    {
        $data = $this->with('supplier')->select('id', 'nomor_bb', 'supplier_id', 'dp_ppn as nilai_ppn', 'created_at as tgl')
                 ->where('dp_ppn', '>', 0)
                 ->where('void', 0)
                 ->union($this->select('id', 'nomor_bb', 'supplier_id', 'sisa_ppn as nilai_ppn', 'updated_at as tgl')
                         ->where('sisa_ppn', '>', 0)
                         ->where('tempo', 0)
                         ->where('void', 0))
                 ->union($this->select('id', 'nomor_bb', 'supplier_id', 'ppn as nilai_ppn', 'created_at as tgl')
                         ->where('dp_ppn', 0)
                         ->where('ppn', '>', 0)
                         ->where('tempo', 0)
                         ->where('void', 0))
                 ->get();

        return $data;
    }

    public function sumNilaiPpn()
    {
       // Directly sum the relevant columns based on conditions, including where void is 0
        $sum = $this->newQuery()
            ->where('void', 0) // Add this line to include the condition
            ->selectRaw('SUM(CASE
                                WHEN dp_ppn > 0 THEN dp_ppn
                                WHEN sisa_ppn > 0 AND tempo = 0 THEN sisa_ppn
                                WHEN dp_ppn = 0 AND ppn > 0 AND tempo = 0 THEN ppn
                                ELSE 0
                            END) as total_nilai_ppn')
            ->value('total_nilai_ppn');

        return $sum;
    }

    // public function getFormattedTglAttribute()
    // {
    //     // Check if 'tgl' attribute is set
    //     if ($this->attributes['tgl']) {
    //         // Parse the 'tgl' attribute as a Carbon instance and format it
    //         return Carbon::parse($this->attributes['tgl'])->format('d-m-Y');
    //     }

    //     return null;
    // }

    public function void_belanja($id)
    {
        // TODO:
        // 1. Kembalikan DP + DP PPN
        // 2. kurangi stock baik itu kemasan, packaging, maupun bahan baku berdasarkan jenis dan id

        $invoice = $this->with('detail.rekap')->where('id',$id)->first();

        try {
            DB::beginTransaction();

            foreach ($invoice->detail as $d) {

                $rekap_id = $d->rekap_bahan_baku_id;
                // dd($rekap_id);
                $this->update_stok($rekap_id);

            }

            $rekening = Rekening::where('untuk', 'kas-besar')->first();

            $kas = new KasBesar();

            $nominal = $invoice->dp + $invoice->dp_ppn;

            $store = $kas->create([
                'uraian' => "Pembatalan ".$invoice->uraian,
                'jenis' => 1,
                'nomor_bb' => $invoice->nomor_bb,
                'nominal' => $nominal,
                'saldo' => $kas->saldoTerakhir() + $nominal,
                'no_rek' => $rekening->no_rek,
                'nama_rek' => $rekening->nama_rek,
                'bank' => $rekening->bank,
                'modal_investor_terakhir' => $kas->modalInvestorTerakhir(),
                'invoice_belanja_id' => $invoice->id
            ]);

            $invoice->update(['void' => 1]);

            PpnMasukan::where('invoice_belanja_id', $invoice->id)->delete();

            DB::commit();

            $ppnMasukan = $this->sumNilaiPpn();

            $pesan = "🔵🔵🔵🔵🔵🔵🔵🔵🔵\n".
                        "*VOID BELI BAHAN BAKU*\n".
                        "🔵🔵🔵🔵🔵🔵🔵🔵🔵\n\n".
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

            $kas->sendWa($group, $pesan);

            return [
                'status' => 'success',
                'message' => 'Pembelian berhasil dibatalkan.',
            ];

        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();

            return [
                'status' => 'error',
                'message' => 'Gagal Membatalkan Pesanan. '.$th->getMessage(),
            ];
        }
    }

    private function update_stok($rekap_id)
    {
        $rekap = RekapBahanBaku::find($rekap_id);

        if($rekap->bahan_baku_id)
        {
            $bahan = BahanBaku::find($rekap->bahan_baku_id);
            $bahan->decrement('stock', $rekap->jumlah);
        } elseif($rekap->kemasan_id) {
            $bahan = Kemasan::find($rekap->kemasan_id);
            $bahan->decrement('stok', $rekap->jumlah);
        } elseif($rekap->packaging_id) {
            Packaging::find($rekap->packaging_id)->decrement('stok', $rekap->jumlah);
        }

        RekapBahanBaku::create([
            'bahan_baku_id' => $rekap->bahan_baku_id ?? null,
            'kemasan_id' => $rekap->kemasan_id ?? null,
            'packaging_id' => $rekap->packaging_id ?? null,
            'jumlah' => $rekap->jumlah,
            'nama' => $rekap->nama,
            'satuan_id' => $rekap->satuan_id,
            'harga' => $rekap->harga,
            'add_fee' => $rekap->add_fee,
            'uraian' => 'Pembatalan '.$rekap->uraian,
            'jenis' => 0,
        ]);
        // $rekap->delete();

        return true;
    }
}
