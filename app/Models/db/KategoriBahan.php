<?php

namespace App\Models\db;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriBahan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function bahanBaku()
    {
        return $this->hasMany(BahanBaku::class);
    }


}
