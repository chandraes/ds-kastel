@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center mb-5">
        <div class="col-md-12 text-center">
            <h1><u>REKAP PO</u></h1>
        </div>
    </div>
    @include('swal')
    <div class="row justify-content-between mt-3">
        <div class="col-md-6">
            <table class="table">
                <tr class="text-center">
                    <td><a href="{{route('home')}}"><img src="{{asset('images/dashboard.svg')}}" alt="dashboard"
                                width="30"> Dashboard</a></td>
                    <td><a href="{{route('rekap')}}"><img src="{{asset('images/rekap.svg')}}" alt="dokumen" width="30">
                            REKAP</a></td>
                </tr>
            </table>
        </div>
        <form action="{{route('po.rekap')}}" method="get" class="col-md-6">
            <div class="row mt-2">

                <div class="col-md-4 mb-3">
                    <select class="form-select" name="tahun" id="tahun">
                        @foreach ($dataTahun as $dt)
                        <option value="{{$dt->tahun}}" {{$dt->tahunArray == $tahun ? 'selected' : ''}}>{{$dt->tahun}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-8 mb-3">
                    <button type="submit" class="btn btn-primary form-control" id="btn-cari">Tampilkan</button>
                </div>
                {{-- <div class="col-md-3 mb-3">
                    <label for="showPrint" class="form-label">&nbsp;</label>
                    <a href="{{route('rekap.kas-besar.preview', ['bulan' => $bulan, 'tahun' => $tahun])}}"
                        target="_blank" class="btn btn-secondary form-control" id="btn-cari">Print Preview</a>
                </div> --}}
            </div>
        </form>
    </div>
    <div class="row mt-3">
        <table class="table table-bordered table-hover" id="data-table">
            <thead class="table-success">
                <tr>
                    <th class="text-center align-middle">No</th>
                    <th class="text-center align-middle">Tanggal</th>
                    <th class="text-center align-middle">No PO</th>
                    <th class="text-center align-middle">Kepada</th>
                    <th class="text-center align-middle">Alamat</th>
                    <th class="text-center align-middle">Telepon</th>
                    <th class="text-center align-middle">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $d)
                <tr>
                    <td></td>
                    <td class="text-center align-middle">{{$d->tanggal}}</td>
                    <td class="text-center align-middle">{{$d->full_nomor}}</td>
                    <td class="text-center align-middle">{{$d->kepada}}</td>
                    <td class="text-center align-middle">{{$d->alamat}}</td>
                    <td class="text-center align-middle">{{$d->telepon}}</td>
                    <td>
                        <a href="{{route('po.rekap.pdf', ['po' => $d])}}" class="btn btn-primary btn-sm">Detail</a>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>

</div>
@endsection
@push('css')
<link href="{{asset('assets/css/dt.min.css')}}" rel="stylesheet">
<link rel="stylesheet" href="{{asset('assets/js/flatpickr/flatpickr.min.css')}}">
<script src="{{asset('assets/js/flatpickr/flatpickr.js')}}"></script>
<script src="{{asset('assets/js/cleave.min.js')}}"></script>
@endpush
@push('js')
<script src="{{asset('assets/js/dt5.min.js')}}"></script>
<script>
    function detailInvoice(data)
    {
        document.getElementById('detailModalTitle').innerHTML = 'INVOICE : '+data.invoice;
    }
    $(document).ready(function() {
        var table = $('#data-table').DataTable({
            "paging": false,
            "searching": true,
            "scrollCollapse": true,
            "scrollY": "500px",

        });

        table.on( 'order.dt search.dt', function () {
            table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
            } );
        } ).draw();
    });
</script>
@endpush
