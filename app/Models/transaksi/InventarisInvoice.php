<?php

namespace App\Models\transaksi;

use App\Models\db\InventarisRekap;
use App\Models\GroupWa;
use App\Models\KasBesar;
use App\Models\PesanWa;
use App\Services\StarSender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InventarisInvoice extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function inventaris()
    {
        return $this->belongsTo(InventarisRekap::class, 'inventaris_id');
    }

    public function beliInventaris($data)
    {
        try {
            DB::beginTransaction();

            $kas = new KasBesar();
            $saldo = $kas->saldoTerakhir();

            $total = ($data['jumlah'] * $data['harga_satuan']) + $data['ppn'];

            if($saldo < $total){
                return [
                    'status' => 'error',
                    'message' => 'Saldo kas besar tidak mencukupi! Sisa saldo: Rp. '.number_format($saldo, 0, ',', '.'),
                ];
            }

            $inventaris = InventarisRekap::create([
                'inventaris_jenis_id' => $data['inventaris_jenis_id'],
                'status' => $data['status'],
                'jenis' => $data['jenis'],
                'uraian' => $data['uraian'],
                'jumlah' => $data['jumlah'],
                'harga_satuan' => $data['harga_satuan'],
                'total' => $data['jumlah'] * $data['harga_satuan'],
            ]);

            if($data['pembayaran'] == 1){
                $invoice = $this->create([
                    'inventaris_id' => $inventaris->id,
                    'uraian' => $data['uraian'],
                    'pembayaran' => $data['pembayaran'],
                    'jumlah' => $data['jumlah'],
                    'harga_satuan' => $data['harga_satuan'],
                    'ppn' => $data['ppn'],
                    'total' => $total,
                    'nama_rek' => $data['nama_rek'],
                    'no_rek' => $data['no_rek'],
                    'bank' => $data['bank'],
                    'lunas' => 1,
                ]);
            }

            $store = $kas->create([
                'uraian' => $data['uraian'],
                'jenis' => 0,
                'nominal' => $total,
                'saldo' => $saldo - $total,
                'no_rek' => $data['no_rek'],
                'nama_rek' => $data['nama_rek'],
                'bank' => $data['bank'],
                'modal_investor_terakhir' => $kas->modalInvestorTerakhir(),
                'invoice_inventaris_id' => $invoice->id,
            ]);

            $pesan = "🔴🔴🔴🔴🔴🔴🔴🔴🔴\n".
                        "*FORM INVENTARIS*\n".
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

            DB::commit();

            $res = [
                'status' => 'success',
                'message' => 'Data berhasil disimpan',
            ];

            return $res;

        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();

            $res = [
                'status' => 'error',
                'message' => $th->getMessage(),
            ];

            return $res;
        }
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
