<?php

namespace App\Models\db;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Packaging extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }
}
