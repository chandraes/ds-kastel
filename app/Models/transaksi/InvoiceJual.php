<?php

namespace App\Models\transaksi;

use App\Models\db\Konsumen;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceJual extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $appends = ['full_invoice', 'bulan_angka', 'tanggal', 'tahun'];

    public function generateNoInvoice()
    {
        // check max no_invoice by year now
        $max = $this->whereYear('created_at', date('Y'))->max('no_invoice');
        $no_invoice = $max + 1;

        return $no_invoice;

    }

    public function generateInvoice($nomor)
    {
        return str_pad($nomor, 3, '0', STR_PAD_LEFT) . '/PT Kastel/' . date('m'). '/' . date('Y');
    }

    public function getFullInvoiceAttribute()
    {
        return str_pad($this->no_invoice, 3, '0', STR_PAD_LEFT) . '/PT Kaster/' . $this->bulan. '/' . $this->tahun;
    }

    public function konsumen()
    {
        return $this->belongsTo(Konsumen::class, 'konsumen_id');
    }

    public function getTahunAttribute()
    {
        return date('Y', strtotime($this->created_at));
    }

    public function getBulanAngkaAttribute()
    {
        return date('m', strtotime($this->created_at));
    }

    public function getTanggalAttribute()
    {
        return date('d-m-Y', strtotime($this->created_at));
    }
}
