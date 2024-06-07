<?php

namespace App\Models\transaksi;

use App\Models\Produksi\ProductJadi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceJualDetail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function invoice_jual()
    {
        return $this->belongsTo(InvoiceJual::class);
    }

    public function product_jadi()
    {
        return $this->belongsTo(ProductJadi::class);
    }

    public function getNfTotalAttribute()
    {
        return number_format($this->total, 0, ',', '.');
    }

    public function getNfHargaAttribute()
    {
        return number_format($this->harga, 0, ',', '.');
    }

    public function getNfJumlahAttribute()
    {
        return number_format($this->jumlah, 0, ',', '.');
    }
}
