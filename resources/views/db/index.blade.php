@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h1>DATABASE</h1>
</div>
<div class="container mt-5">
    <div class="row justify-content-left">

        @if (auth()->user()->role == 'admin' || auth()->user()->role == 'su')
        <div class="col-lg-3 col-md-3 col-sm-4 my-4 text-center">
            <a href="{{route('db.investor')}}" class="text-decoration-none">
                <img src="{{asset('images/investor.svg')}}" alt="" width="80">
                <h4 class="mt-2">PERSENTASE DIVIDEN</h4>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-4 my-4 text-center">
            <a href="{{route('db.investor-modal')}}" class="text-decoration-none">
                <img src="{{asset('images/investor-modal.svg')}}" alt="" width="80">
                <h4 class="mt-2">INVESTOR MODAL</h4>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-4 my-4 text-center">
            <a href="{{route('db.pengelola')}}" class="text-decoration-none">
                <img src="{{asset('images/pengelola.svg')}}" alt="" width="80">
                <h4 class="mt-2">PENGELOLA</h4>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-4 my-4 text-center">
            <a href="{{route('db.rekening')}}" class="text-decoration-none">
                <img src="{{asset('images/rekening.svg')}}" alt="" width="80">
                <h4 class="mt-2">REKENING TRANSAKSI</h4>
            </a>
        </div>
        @endif
    </div>
    <div class="row justify-content-left">
        <div class="col-lg-3 col-md-3 col-sm-4 my-4 text-center">
            <a href="{{route('db.supplier')}}" class="text-decoration-none">
                <img src="{{asset('images/supplier.svg')}}" alt="" width="80">
                <h4 class="mt-2">SUPPLIER<br>BAHAN BAKU</h4>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-4 my-4 text-center">
            <a href="{{route('db.konsumen')}}" class="text-decoration-none">
                <img src="{{asset('images/customer.svg')}}" alt="" width="80">
                <h4 class="mt-2">DAFTAR<br>KONSUMEN</h4>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-4 my-4 text-center">
            <a href="{{route('db.pajak')}}" class="text-decoration-none">
                <img src="{{asset('images/pajak.svg')}}" alt="" width="80">
                <h4 class="mt-2">PAJAK</h4>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-4 my-4 text-center">
            <a href="{{route('db.staff')}}" class="text-decoration-none">
                <img src="{{asset('images/karyawan.svg')}}" alt="" width="80">
                <h4 class="mt-2">STAFF</h4>
            </a>
        </div>
    </div>
    <div class="row justify-content-left">
        <div class="col-lg-3 col-md-3 col-sm-4 my-4 text-center">
            <a href="{{route('db.satuan')}}" class="text-decoration-none">
                <img src="{{asset('images/satuan.svg')}}" alt="" width="80">
                <h4 class="mt-2">SATUAN</h4>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-4 my-4 text-center">
            <a href="{{route('db.bahan-baku')}}" class="text-decoration-none">
                <img src="{{asset('images/bahan-baku.svg')}}" alt="" width="80">
                <h4 class="mt-2">DAFTAR<br>BAHAN BAKU</h4>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-4 my-4 text-center">
            <a href="{{route('db.product')}}" class="text-decoration-none">
                <img src="{{asset('images/product.svg')}}" alt="" width="80">
                <h4 class="mt-2">DAFTAR<br>PRODUCT</h4>
            </a>
        </div>
    </div>
    <div class="row justify-content-left">
        <div class="col-lg-3 col-md-3 col-sm-4 my-4 text-center">
            <a href="{{route('db.packaging')}}" class="text-decoration-none">
                <img src="{{asset('images/packaging.svg')}}" alt="" width="80">
                <h4 class="mt-2">PACKAGING</h4>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-4 my-4 text-center">
            <a href="{{route('db.kemasan')}}" class="text-decoration-none">
                <img src="{{asset('images/kemasan.svg')}}" alt="" width="80">
                <h4 class="mt-2">KEMASAN</h4>
            </a>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-4 my-4 text-center">
            <a href="{{route('home')}}" class="text-decoration-none">
                <img src="{{asset('images/dashboard.svg')}}" alt="" width="80">
                <h4 class="mt-2">DASHBOARD</h4>
            </a>
        </div>
    </div>
</div>
@endsection
