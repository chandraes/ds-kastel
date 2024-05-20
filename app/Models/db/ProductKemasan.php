<?php

namespace App\Models\db;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductKemasan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $appends = ['nf_harga'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function kemasan()
    {
        return $this->belongsTo(Kemasan::class);
    }

    public function getNfHargaAttribute()
    {
        return number_format($this->harga, 0, ',', '.');
    }
}
