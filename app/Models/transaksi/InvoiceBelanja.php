<?php

namespace App\Models\transaksi;

use App\Models\db\RekapBahanBaku;
use App\Models\GroupWa;
use App\Models\KasBesar;
use App\Models\PesanWa;
use App\Models\Rekening;
use App\Services\StarSender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InvoiceBelanja extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $appends = ['tanggal', 'nf_diskon', 'nf_ppn', 'nf_total', 'id_jatuh_tempo'];

    public function getTanggalAttribute()
    {
        return date('d-m-Y', strtotime($this->created_at));
    }

    public function getNfDiskonAttribute()
    {
        return number_format($this->diskon, 0, ',', '.');
    }

    public function getNfPpnAttribute()
    {
        return number_format($this->ppn, 0, ',', '.');
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
}
