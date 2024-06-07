<?php

namespace App\Models;

use App\Models\db\Konsumen;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasKonsumen extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function konsumen()
    {
        return $this->belongsTo(Konsumen::class, 'konsumen_id');
    }

    public function sisaTerakhir($konsumen_id)
    {
        return $this->where('konsumen_id', $konsumen_id)->orderBy('id', 'desc')->first()->sisa ?? 0;
    }
}
