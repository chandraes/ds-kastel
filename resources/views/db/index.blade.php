@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h1>DATABASE</h1>
</div>
<div class="container mt-5">
    <div class="row justify-content-left">

        @if (auth()->user()->role == 'admin' || auth()->user()->role == 'su')
        <div class="col-lg-3 col-md-3 col-sm-6 my-4 text-center">
            <a href="{{route('db.investor')}}" class="text-decoration-none">
                <img src="{{asset('images/investor.svg')}}" alt="" width="80">
                <h3 class="mt-2">PERSENTASE DIVIDEN</h3>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 my-4 text-center">
            <a href="{{route('db.investor-modal')}}" class="text-decoration-none">
                <img src="{{asset('images/investor-modal.svg')}}" alt="" width="80">
                <h3 class="mt-2">INVESTOR MODAL</h3>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 my-4 text-center">
            <a href="{{route('db.pengelola')}}" class="text-decoration-none">
                <img src="{{asset('images/pengelola.svg')}}" alt="" width="80">
                <h3 class="mt-2">PENGELOLA</h3>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 my-4 text-center">
            <a href="{{route('db.rekening')}}" class="text-decoration-none">
                <img src="{{asset('images/rekening.svg')}}" alt="" width="80">
                <h3 class="mt-2">REKENING TRANSAKSI</h3>
            </a>
        </div>
        @endif
    </div>
    <div class="row justify-content-left">
        {{-- <div class="col-lg-3 col-md-3 col-sm-6 my-4 text-center">
            <a href="{{route('db.customer')}}" class="text-decoration-none">
                <img src="{{asset('images/customer.svg')}}" alt="" width="80">
                <h3 class="mt-2">KONSUMEN</h3>
            </a>
        </div> --}}
        <div class="col-lg-3 col-md-3 col-sm-6 my-4 text-center">
            <a href="{{route('db.konsumen')}}" class="text-decoration-none">
                <img src="{{asset('images/customer.svg')}}" alt="" width="80">
                <h3 class="mt-2">KONSUMEN</h3>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 my-4 text-center">
            <a href="#" class="text-decoration-none">
                <img src="{{asset('images/project.svg')}}" alt="" width="80">
                <h3 class="mt-2">PRODUCT</h3>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 my-4 text-center">
            <a href="{{route('home')}}" class="text-decoration-none">
                <img src="{{asset('images/dashboard.svg')}}" alt="" width="80">
                <h3 class="mt-2">DASHBOARD</h3>
            </a>
        </div>
    </div>
</div>
@endsection

