<?php

namespace App\Models\db;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $appends = ['total_komposisi'];

    public function kategori()
    {
        return $this->belongsTo(KategoriProduct::class, 'kategori_product_id');
    }

    public function komposisi()
    {
        return $this->hasMany(ProductKomposisi::class, 'product_id');
    }

    public function getTotalKomposisiAttribute()
    {
        return $this->komposisi->sum('jumlah');
    }

    public function kemasan()
    {
        return $this->hasMany(Kemasan::class, 'product_id', 'id');
    }
}
