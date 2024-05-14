<?php

namespace App\Models\db;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function kategori()
    {
        return $this->belongsTo(KategoriProduct::class, 'kategori_product_id');
    }

    public function komposisi()
    {
        return $this->hasMany(ProductKomposisi::class, 'product_id');
    }
}
