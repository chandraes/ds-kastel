<?php

namespace App\Models\transaksi;

use App\Models\db\Konsumen;
use App\Models\db\Pajak;
use App\Models\GroupWa;
use App\Models\KasBesar;
use App\Models\KasKonsumen;
use App\Models\PesanWa;
use App\Models\Produksi\ProductJadi;
use App\Models\Produksi\ProductJadiRekap;
use App\Models\Rekening;
use App\Models\User;
use App\Services\StarSender;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class KeranjangJual extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $appends = ['nf_harga', 'nf_total', 'total'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product_jadi()
    {
        return $this->belongsTo(ProductJadi::class);
    }

    public function getNfHargaAttribute()
    {
        return number_format($this->harga, 0, ',', '.');
    }

    public function getNfTotalAttribute()
    {
        return number_format($this->harga * $this->jumlah, 0, ',', '.');
    }

    public function getTotalAttribute()
    {
        return $this->harga * $this->jumlah;
    }

    public function checkout($req)
    {
        $getData = $this->where('user_id', auth()->id())->get();

        $pph = 0;

        $pphVal = Pajak::where('untuk', 'pph')->first()->persen / 100;
        $ppnVal = Pajak::where('untuk', 'ppn')->first()->persen / 100;

        if ($req['apa_pph'] == 1) {
            $pph = $getData->sum('total') * $pphVal;
        }

        unset($req['apa_pph']);

        $db = new InvoiceJual();

        $konsumen = Konsumen::find($req['konsumen_id']);

        $dbKonsumen = new KasKonsumen();

        $sisaTerakhir = $dbKonsumen->sisaTerakhir($konsumen->id);

        $gt = $getData->sum('total') + ($getData->sum('total')*$ppnVal) - $pph;

        $now = Carbon::now();

        $jatuhTempo = $now->addDays($konsumen->tempo_hari);

        $countInv = $db->where('konsumen_id', $konsumen->id)->where('lunas', 0)
                        // where $now is already passed the jatuh tempo
                        ->whereDate('created_at', '>', $now)
                        ->count();

        if ($sisaTerakhir + $gt > $konsumen->plafon || $countInv != 0) {
            return [
                'status' => 'error',
                'message' => 'Plafon tidak mencukupi atau masih ada invoice yang belum lunas',
            ];
        }

        $data['no_invoice'] = $db->generateNoInvoice();
        $data['invoice'] = $db->generateInvoice($data['no_invoice']);
        $data['konsumen_id'] = $req['konsumen_id'];
        $data['total'] = $getData->sum('total');
        $data['ppn'] = $data['total'] * $ppnVal;
        $data['pph'] = $pph;
        $data['lunas'] = $konsumen->pembayaran == 1 ? 1 : 0;

       try {

        DB::beginTransaction();

        $kasKonsumen = new KasKonsumen();
        $pjr = new ProductJadiRekap();

        $store = $db->create($data);

        foreach ($getData as $d) {
            $detail['invoice_jual_id'] = $store->id;
            $detail['product_jadi_id'] = $d->product_jadi_id;
            $detail['harga'] = $d->harga;
            $detail['jumlah'] = $d->jumlah;
            $detail['total'] = $detail['harga'] * $detail['jumlah'];

            $store->detail()->create($detail);

            ProductJadiRekap::create([
                'jenis' => 0,
                'product_jadi_id' => $d->product_jadi_id,
                'jumlah_kemasan' => $d->product_jadi->kemasan->packaging ? $d->product_jadi->kemasan->packaging->konversi_kemasan * $d->jumlah : $d->jumlah,
                'jumlah_packaging' => $d->jumlah,
                'invoice_jual_id' => $store->id,
                'sisa_kemasan' => $pjr->sisaTerakhir($d->product_jadi_id),
            ]);
        }

        $this->penguranganStock();

        if ($konsumen->pembayaran == 1) {

            $storeKasKonsumen = $kasKonsumen->create([
                'konsumen_id' => $konsumen->id,
                'invoice_jual_id' => $store->id,
                'uraian' => 'Pembelian ' . $store->invoice,
                'bayar' =>  $store->total + $store->ppn - $store->pph,
                'sisa' => $kasKonsumen->sisaTerakhir($konsumen->id),
            ]);

            $kas = new KasBesar();
            $rekening = Rekening::where('untuk', 'kas-besar')->first();

            $kb['uraian'] = 'Penjualan ' . $store->invoice;
            $kb['jenis'] = 1;
            $kb['nominal'] = $store->total + $store->ppn - $store->pph;
            $kb['saldo'] = $kas->saldoTerakhir() + $kb['nominal'];
            $kb['no_rek'] = $rekening->no_rek;
            $kb['invoice_jual_id'] = $store->id;
            $kb['nama_rek'] = $rekening->nama_rek;
            $kb['bank'] = $rekening->bank;
            $kb['modal_investor_terakhir'] = $kas->modalInvestorTerakhir();

            $storeKas = $kas->create($kb);

            $pesan =    "🔵🔵🔵🔵🔵🔵🔵🔵🔵\n".
                        "*FORM PENJUALAN*\n".
                        "🔵🔵🔵🔵🔵🔵🔵🔵🔵\n\n".
                        "Invoice : *".$store->invoice."*\n\n".
                        "Konsumen : *".$konsumen->nama."*\n".
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


            $this->sendWa($pesan);

        } else {
            $storeKasKonsumen = $kasKonsumen->create([
                'konsumen_id' => $konsumen->id,
                'invoice_jual_id' => $store->id,
                'uraian' => 'Hutang ' . $store->invoice,
                'hutang' =>  $store->total + $store->ppn - $store->pph,
                'sisa' => $kasKonsumen->sisaTerakhir($konsumen->id) + ($store->total + $store->ppn - $store->pph),
            ]);
        }

        $this->where('user_id', auth()->id())->delete();

        DB::commit();

        $result = [
            'status' => 'success',
            'message' => 'Berhasil checkout',
        ];

       } catch (\Throwable $th) {

            DB::rollBack();

            $result = [
                'status' => 'error',
                'message' => 'Gagal checkout. '.$th->getMessage(),
            ];

            return $result;
        }

        return $result;

    }

    public function penguranganStock()
    {
        $getData = $this->where('user_id', auth()->id())->get();

        foreach ($getData as $d) {
            $product = ProductJadi::find($d->product_jadi_id);

            $product->update([
                'stock_kemasan' => $product->kemasan->packaging ? $product->stock_kemasan - ($product->kemasan->packaging->konversi_kemasan * $d->jumlah) : $product->stock_kemasan - $d->jumlah,
                'stock_packaging' => $product->stock_packaging - $d->jumlah,
            ]);
        }
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


}
