<?php

namespace App\Models\db;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventarisRekap extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $appends = ['nf_harga_satuan', 'nf_jumlah', 'total', 'nf_total', 'tanggal'];

    public function jenis()
    {
        return $this->belongsTo(InventarisJenis::class, 'inventaris_jenis_id');
    }

    public function getNfHargaSatuanAttribute()
    {
        return number_format($this->harga_satuan, 0, ',', '.');
    }

    public function getNfJumlahAttribute()
    {
        return number_format($this->jumlah, 0, ',', '.');
    }

    public function getTotalAttribute()
    {
        return $this->jumlah * $this->harga_satuan;
    }

    public function getNfTotalAttribute()
    {
        return number_format($this->total, 0, ',', '.');
    }

    public function getTanggalAttribute()
    {
        return $this->created_at->format('d-m-Y');
    }
}
