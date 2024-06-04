<?php

namespace App\Models\Produksi;

use App\Models\db\BahanBaku;
use App\Models\db\Kemasan;
use App\Models\db\Packaging;
use App\Models\db\Product;
use App\Models\db\ProductKomposisi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RencanaProduksi extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $appends = ['id_tanggal_produksi', 'id_tanggal_expired'];

    public function produksi_detail()
    {
        return $this->hasMany(ProduksiDetail::class, 'rencana_produksi_id', 'id');
    }

    public function packaging()
    {
        return $this->belongsTo(Packaging::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function kemasan()
    {
        return $this->belongsTo(Kemasan::class);
    }

    public function getIdTanggalProduksiAttribute()
    {
        return date('d-m-Y', strtotime($this->tanggal_produksi));
    }

    public function getIdTanggalExpiredAttribute()
    {
        return date('d-m-Y', strtotime($this->tanggal_expired));
    }

    public function generateNomorProduksi($product_id)
    {
        return $this->where('product_id', $product_id)->max('nomor_produksi') + 1;
    }

    public function storeProduksi($data)
    {
        $komposisi = ProductKomposisi::with(['product','bahan_baku', 'bahan_baku.kategori', 'bahan_baku.satuan'])
                                    ->where('product_id', $data['product_id'])->get();
        $product = Product::with('kategori')->where('id', $data['product_id'])->first();
        $kemasan = Kemasan::with(['packaging'])->find($data['kemasan_id']);
        $data['tanggal_produksi'] = now();
        $data['tanggal_expired'] = now()->addMonth($data['expired_dalam'] - 1);
        $data['nomor_produksi'] = $this->generateNomorProduksi($data['product_id']);
        $data['kode_produksi'] = $product->kategori->kode.'/'.$product->kode.'/'.str_pad($data['nomor_produksi'], 2, '0', STR_PAD_LEFT);

        unset($data['expired_dalam']);

        try {
            DB::beginTransaction();

            if($kemasan->packaging_id) {
                $packaging = floor($data['rencana_produksi']/$kemasan->packaging->konversi_kemasan);
                Packaging::find($kemasan->packaging_id)->decrement('stok', $packaging);
            } else {
                $packaging = 0;
            }

            $data['rencana_packaging'] = $packaging;
            $data['rencana_kemasan'] = $data['rencana_produksi'];

            $store = $this->create($data);

            foreach ($komposisi as $item) {

                $totalBahan = (($item->jumlah * $data['rencana_produksi'] / 100) * $kemasan->konversi_liter / $item->product->konversi_liter);

                $bahan = BahanBaku::find($item->bahan_baku_id)->decrement('stock', $totalBahan);

            }

            DB::commit();

            $result = [
                'status' => 'success',
                'message' => 'Data berhasil disimpan',
                'data' => $store
            ];

            return $result;

        } catch (\Exception $e) {
            DB::rollBack();
            $result = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
            return $result;



        }


    }

}
