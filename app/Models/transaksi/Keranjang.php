<?php

namespace App\Models\transaksi;

use App\Models\db\BahanBaku;
use App\Models\db\Satuan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $appends = ['nf_jumlah'];

    public function getNfJumlahAttribute()
    {
        return number_format($this->jumlah, 0, ',','.');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bahan_baku()
    {
        return $this->belongsTo(BahanBaku::class);
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }

}
