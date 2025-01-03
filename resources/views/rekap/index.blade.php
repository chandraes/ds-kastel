@extends('layouts.app')
@section('content')

<div class="container text-center">
    <h1><u>REKAP</u></h1>
</div>
@include('rekap.modal-konsumen')
<div class="container mt-5">
    <div class="row justify-content-left">
        <h2 class="mt-3">UMUM</h2>
        <div class="col-md-2 text-center mt-5">
            <a href="{{route('rekap.kas-besar')}}" class="text-decoration-none">
                <img src="{{asset('images/kas-besar.svg')}}" alt="" width="70">
                <h5 class="mt-3">KAS BESAR</h5>
            </a>
        </div>
        <div class="col-md-2 text-center mt-5">
            <a href="#" class="text-decoration-none">
                <img src="{{asset('images/kosong.svg')}}" alt="" width="70">
                <h5 class="mt-3">NOTA VOID TRANSAKSI</h5>
            </a>
        </div>
    </div>
    <div class="row justify-content-left">
        <div class="col-md-2 text-center mt-5">
            <a href="#" class="text-decoration-none">
                <img src="{{asset('images/kosong.svg')}}" alt="" width="70">
                <h5 class="mt-3">DEPOSIT</h5>
            </a>
        </div>
        <div class="col-md-2 text-center mt-5">
            <a href="#" class="text-decoration-none">
                <img src="{{asset('images/kosong.svg')}}" alt="" width="70">
                <h5 class="mt-3">DIVIDEN</h5>
            </a>
        </div>
        <div class="col-md-2 text-center mt-5">
            <a href="#" class="text-decoration-none">
                <img src="{{asset('images/kosong.svg')}}" alt="" width="70">
                <h5 class="mt-3">KASBON</h5>
            </a>
        </div>
        <div class="col-md-2 text-center mt-5">
            <a href="#" class="text-decoration-none">
                <img src="{{asset('images/kosong.svg')}}" alt="" width="70">
                <h5 class="mt-3">LAIN-LAIN</h5>
            </a>
        </div>
        <div class="col-md-2 text-center mt-5">
            <a href="#" class="text-decoration-none">
                <img src="{{asset('images/kosong.svg')}}" alt="" width="70">
                <h5 class="mt-3">GANTI RUGI</h5>
            </a>
        </div>
        <div class="col-md-2 text-center mt-5">
            <a href="#" class="text-decoration-none">
                <img src="{{asset('images/kosong.svg')}}" alt="" width="70">
                <h5 class="mt-3">CSR<br>(TIDAK TERTENTU)</h5>
            </a>
        </div>


    </div>
    <hr>
    <br>
    <div class="row justify-content-left">
        <h2 class="mt-3">COST OPERATIONAL</h2>
        <div class="col-md-2 text-center mt-5">
            <a href="#" class="text-decoration-none">
                <img src="{{asset('images/kosong.svg')}}" alt="" width="70">
                <h5 class="mt-3">OPERATIONAL</h5>
            </a>
        </div>
        <div class="col-md-2 text-center mt-5">
            <a href="{{route('rekap.kas-kecil')}}" class="text-decoration-none">
                <img src="{{asset('images/kas-kecil.svg')}}" alt="" width="70">
                <h5 class="mt-3">KAS KECIL</h5>
            </a>
        </div>
        <div class="col-md-2 text-center mt-5">
            <a href="{{route('rekap.gaji')}}" class="text-decoration-none">
                <img src="{{asset('images/gaji.svg')}}" alt="" width="70">
                <h5 class="mt-3">GAJI</h5>
            </a>
        </div>
        <div class="col-md-2 text-center mt-5">
            <a href="{{route('rekap.bunga-investor')}}" class="text-decoration-none">
                <img src="{{asset('images/bunga-kreditor.svg')}}" alt="" width="70">
                <h5 class="mt-3">BUNGA INVESTOR</h5>
            </a>
        </div>
    </div>
    <hr>
    <br>
    <div class="row justify-content-left">
        <h2 class="mt-3">TRANSAKSI</h2>
        <div class="col-md-2 text-center mt-5">
            <a href="#" class="text-decoration-none">
                <img src="{{asset('images/kosong.svg')}}" alt="" width="70">
                <h5 class="mt-3">BAHAN BAKU</h5>
            </a>
        </div>
        <div class="col-md-2 text-center mt-5">
            <a href="#" class="text-decoration-none">
                <img src="{{asset('images/kosong.svg')}}" alt="" width="70">
                <h5 class="mt-3">KEMASAN</h5>
            </a>
        </div>
        <div class="col-md-2 text-center mt-5">
            <a href="#" class="text-decoration-none">
                <img src="{{asset('images/kosong.svg')}}" alt="" width="70">
                <h5 class="mt-3">PACKAGING</h5>
            </a>
        </div>
    </div>
    <hr>
    <br>
    <div class="row justify-content-left">
        <h2 class="mt-3">INVOICE</h2>
        <div class="col-md-2 text-center mt-5">
            <a href="{{route('rekap.invoice-penjualan')}}" class="text-decoration-none">
                <img src="{{asset('images/invoice-jual.svg')}}" alt="" width="70">
                <h5 class="mt-3">INVOICE KONSUMEN</h5>
            </a>
        </div>
        <div class="col-md-2 text-center mt-5">
            <a href="#" class="text-decoration-none">
                <img src="{{asset('images/kosong.svg')}}" alt="" width="70">
                <h5 class="mt-3">INVOICE SUPPLIER</h5>
            </a>
        </div>

    </div>
    <hr>
    <br>
    <div class="row justify-content-left">
        <h2 class="mt-3">DATA LAMA</h2>
        <div class="col-md-2 text-center mt-5">
            <a href="{{route('rekap.invoice-belanja')}}" class="text-decoration-none">
                <img src="{{asset('images/rekap-invoice.svg')}}" alt="" width="70">
                <h5 class="mt-3">INVOICE PEMBELIAN</h5>
            </a>
        </div>
        <div class="col-md-2 text-center mt-5">
            <a href="{{route('rekap.kas-investor')}}" class="text-decoration-none">
                <img src="{{asset('images/kas-investor.svg')}}" alt="" width="70">
                <h5 class="mt-3">KAS INVESTOR</h5>
            </a>
        </div>
        <div class="col-md-2 text-center mt-5">
            <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#kasKonsumenModal">
                <img src="{{asset('images/kas-konsumen.svg')}}" alt="" width="70">
                <h5 class="mt-3">KAS KONSUMEN</h5>
            </a>
        </div>
        <div class="col-md-2 text-center mt-5">
            <a href="{{route('home')}}" class="text-decoration-none">
                <img src="{{asset('images/dashboard.svg')}}" alt="" width="70">
                <h5 class="mt-3">DASHBOARD</h5>
            </a>
        </div>
    </div>
</div>
@endsection
@push('css')
<link rel="stylesheet" href="{{asset('assets/plugins/select2/select2.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/select2/select2.min.css')}}">
@endpush
@push('js')
<script src="{{asset('assets/plugins/select2/select2.full.min.js')}}"></script>
<script>
    $('#project').select2({
            theme: 'bootstrap-5',
            width: '100%',
            dropdownParent: $('#kasSupplier')
        });
</script>
@endpush
