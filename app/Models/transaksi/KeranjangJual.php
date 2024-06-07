<?php

namespace App\Models\transaksi;

use App\Models\Produksi\ProductJadi;
use App\Models\User;
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

        $db = new InvoiceJual();

        $data['no_invoice'] = $db->generateNoInvoice();
        $data['invoice'] = $db->generateInvoice($data['no_invoice']);
        $data['konsumen_id'] = $req['konsumen_id'];
        $data['total'] = $getData->sum('total');
        $data['ppn'] = $data['total'] * 0.11;

       try {
        DB::beginTransaction();

        $db->create($data);

        DB::commit();

        $result = [
            'status' => 'success',
            'message' => 'Berhasil checkout',
        ];

       } catch (\Throwable $th) {

            DB::rollBack();

            $result = [
                'status' => 'error',
                'message' => 'Gagal checkout',
            ];

            return $result;
        }

        return $result;

    }


}
