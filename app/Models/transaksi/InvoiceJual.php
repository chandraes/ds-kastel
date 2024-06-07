<?php

namespace App\Models\transaksi;

use App\Models\db\Konsumen;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class InvoiceJual extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $appends = ['full_invoice', 'bulan_angka', 'tanggal', 'tahun', 'jatuh_tempo', 'nf_total', 'nf_ppn', 'nf_grand_total', 'grand_total'];

    public function detail()
    {
        return $this->hasMany(InvoiceJualDetail::class, 'invoice_jual_id', 'id');
    }

    public function generateNoInvoice()
    {
        // check max no_invoice by year now
        $max = $this->whereYear('created_at', date('Y'))->max('no_invoice');
        $no_invoice = $max + 1;

        return $no_invoice;
    }

    public function dataTahun()
    {
        return $this->selectRaw('YEAR(created_at) as tahunArray')->groupBy('tahunArray')->get();
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

    public function getJatuhTempoAttribute()
    {
        // use carbon to add days from relation konsumen->tempo_hari
        return Carbon::create($this->created_at)->addDays($this->konsumen->tempo_hari)->format('d-m-Y');
    }

    public function getNfTotalAttribute()
    {
        return number_format($this->total, 0, ',', '.');
    }

    public function getNfPpnAttribute()
    {
        return number_format($this->ppn, 0, ',', '.');
    }

    public function getGrandTotalAttribute()
    {
        return $this->total+$this->ppn;
    }

    public function getNfGrandTotalAttribute()
    {
        return number_format($this->grand_total, 0, ',', '.');
    }

    public function rekapInvoice($month, $year)
    {
        return $this->with(['konsumen'])->where('lunas', 1)->whereMonth('created_at', $month)->whereYear('created_at', $year)->get();
    }

    public function rekapInvoiceByMonth($month, $year)
    {
        $data = $this->where('lunas', 1)->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        if (!$data) {
        $data = $this->where('lunas', 1)->where('created_at', '<', Carbon::create($year, $month, 1))
                ->orderBy('id', 'desc')
                ->first();
        }

        return $data;
    }
}
