@extends('layouts.app')
@section('content')
<div class="container text-center">
    <h1>INVENTARIS</h1>
</div>
<div class="container mt-3">
    <div class="row justify-content-left">
        <div class="col-md-3 text-center mb-5 mt-3">
            <a href="{{route('rekap')}}" class="text-decoration-none">
                <img src="{{asset('images/rekap.svg')}}" alt="" width="70">
                <h4 class="mt-2">BARANG<br>HABIS PAKAI</h4>
            </a>
        </div>

        <div class="col-md-3 text-center mb-5 mt-3">
            <a href="{{route('rekap')}}" class="text-decoration-none">
                <img src="{{asset('images/rekap.svg')}}" alt="" width="70">
                <h4 class="mt-2">BARANG<br>TIDAK HABIS PAKAI</h4>
            </a>
        </div>
        <div class="col-md-3 text-center mt-3">
            <a href="{{route('home')}}" class="text-decoration-none">
                <img src="{{asset('images/dashboard.svg')}}" alt="" width="70">
                <h4 class="mt-2">DASHBOARD</h4>
            </a>
        </div>
    </div>
</div>
@endsection
