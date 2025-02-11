@extends('layouts.app')
@section('content')
<div class="container text-center">
    <h1><u>BILLING</u></h1>
</div>
@include('swal')
<div class="container mt-3">
    <div class="row justify-content-left">
        <h4 class="mt-3">UMUM</h4>
        <div class="col-md-2 text-center mt-5">
            <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#formDeposit">
                <img src="{{asset('images/form-deposit.svg')}}" alt="" width="70">
                <h4 class="mt-3">FORM DEPOSIT</h4>
            </a>
            @include('billing.modal-form-deposit')
        </div>
        <div class="col-md-2 text-center mt-5">
            <a href="#" class="text-decoration-none">
                <img src="{{asset('images/kosong.svg')}}" alt="" width="70">
                <h4 class="mt-3">FORM DIVIDEN</h4>
            </a>
        </div>
        <div class="col-md-2 text-center mt-5">
            <a href="#" class="text-decoration-none">
                <img src="{{asset('images/kosong.svg')}}" alt="" width="70">
                <h4 class="mt-3">FORM KASBON</h4>
            </a>
        </div>
        <div class="col-md-2 text-center mt-5">
            <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#modalLain">
                <img src="{{asset('images/form-lain.svg')}}" alt="" width="70">
                <h4 class="mt-3">FORM LAIN-LAIN</h4>
            </a>
            <div class="modal fade" id="modalLain" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
                role="dialog" aria-labelledby="modalLainTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalLainTitle">Form Lain-lain</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <select class="form-select" name="selectLain" id="selectLain">
                                <option value="masuk">Dana Masuk</option>
                                <option value="keluar">Dana Keluar</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="button" class="btn btn-primary" onclick="funLain()">Lanjutkan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2 text-center mt-5">
            <a href="#" class="text-decoration-none">
                <img src="{{asset('images/kosong.svg')}}" alt="" width="70">
                <h4 class="mt-3">FORM GANTI RUGI</h4>
            </a>
        </div>
        <div class="col-md-2 text-center mt-5">
            <a href="#" class="text-decoration-none">
                <img src="{{asset('images/kosong.svg')}}" alt="" width="70">
                <h4 class="mt-3">FORM CSR<br>(TIDAK TERTENTU)</h4>
            </a>
        </div>

    </div>
    <hr>
    <br>
    <div class="row justify-content-left">
        <h4 class="mt-3">COST OPERATIONAL</h4>
        <div class="col-md-2 text-center mt-5">
            <a href="{{route('billing.form-cost-operational.cost-operational')}}" class="text-decoration-none">
                <img src="{{asset('images/form-cost-operational.svg')}}" alt="" width="70">
                <h4 class="mt-3">FORM OPERATIONAL</h4>
            </a>
        </div>
        <div class="col-md-2 text-center mt-5">
            <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#formKecil">
                <img src="{{asset('images/kas-kecil.svg')}}" alt="" width="70">
                <h4 class="mt-3">FORM KAS KECIL</h4>
            </a>
            @include('billing.modal-form-kas-kecil')
        </div>
        <div class="col-md-2 text-center mt-5">
            <a href="{{route('billing.form-cost-operational.gaji')}}" class="text-decoration-none">
                <img src="{{asset('images/gaji.svg')}}" alt="" width="70">
                <h4 class="mt-3">FORM GAJI</h4>
            </a>
        </div>
        <div class="col-md-2 text-center mt-5">
            <a href="{{route('billing.bunga-investor')}}" class="text-decoration-none">
                <img src="{{asset('images/bunga-kreditor.svg')}}" alt="" width="70">
                <h4 class="mt-3">FORM BUNGA KREDITUR</h4>
            </a>
        </div>
    </div>
    <hr>
    <br>
    <div class="row justify-content-left">
        <h4 class="mt-3">TRANSAKSI</h4>
        {{-- <div class="col-md-2 text-center mt-5">
            <a href="{{route('billing.form-transaksi')}}" class="text-decoration-none">
                <img src="{{asset('images/transaksi.svg')}}" alt="" width="70">
                <h4 class="mt-3">FORM TRANSAKSI</h4>
            </a>
        </div> --}}
        <div class="col-md-2 text-center mt-5">
            <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#bahanBakuModal">
                <img src="{{asset('images/form-bahan-baku.svg')}}" alt="" width="70">
                <h4 class="mt-4">FORM BAHAN BAKU</h4>
            </a>
        </div>
        <div class="col-md-2 text-center mt-5">
            <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#kemasanModal">
                <img src="{{asset('images/kemasan.svg')}}" alt="" width="70">
                <h4 class="mt-4">FORM KEMASAN</h4>
            </a>
        </div>
        <div class="col-md-2 text-center mt-5">
            <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#packagingModal">
                <img src="{{asset('images/packaging.svg')}}" alt="" width="70">
                <h4 class="mt-4">FORM PACKAGING</h4>
            </a>
        </div>
        @include('billing.form-transaksi.modal-bahan-baku')
        @include('billing.form-transaksi.modal-kemasan')
        @include('billing.form-transaksi.modal-packaging')

        {{-- <div class="col-md-2 text-center mt-5">
            <a href="{{route('billing.form-cost-operational')}}" class="text-decoration-none">
                <img src="{{asset('images/form-cost-operational.svg')}}" alt="" width="70">
                <h4 class="mt-3">FORM COST<br>OPERATIONAL</h4>
            </a>
        </div> --}}

    </div>
    <hr>
    <br>
    <div class="row justify-content-left">
        <h4 class="mt-3">PRODUKSI</h4>
        <div class="col-md-2 text-center mt-5">
            <a href="{{route('billing.produksi')}}" class="text-decoration-none">
                <img src="{{asset('images/produksi.svg')}}" alt="" width="70">
                <h4 class="mt-3">PRODUKSI</h4>
            </a>
        </div>
        <div class="col-md-2 text-center mt-5">
            <a href="{{route('billing.stok-bahan-jadi.rencana')}}" class="text-decoration-none">
                <img src="{{asset('images/rencana.svg')}}" alt="" width="70">
                <h4 class="mt-3">RENCANA STOCK<br>BAHAN JADI
                    @if($rp != 0) <span class="text-danger">({{$rp}})</span> @endif
                </h4>
            </a>
        </div>
        <div class="col-md-2 text-center mt-5">
            <a href="{{route('billing.stok-bahan-jadi')}}" class="text-decoration-none">
                <img src="{{asset('images/product-jadi.svg')}}" alt="" width="70">
                <h4 class="mt-3">STOCK BAHAN JADI</h4>
            </a>
        </div>
    </div>
    <hr>
    <br>
    <div class="row justify-content-left">
        <h4 class="mt-3">INVOICE</h4>
        <div class="col-md-2 text-center mt-5">
            <a href="{{route('billing.invoice-jual')}}" class="text-decoration-none">
                <img src="{{asset('images/invoice-jual.svg')}}" alt="" width="70">
                <h4 class="mt-3">INVOICE KONSUMEN
                    @if($ij != 0) <span class="text-danger">({{$ij}})</span> @endif
                </h4>
            </a>
        </div>
        <div class="col-md-2 text-center mt-5">
            <a href="{{route('billing.form-transaksi.bahan-baku.hutang-belanja')}}" class="text-decoration-none">
                <img src="{{asset('images/nota-belanja.svg')}}" alt="" width="70">
                <h4 class="mt-4">INVOICE SUPPLIER
                    @if ($hb > 0)
                    <span class="text-danger">({{$hb}})</span>
                    @endif
                </h4>
            </a>
        </div>
        <div class="col-md-2 text-center mt-5">
            <a href="{{route('home')}}" class="text-decoration-none">
                <img src="{{asset('images/dashboard.svg')}}" alt="" width="70">
                <h4 class="mt-3">DASHBOARD</h4>
            </a>
        </div>
    </div>
    <hr>
    <br>
   {{-- <div class="row justify-content-left">
        <h4 class="mt-3">INVENTARIS</h4>
        <div class="col-md-2 text-center mt-5">
            <a href="{{route('billing.form-inventaris')}}" class="text-decoration-none">
                <img src="{{asset('images/form-inventaris.svg')}}" alt="" width="70">
                <h4 class="mt-3">FORM INVENTARIS
                </h4>
            </a>
        </div>

    </div> --}}
</div>
@endsection
@push('js')
<script>
    function funDeposit(){
        var selectDeposit = document.getElementById('selectDeposit').value;
        if(selectDeposit == 'masuk'){
            window.location.href = "{{route('form-deposit.masuk')}}";
        }else if(selectDeposit == 'keluar'){
            window.location.href = "{{route('form-deposit.keluar')}}";
        }else if(selectDeposit == 'keluar-all'){
            window.location.href = "{{route('form-deposit.keluar-all')}}";
        }
    }

    function funLain(){
        var selectLain = document.getElementById('selectLain').value;
        if(selectLain == 'masuk'){
            window.location.href = "{{route('form-lain.masuk')}}";
        }else if(selectLain == 'keluar'){
            window.location.href = "{{route('form-lain.keluar')}}";
        }
    }

    function funKecil(){
        var selectKecil = document.getElementById('selectKecil').value;
        if(selectKecil == 'masuk'){
            window.location.href = "{{route('form-kas-kecil.masuk')}}";
        }else if(selectKecil == 'keluar'){
            window.location.href = "{{route('form-kas-kecil.keluar')}}";
        }
    }


</script>
@endpush
