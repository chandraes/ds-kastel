<?php

namespace App\Models\db;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriProduct extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
