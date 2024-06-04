<?php

namespace App\Models\Produksi;

use App\Models\db\Kemasan;
use App\Models\db\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductJadi extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function kemasan()
    {
        return $this->belongsTo(Kemasan::class);
    }
}
