<?php

namespace App\Models\db;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['modal', 'nf_modal'];

    public function komposisi()
    {
        return $this->hasMany(ProductKomposisi::class, 'bahan_baku_id');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriBahan::class, 'kategori_bahan_id');
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }

    public function rekap()
    {
        return $this->hasMany(RekapBahanBaku::class, 'bahan_baku_id');
    }

    public function getModalAttribute()
    {
        // max harga + latest add_fee
        return $this->rekap->max('harga') + $this->rekap->max('add_fee');

    }

    public function getNfModalAttribute()
    {
        return number_format($this->modal, 0, ',', '.');
    }


}
