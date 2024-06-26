<?php

namespace App\Models\db;

use App\Models\Produksi\RencanaProduksi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kemasan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $appends = ['nf_harga', 'nf_harga_satuan', 'harga_setelah_ppn', 'nf_harga_setelah_ppn', 'nf_stok'];

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

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function rencana_produksi()
    {
        return $this->hasMany(RencanaProduksi::class);
    }

    public function getNfHargaAttribute()
    {
        return number_format($this->harga, 0, ',', '.');
    }

    public function getNfHargaSatuanAttribute()
    {
        return number_format($this->harga_satuan, 0, ',', '.');
    }

    public function getNfStokAttribute()
    {
        return number_format($this->stok, 0, ',', '.');
    }

    public function getHargaSetelahPpnAttribute()
    {
        $ppn = Pajak::where('untuk', 'ppn')->first();

        $harga = $this->harga + ($this->harga * ($ppn->persen / 100));

        return $harga;
    }

    public function getNfHargaSetelahPpnAttribute()
    {
        return number_format($this->harga_setelah_ppn, 0, ',', '.');
    }
}
