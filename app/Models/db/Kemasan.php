<?php

namespace App\Models\db;

use App\Models\Produksi\RencanaProduksi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kemasan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $appends = ['nf_harga'];

    public function kategori()
    {
        return $this->belongsTo(KemasanKategori::class, 'kemasan_kategori_id');
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }

    public function packaging()
    {
        return $this->belongsTo(Packaging::class);
    }

    public function rencana_produksi()
    {
        return $this->hasMany(RencanaProduksi::class);
    }

    public function getNfHargaAttribute()
    {
        return number_format($this->harga, 0, ',', '.');
    }
}
