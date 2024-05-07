<?php

namespace App\Models\transaksi;

use App\Models\db\BahanBaku;
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

class Keranjang extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $appends = ['nf_jumlah'];

    public function getNfJumlahAttribute()
    {
        return number_format($this->jumlah, 0, ',','.');
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

        $belanja = $this->where('user_id', auth()->user()->id)->get();

        if($data['ppn'] == 1)
        {
            $data['ppn'] = 0.11 * ($belanja->sum('total') + $belanja->sum('add_fee'));

        }
        $data['diskon'] = str_replace('.', '', $data['diskon']);

        $data['total'] = $belanja->sum('total') + $belanja->sum('add_fee') + $data['ppn'] - $data['diskon'];

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

            $store = $this->kas_checkout($data);

            $this->invoice_checkout($data);

            $this->update_bahan();

            $this->where('user_id', auth()->user()->id)->delete();

            DB::commit();

            $pesan = "🔴🔴🔴🔴🔴🔴🔴🔴🔴\n".
                        "*Form Bahan Baku*\n".
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

    private function kas_checkout($data)
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
        ];

        $store = $db->create($kas);

        return $store;

    }

    private function update_bahan()
    {
        $keranjang = $this->where('user_id', auth()->user()->id)->get();

        // Get all the bahan_baku_ids from the keranjang
        $bahan_baku_ids = $keranjang->pluck('bahan_baku_id')->toArray();

        // Get all the BahanBaku records at once
        $bahan_bakus = BahanBaku::whereIn('id', $bahan_baku_ids)->get()->keyBy('id');

        foreach ($keranjang as $item) {
            $bahan = $bahan_bakus[$item->bahan_baku_id];

            if($bahan->apa_konversi == 1 && $item->satuan_id != 1) {
                $bahan->stock += $item->jumlah * $bahan->konversi;
            } else {
                $bahan->stock += $item->jumlah;
            }

            $bahan->save();
        }

        return true;
    }

    private function invoice_checkout($data)
    {
        $db = new InvoiceBelanja();

        $data['ppn_masukan'] = $data['ppn'] == 0 ? 1 : 0;

        $invoice = [
            'uraian' => $data['uraian'],
            'ppn' => str_replace('.', '', $data['ppn']),
            'diskon' => str_replace('.', '', $data['diskon']),
            'total' => $data['total'],
            'nama_rek' => $data['nama_rek'],
            'no_rek' => $data['no_rek'],
            'bank' => $data['bank'],
            'ppn_masukan' => $data['ppn_masukan'],
        ];

        $store = $db->create($invoice);

        $keranjang = $this->where('user_id', auth()->user()->id)->get();

        foreach ($keranjang as $item) {

            $rekap = RekapBahanBaku::create([
                'bahan_baku_id' => $item->bahan_baku_id,
                'nama' => $item->bahan_baku->nama,
                'jenis' => 0, //Pembelian
                'jumlah' => $item->jumlah,
                'harga' => $item->harga,
                'satuan_id' => $item->satuan_id,
                'add_fee' => $item->add_fee,
            ]);

            $store->detail()->create([
                'invoice_belanja_id' => $store->id,
                'rekap_bahan_baku_id' => $rekap->id,
            ]);
        }

        return true;
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

}
