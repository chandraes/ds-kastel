<?php

namespace App\Models\transaksi;

use App\Models\db\Konsumen;
use App\Models\GroupWa;
use App\Models\KasBesar;
use App\Models\KasKonsumen;
use App\Models\PesanWa;
use App\Models\RekapGaji;
use App\Models\Rekening;
use App\Services\StarSender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InvoiceJual extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $appends = ['full_invoice', 'bulan_angka', 'tanggal', 'tahun', 'jatuh_tempo', 'nf_total', 'nf_ppn', 'nf_grand_total', 'grand_total', 'nf_pph'];

    public function detail()
    {
        return $this->hasMany(InvoiceJualDetail::class, 'invoice_jual_id', 'id');
    }

    public function generateNoInvoice()
    {
        // check max no_invoice by year now
        $max = $this->whereYear('created_at', date('Y'))->max('no_invoice');
        $no_invoice = $max + 1;

        return $no_invoice;
    }

    public function dataTahun()
    {
        return $this->selectRaw('YEAR(created_at) as tahunArray')->groupBy('tahunArray')->get();
    }

    public function generateInvoice($nomor)
    {
        return str_pad($nomor, 3, '0', STR_PAD_LEFT) . '/PT Kastel/' . date('m'). '/' . date('Y');
    }

    public function getFullInvoiceAttribute()
    {
        return str_pad($this->no_invoice, 3, '0', STR_PAD_LEFT) . '/PT Kaster/' . $this->bulan. '/' . $this->tahun;
    }

    public function konsumen()
    {
        return $this->belongsTo(Konsumen::class, 'konsumen_id');
    }

    public function getTahunAttribute()
    {
        return date('Y', strtotime($this->created_at));
    }

    public function getBulanAngkaAttribute()
    {
        return date('m', strtotime($this->created_at));
    }

    public function getTanggalAttribute()
    {
        return date('d-m-Y', strtotime($this->created_at));
    }

    public function getJatuhTempoAttribute()
    {
        // use carbon to add days from relation konsumen->tempo_hari
        return Carbon::create($this->created_at)->addDays($this->konsumen->tempo_hari)->format('d-m-Y');
    }

    public function getNfTotalAttribute()
    {
        return number_format($this->total, 0, ',', '.');
    }

    public function getNfPpnAttribute()
    {
        return number_format($this->ppn, 0, ',', '.');
    }

    public function getNfPphAttribute()
    {
        return number_format($this->pph, 2, ',', '.');
    }

    public function getGrandTotalAttribute()
    {
        return $this->total+$this->ppn-$this->pph;
    }

    public function getNfGrandTotalAttribute()
    {
        return number_format($this->grand_total, 0, ',', '.');
    }

    public function rekapInvoice($month, $year)
    {
        return $this->with(['konsumen'])->where('lunas', 1)->whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
    }

    public function rekapInvoiceByMonth($month, $year)
    {
        $data = $this->where('lunas', 1)->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        if (!$data) {
        $data = $this->where('lunas', 1)->where('created_at', '<', Carbon::create($year, $month, 1))
                ->orderBy('id', 'desc')
                ->first();
        }

        return $data;
    }

    public function pelunasan($id)
    {
        $data = $this->find($id);

        try {
            DB::beginTransaction();

            $kas = new KasBesar();
            $rekening = Rekening::where('untuk', 'kas-besar')->first();

            $kb['uraian'] = 'Pelunasan ' . $data->invoice;
            $kb['jenis'] = 1;
            $kb['nominal'] = $data->total + $data->ppn;
            $kb['saldo'] = $kas->saldoTerakhir() + $kb['nominal'];
            $kb['no_rek'] = $rekening->no_rek;
            $kb['invoice_jual_id'] = $data->id;
            $kb['nama_rek'] = $rekening->nama_rek;
            $kb['bank'] = $rekening->bank;
            $kb['modal_investor_terakhir'] = $kas->modalInvestorTerakhir();

            $storeKas = $kas->create($kb);

            $pesan =    "🔵🔵🔵🔵🔵🔵🔵🔵🔵\n".
                        "*FORM PELUNASAN TAGIHAN*\n".
                        "🔵🔵🔵🔵🔵🔵🔵🔵🔵\n\n".
                        "Invoice : *".$data->invoice."*\n\n".
                        "Konsumen : *".$data->konsumen->nama."*\n".
                        "Nilai :  *Rp. ".number_format($storeKas->nominal, 0, ',', '.')."*\n\n".
                        "Ditransfer ke rek:\n\n".
                        "Bank      : ".$storeKas->bank."\n".
                        "Nama    : ".$storeKas->nama_rek."\n".
                        "No. Rek : ".$storeKas->no_rek."\n\n".
                        "==========================\n".
                        "Sisa Saldo Kas Besar : \n".
                        "Rp. ".number_format($storeKas->saldo, 0, ',', '.')."\n\n".
                        "Total Modal Investor : \n".
                        "Rp. ".number_format($storeKas->modal_investor_terakhir, 0, ',', '.')."\n\n".
                        "Terima kasih 🙏🙏🙏\n";




            $data->update([
                'lunas' => 1
            ]);


            $kasKonsumen = new KasKonsumen();

            $sisa = $kasKonsumen->sisaTerakhir($data->konsumen->id) - ($data->total + $data->ppn);

            $storeKasKonsumen = $kasKonsumen->create([
                'konsumen_id' => $data->konsumen->id,
                'invoice_jual_id' => $data->id,
                'uraian' => 'Pelunasan ' . $data->invoice,
                'bayar' =>  $data->total + $data->ppn,
                'sisa' => $sisa > 0 ? $sisa : 0,
            ]);

            $this->sendWa($pesan);

            DB::commit();

            $res = [
                'status' => 'success',
                'message' => 'Invoice berhasil dilunasi'
            ];

        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();

            $res = [
                'status' => 'error',
                'message' => 'Gagal melakukan transaksi.'.$th->getMessage()
            ];

            return $res;
        }

        return $res;
    }

    private function sendWa($pesan)
    {
        $tujuan = GroupWa::where('untuk', 'kas-besar')->first()->nama_group;
        $send = new StarSender($tujuan, $pesan);
        $res = $send->sendGroup();

        $status = ($res == 'true') ? 1 : 0;

        PesanWa::create([
            'pesan' => $pesan,
            'tujuan' => $tujuan,
            'status' => $status,
        ]);
    }

    public function pphBadan($tahun, $kelebihan = 0)
    {
        $kasBesar = new KasBesar();
        $belanja = new InvoiceBelanja();

        $data = [];

        $data['omset'] = $this->whereYear('updated_at', $tahun)->where('lunas', 1)->sum('total');

        $data['permintaan_kas_kecil'] = $kasBesar->whereYear('created_at', $tahun)->whereNotNull('nomor_kode_kas_kecil')->sum('nominal');
        $data['lain_lain'] = $kasBesar->whereYear('created_at', $tahun)->where('lain_lain', 1)->where('jenis', 0)->sum('nominal');
        $data['belanja'] = $belanja->whereYear('created_at', $tahun)->where('tempo', 0)->sum('total');
        $data['modal'] = $data['permintaan_kas_kecil'] + $data['lain_lain'] + $data['belanja'];

        $data['gaji'] = RekapGaji::where('tahun', $tahun)->sum('total');
        $data['co'] = $kasBesar->whereYear('created_at', $tahun)->where('cost_operational', 1)->sum('nominal');
        $data['cost_operational'] = $data['gaji'] + $data['co'];

        $data['laba_bersih'] = $data['omset'] - $data['modal'] - $data['cost_operational'];
        $data['pokok_pkp'] = $data['laba_bersih'] - $kelebihan;

        $data['pph_terhutang_2'] = (0.5 * 0.22) * $data['laba_bersih'];
        $nominal = 4800000000;
        $data['pkp_fasilitas'] = ($nominal/$data['omset']) * $data['pokok_pkp'];

        $data['pph_terhutang_fasilitas'] = (0.5 * 0.22) * $data['pkp_fasilitas'];

        $data['pkp_non_fasilitas'] = $data['pokok_pkp'] - $data['pkp_fasilitas'];
        $data['pph_terhutang_non_fasilitas'] = 0.22 * $data['pkp_non_fasilitas'];

        $data['kredit_pph'] = InvoiceJual::where('lunas', 1)->whereYear('updated_at', $tahun)->sum('pph');

        $data['gt_pph_terhutang'] = $data['pph_terhutang_fasilitas'] + $data['pph_terhutang_non_fasilitas'];

        $data['gt'] = $data['gt_pph_terhutang'] - $data['kredit_pph'];

        return $data;

    }
}
