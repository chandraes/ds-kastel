<?php

namespace App\Models\transaksi;

use App\Models\db\RekapBahanBaku;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceBelanja extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $appends = ['tanggal', 'nf_diskon', 'nf_ppn', 'nf_total', 'id_jatuh_tempo'];

    public function getTanggalAttribute()
    {
        return date('d-m-Y', strtotime($this->created_at));
    }

    public function getNfDiskonAttribute()
    {
        return number_format($this->diskon, 0, ',', '.');
    }

    public function getNfPpnAttribute()
    {
        return number_format($this->ppn, 0, ',', '.');
    }

    public function getNfTotalAttribute()
    {
        return number_format($this->total, 0, ',', '.');
    }

    public function getIdJatuhTempoAttribute()
    {
        return date('d-m-Y', strtotime($this->jatuh_tempo));
    }

    public function detail()
    {
        return $this->hasMany(InvoiceBelanjaDetail::class, 'invoice_belanja_id');
    }

    public function rekap()
    {
        return $this->hasManyThrough(RekapBahanBaku::class, InvoiceBelanjaDetail::class, 'invoice_belanja_id', 'id', 'id', 'rekap_bahan_baku_id');
    }
}
