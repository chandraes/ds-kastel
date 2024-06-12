<?php

namespace App\Models\Produksi;

use App\Models\transaksi\InvoiceJual;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductJadiRekap extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $appends = ['tanggal'];

    public function getTanggalAttribute()
    {
        return date('d-m-Y', strtotime($this->created_at));
    }

    public function productJadi()
    {
        return $this->belongsTo(ProductJadi::class, 'product_jadi_id');
    }

    public function rencanaProduksi()
    {
        return $this->belongsTo(RencanaProduksi::class, 'rencana_produksi_id');
    }

    public function invoiceJual()
    {
        return $this->belongsTo(InvoiceJual::class, 'invoice_jual_id');
    }

    public function sisaTerakhir($productJadiId)
    {
        return $this->where('product_jadi_id', $productJadiId)->orderBy('id', 'desc')->first()->sisa_kemasan ?? 0;
    }
}
