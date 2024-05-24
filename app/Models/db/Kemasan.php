<?php

namespace App\Models\db;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kemasan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $appends = ['nf_harga'];

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }

    public function packaging()
    {
        return $this->belongsTo(Packaging::class);
    }

    public function getNfHargaAttribute()
    {
        return number_format($this->harga, 0, ',', '.');
    }
}
