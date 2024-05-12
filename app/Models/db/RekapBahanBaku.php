<?php

namespace App\Models\db;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapBahanBaku extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $appends = ['nf_harga', 'nf_add_fee', 'nf_jumlah', 'total', 'nf_total'];

    public function getTotalAttribute()
    {
        return $this->harga * $this->jumlah + $this->add_fee;
    }

    public function getNfTotalAttribute()
    {
        return number_format($this->total, 0, ',', '.');
    }

    public function getNfHargaAttribute()
    {
        return number_format($this->harga, 0, ',', '.');
    }

    public function getNfAddFeeAttribute()
    {
        return number_format($this->add_fee, 0, ',', '.');
    }

    public function getNfJumlahAttribute()
    {
        return number_format($this->jumlah, 0, ',', '.');
    }

    public function bahan_baku()
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_baku_id');
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }

}
