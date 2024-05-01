<?php

namespace App\Models\db;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['modal'];

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
        // sum jumlah * harga + add_fee
        return $this->rekap->sum(function ($item) {
            return $item->jumlah * $item->harga + $item->add_fee;
        });

    }

    public function getNfModalAttribute()
    {
        return number_format($this->modal, 0, ',', '.');
    }


}
