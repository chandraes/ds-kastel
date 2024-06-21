@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 text-center">
            <h1><u>DAFTAR HARGA JUAL PRODUCT</u></h1>
        </div>
    </div>
    <div class="flex-row justify-content-between mt-3">
        <div class="col-md-6">
            <table class="table" id="data-table">
                <tr>
                    <td><a href="{{route('home')}}"><img src="{{asset('images/dashboard.svg')}}" alt="dashboard"
                                width="30"> Dashboard</a></td>
                    <td><a href="{{route('db')}}"><img src="{{asset('images/database.svg')}}" alt="dokumen" width="30">
                            Database</a></td>
                </tr>
            </table>
        </div>
    </div>
</div>
@include('db.kemasan-harga-jual.edit')
<div class="container mt-5 table-responsive">
    <table class="table table-bordered" id="data">
        <thead class="table-warning bg-gradient">
            <tr>
                <th class="text-center align-middle" style="width: 5%">NO</th>
                <th class="text-center align-middle">KATEGORI</th>
                <th class="text-center align-middle">JENIS</th>
                <th class="text-center align-middle">NAMA KEMASAN</th>
                <th class="text-center align-middle">PACKAGING</th>
                <th class="text-center align-middle">ISI PACKAGING</th>
                <th class="text-center align-middle">HARGA PER KEMASAN</th>
                <th class="text-center align-middle">HARGA PER PACKAGING</th>
                <th class="text-center align-middle">ACT</th>
            </tr>
        </thead>
        <tbody>
            @php
                $categoryRowspans = [];
                foreach ($data as $d) {
                    $categoryName = $d->kategori->nama;
                    if (!isset($categoryRowspans[$categoryName])) {
                        $categoryRowspans[$categoryName] = 0;
                    }
                    $categoryRowspans[$categoryName] += max(count($d->kemasan), 1);
                }
                $categoryCounters = [];
                $no = 1;
            @endphp

            @foreach ($data as $d)
                @php
                    $rowspan = count($d->kemasan) > 0 ? count($d->kemasan) : 1;
                    $categoryName = $d->kategori->nama;
                    if (!isset($categoryCounters[$categoryName])) {
                        $categoryCounters[$categoryName] = 0;
                    }
                    $isFirstCategoryRow = $categoryCounters[$categoryName] === 0;
                    $categoryCounters[$categoryName]++;
                @endphp
                @if (count($d->kemasan) > 0)
                    @foreach ($d->kemasan as $indexK => $k)
                        <tr>
                            @if ($indexK == 0)
                                @if ($isFirstCategoryRow)
                                    <td class="text-center align-middle" rowspan="{{ $categoryRowspans[$categoryName] }}">{{ $no++ }}</td>
                                    <td class="text-center align-middle" rowspan="{{ $categoryRowspans[$categoryName] }}">{{ $d->kategori->nama }}</td>
                                @endif
                                <td class="text-center align-middle" rowspan="{{ $rowspan }}">{{ $d->nama }}</td>
                            @endif
                            <td class="text-center align-middle">{{ $k->kategori ? $k->kategori->nama : '' }}</td>
                             <td class="text-center align-middle">
                                @if ($k->packaging_id)
                                    {{ $k->packaging->nama }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                @if ($k->packaging_id)
                                    {{ $k->packaging->konversi_kemasan }}
                                @else
                                    -
                                @endif
                            <td class="text-end align-middle">{{$k->nf_harga_satuan}}</td>
                            <td class="text-end align-middle">{{$k->nf_harga}}</td>
                            <td class="text-center align-middle">
                                <div class="d-flex justify-content-center">
                                    <button type="button" class="btn btn-primary m-2" data-bs-toggle="modal" data-bs-target="#editInvestor" onclick="editHargaJual({{ $k }}, {{ $k->id }})"><i class="fa fa-edit"></i> {{$k->harga != null ? 'Edit': 'Tambah'}} Harga</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        @if ($isFirstCategoryRow)
                            <td class="text-center align-middle" rowspan="{{ $categoryRowspans[$categoryName] }}">{{ $no++ }}</td>
                            <td class="text-center align-middle" rowspan="{{ $categoryRowspans[$categoryName] }}">{{ $d->kategori->nama }}</td>
                        @endif
                        <td class="text-center align-middle">{{ $d->nama }}</td>
                        <td class="text-center align-middle" colspan="7">-</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>

@endsection
@push('css')
<link href="{{asset('assets/css/dt.min.css')}}" rel="stylesheet">
<style>


    #data {
        border-collapse: collapse;
    }
    #data thead th {
        position: sticky;
        top: 0;
        z-index: 1;
    }
    /* #data tbody {
        display: block;
        max-height: 550px;
        overflow-y: auto;
    } */
    /* #data thead, #data tbody tr {
        display: table;
        width: 100%;
        table-layout: fixed;
    } */

</style>
@endpush
@push('js')

<script src="{{asset('assets/js/dt5.min.js')}}"></script>
<script>
    function editHargaJual(data, id) {
        console.log(data.packaging);
        document.getElementById('edit_product_id').value = data.product_id;
        document.getElementById('edit_packaging_id').value = data.packaging_id;
        document.getElementById('kemasan_kategori_id').value = data.nama;
        document.getElementById('edit_harga_satuan').value = data.nf_harga_satuan;
        if (data.packaging) {
            document.getElementById('edit_isi_kemasan').value = data.packaging.konversi_kemasan;
        }
        else {
            document.getElementById('edit_isi_kemasan').value = 1;
        }
        document.getElementById('edit_harga').value = data.nf_harga;
        document.getElementById('editForm').action = '/db/harga-jual/update/' + id;
    }

        var harga_satuan = new Cleave('#edit_harga_satuan', {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand',
            numeralDecimalMark: ',',
            delimiter: '.'
        });

        function calculateTotal()
        {
            let harga_satuan = document.getElementById('edit_harga_satuan').value;
            harga_satuan = harga_satuan.replace(/\./g, '');
            console.log('harga satuan: ', harga_satuan);
            let isi_kemasan = document.getElementById('edit_isi_kemasan').value;
            console.log('isi kemasan: ', isi_kemasan);
            let harga = harga_satuan * isi_kemasan;
            // make it to number format with . separator
            let hargaIdr = harga.toLocaleString('id-ID');
            document.getElementById('edit_harga').value = hargaIdr;
        }

    confirmAndSubmit('#createForm', "Apakah anda yakin?");
    confirmAndSubmit('#editForm', "Apakah anda yakin?");

</script>
@endpush
