<?php

namespace App\Models\transaksi;

use App\Models\Produksi\ProductJadi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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


}
