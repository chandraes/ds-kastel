@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h1>DATABASE</h1>
</div>
<div class="container mt-5">
    <div class="row justify-content-left">

        @if (auth()->user()->role == 'admin' || auth()->user()->role == 'su')
        <div class="col-lg-2 col-md-2 col-sm-4 my-4 text-center">
            <a href="{{route('db.investor')}}" class="text-decoration-none">
                <img src="{{asset('images/investor.svg')}}" alt="" width="70">
                <h5 class="mt-2">PERSENTASE DIVIDEN<br>PENGELOLA & INVESTOR</h5>
            </a>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-4 my-4 text-center">
            <a href="{{route('db.pengelola')}}" class="text-decoration-none">
                <img src="{{asset('images/pengelola.svg')}}" alt="" width="70">
                <h5 class="mt-2">PERSENTASE DIVIDEN<br>PENGELOLA</h5>
            </a>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-4 my-4 text-center">
            <a href="{{route('db.investor-modal')}}" class="text-decoration-none">
                <img src="{{asset('images/investor-modal.svg')}}" alt="" width="70">
                <h5 class="mt-2">NAMA<br>INVESTOR</h5>
            </a>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-4 my-4 text-center">
            <a href="{{route('db.rekening')}}" class="text-decoration-none">
                <img src="{{asset('images/rekening.svg')}}" alt="" width="70">
                <h5 class="mt-2">REKENING<br>TRANSAKSI</h5>
            </a>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-4 my-4 text-center">
            <a href="{{route('db.pajak')}}" class="text-decoration-none">
                <img src="{{asset('images/pajak.svg')}}" alt="" width="70">
                <h5 class="mt-2">PERSENTASE<br>PAJAK</h5>
            </a>
        </div>
    </div>
    <div class="row justify-content-left">
        <div class="col-lg-2 col-md-2 col-sm-4 my-4 text-center">
            <a href="{{route('db.staff')}}" class="text-decoration-none">
                <img src="{{asset('images/karyawan.svg')}}" alt="" width="70">
                <h5 class="mt-2">BIODATA<br>DIREKSI & STAFF</h5>
            </a>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-4 my-4 text-center">
            <a href="{{route('db.supplier')}}" class="text-decoration-none">
                <img src="{{asset('images/supplier.svg')}}" alt="" width="70">
                <h5 class="mt-2">BIODATA<br>SUPPLIER<br></h5>
            </a>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-4 my-4 text-center">
            <a href="{{route('db.konsumen')}}" class="text-decoration-none">
                <img src="{{asset('images/customer.svg')}}" alt="" width="70">
                <h5 class="mt-2">BIODATA<br>KONSUMEN</h5>
            </a>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-4 my-4 text-center">
            <a href="{{route('db.cost-operational')}}" class="text-decoration-none">
                <img src="{{asset('images/cost-operational.svg')}}" alt="" width="70">
                <h5 class="mt-2">KATEGORI COST<br>OPERASIONAL</h5>
            </a>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-4 my-4 text-center">
            <a href="{{route('db.kategori-inventaris')}}" class="text-decoration-none">
                <img src="{{asset('images/kategori-inventaris.svg')}}" alt="" width="70">
                <h5 class="mt-2">KATEGORI<br>INVENTARIS</h5>
            </a>
        </div>
        @endif
    </div>
    <div class="row justify-content-left">
        <div class="col-lg-2 col-md-2 col-sm-4 my-4 text-center">
            <a href="{{route('db.satuan')}}" class="text-decoration-none">
                <img src="{{asset('images/satuan.svg')}}" alt="" width="70">
                <h5 class="mt-2">KATEGORI<br>SATUAN</h5>
            </a>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-4 my-4 text-center">
            <a href="{{route('db.bahan-baku')}}" class="text-decoration-none">
                <img src="{{asset('images/bahan-baku.svg')}}" alt="" width="70">
                <h5 class="mt-2">DAFTAR<br>BAHAN BAKU</h5>
            </a>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-4 my-4 text-center">
            <a href="{{route('db.product')}}" class="text-decoration-none">
                <img src="{{asset('images/product.svg')}}" alt="" width="70">
                <h5 class="mt-2">DAFTAR<br>PRODUCT</h5>
            </a>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-4 my-4 text-center">
            <a href="{{route('db.packaging')}}" class="text-decoration-none">
                <img src="{{asset('images/packaging.svg')}}" alt="" width="70">
                <h5 class="mt-2">KONVERSI<br>PACKAGING</h5>
            </a>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-4 my-4 text-center">
            <a href="{{route('db.kemasan')}}" class="text-decoration-none">
                <img src="{{asset('images/kemasan.svg')}}" alt="" width="70">
                <h5 class="mt-2">KONVERSI<br>KEMASAN</h5>
            </a>
        </div>

        <div class="col-lg-2 col-md-2 col-sm-4 my-4 text-center">
            <a href="{{route('home')}}" class="text-decoration-none">
                <img src="{{asset('images/dashboard.svg')}}" alt="" width="70">
                <h5 class="mt-2">DASHBOARD</h5>
            </a>
        </div>
    </div>
</div>
@endsection
