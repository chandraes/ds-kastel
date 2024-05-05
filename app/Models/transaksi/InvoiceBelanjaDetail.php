<?php

namespace App\Models\transaksi;

use App\Models\db\RekapBahanBaku;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceBelanjaDetail extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function invoice()
    {
        return $this->belongsTo(InvoiceBelanja::class, 'invoice_belanja_id');
    }

    public function rekap()
    {
        return $this->belongsTo(RekapBahanBaku::class, 'rekap_bahan_baku_id');
    }
}
