@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 text-center">
            <h1><u>REKAP INVENTARIS</u></h1>
        </div>
    </div>
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
    </div>
</div>
<div class="container table-responsive ml-3">
    <div class="row mt-3">
        <table class="table table-bordered" id="rekapTable">
            <thead class=" table-success">
                <tr>
                    <th class="text-center align-middle">No</th>
                    <th class="text-center align-middle">Kategori</th>
                    <th class="text-center align-middle">Jenis</th>
                    <th class="text-center align-middle">Jumlah</th>
                    <th class="text-center align-middle">Total Harga</th>
                    <th class="text-center align-middle">Subtotal Harga</th>
                </tr>
            </thead>
            <div>
                <tbody>
                    @foreach ($data as $d)
                    @php
                        $subtotal = 0;
                        foreach ($d->jenis as $j) {
                            $subtotal += $j->rekap->sum('total');
                        }

                        $rowspan = $d->jenis->count();
                    @endphp
                    @foreach ($d->jenis as $index => $j)
                        @if ($index == 0)
                            <tr>
                                <td class="text-center align-middle" rowspan="{{$rowspan}}">{{ $loop->iteration }}</td>
                                <td class="text-center align-middle" rowspan="{{$rowspan}}">{{$d->nama}}</td>
                                <td class="text-center align-middle">
                                    <div class="row px-3">
                                        <a href="{{route('rekap.inventaris.detail', ['jenis' => $j->id])}}" class="btn btn-outline-dark">{{$j->nama}}</a>
                                    </div>
                                </td>
                                <td class="text-center align-middle">{{number_format($j->rekap->sum('jumlah'), 0, ',', '.')}}</td>
                                <td class="text-end align-middle">{{number_format($j->rekap->sum('total'), 0, ',', '.')}}</td>
                                <td class="text-end align-middle" rowspan="{{$rowspan}}">{{number_format($subtotal, 0, ',', '.')}}</td>
                            </tr>
                        @else
                            <tr>
                                <td class="text-center align-middle">
                                    <div class="row px-3">
                                        <a href="{{route('rekap.inventaris.detail', ['jenis' => $j->id])}}" class="btn btn-outline-dark">{{$j->nama}}</a>
                                    </div>
                                </td>
                                <td class="text-center align-middle">{{number_format($j->rekap->sum('jumlah'), 0, ',', '.')}}</td>
                                <td class="text-end align-middle">{{number_format($j->rekap->sum('total'), 0, ',', '.')}}</td>
                            </tr>
                        @endif
                    @endforeach
                    @endforeach
                </tbody>
            </div>
        </table>
    </div>
</div>
@endsection
@push('css')
<link href="{{asset('assets/css/dt.min.css')}}" rel="stylesheet">
@endpush
@push('js')
<script src="{{asset('assets/js/dt5.min.js')}}"></script>
<script>

    $(document).ready(function() {


    });


</script>
@endpush
