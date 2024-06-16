<?php

namespace App\Models\db;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventarisJenis extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function kategori()
    {
        return $this->belongsTo(InventarisKategori::class, 'kategori_id');
    }

    public function rekap()
    {
        return $this->hasMany(InventarisRekap::class, 'inventaris_jenis_id');
    }
}
