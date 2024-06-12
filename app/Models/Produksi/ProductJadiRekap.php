<?php

namespace App\Models\Produksi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductJadiRekap extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function productJadi()
    {
        return $this->belongsTo(ProductJadi::class, 'product_jadi_id');
    }

    public function sisaTerakhir($productJadiId)
    {
        return $this->where('product_jadi_id', $productJadiId)->orderBy('id', 'desc')->first()->sisa_kemasan ?? 0;
    }
}
