<?php

namespace App\Models\db;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventarisKategori extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function jenis()
    {
        return $this->hasMany(InventarisJenis::class, 'kategori_id', 'id');
    }
}
