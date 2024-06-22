<?php

namespace App\Models\db;

use App\Models\transaksi\InventarisInvoice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InventarisRekap extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $appends = ['nf_harga_satuan', 'nf_jumlah', 'total', 'nf_total', 'tanggal'];

    public function invoice()
    {
        return $this->hasOne(InventarisInvoice::class, 'inventaris_id');
    }

    public function jenis()
    {
        return $this->belongsTo(InventarisJenis::class, 'inventaris_jenis_id');
    }

    public function getNfHargaSatuanAttribute()
    {
        return number_format($this->harga_satuan, 0, ',', '.');
    }

    public function getNfJumlahAttribute()
    {
        return number_format($this->jumlah, 0, ',', '.');
    }

    public function getNfTotalAttribute()
    {
        return number_format($this->total, 0, ',', '.');
    }

    public function getTanggalAttribute()
    {
        return $this->created_at->format('d-m-Y');
    }

}
