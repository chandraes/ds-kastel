@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center mb-5">
        <div class="col-md-12 text-center">
            <h1><u>STOK BAHAN JADI</u></h1>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-12">
            <a href="{{route('billing')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Kembali</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered table-sm" id="barangJadi" style="font-size: 11px">
                <thead class="table-primary">
                    <tr>
                        <th class="text-center align-middle">
                            NO
                        </th>
                        <th class="text-center align-middle">
                            KATEGORI<br>PRODUCT
                        </th>
                        <th class="text-center align-middle">
                            JENIS<br>PRODUCT
                        </th>
                        <th class="text-center align-middle">
                            STOK<br>KEMASAN
                        </th>
                        <th class="text-center align-middle">
                            SATUAN<br>KEMASAN
                        </th>
                        <th class="text-center align-middle">
                            STOK<br>PACKAGING (DUS)
                        </th>
                        <th class="text-center align-middle">
                            ACT
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $group)
                    @php $i = 0; @endphp
                    @foreach($group as $d)
                    <tr>
                        @if($i++ == 0)
                        <td class="text-center align-middle" rowspan="{{ count($group) }}">{{$loop->iteration}}</td>
                        <td class="text-center align-middle" rowspan="{{ count($group) }}">
                            {{$d->product->kategori->nama}}</td>
                        <td class="text-center align-middle" rowspan="{{ count($group) }}">{{$d->product->nama}}</td>
                        @endif
                        <td class="text-center align-middle">
                            {{$d->sum_kemasan ?? 0}}
                        </td>
                        <td class="text-center align-middle">
                            {{$d->kemasan->satuan->nama}}
                        </td>
                        <td class="text-center align-middle">
                            {{$d->sum_packaging ?? 0}}
                        </td>
                        <td class="text-center align-middle">
                            <button class="btn btn-primary">Aksi</button>
                        </td>
                    </tr>
                    @endforeach
                    @endforeach
                    {{-- <tr>
                        <td rowspan="3" class="text-center align-middle">1</td>
                        <td rowspan="3" class="text-center align-middle">VERNIZ</td>
                    </tr>
                    <tr>
                        <td class="text-center align-middle">
                             <a href="#">STONE CARE</a>
                        </td>
                        <td class="text-center align-middle">0</td>
                        <td class="text-center align-middle">0</td>
                        <td class="text-center align-middle">0</td>
                        <td class="text-center align-middle">
                            <button class="btn btn-primary btn-sm">AKSI</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center align-middle">
                            <a href="">BLACK</a>
                        </td>
                        <td class="text-center align-middle">0</td>
                        <td class="text-center align-middle">0</td>
                        <td class="text-center align-middle">0</td>
                        <td class="text-center align-middle">
                            <button class="btn btn-primary btn-sm">AKSI</button>
                        </td>
                    </tr> --}}
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@push('js')

    <script src="{{asset('assets/js/cleave.min.js')}}"></script>
    <script>

    </script>
@endpush
