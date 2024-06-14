@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 text-center">
            <h1><u>DAFTAR PRODUCT</u></h1>
        </div>
    </div>
    @include('swal')
    @include('db.product.create-kategori')
    <div class="flex-row justify-content-between mt-3">
        <div class="col-md-12">
            <table class="table">
                <tr class="text-center">
                    <td><a href="{{route('home')}}"><img src="{{asset('images/dashboard.svg')}}" alt="dashboard"
                                width="30"> Dashboard</a></td>
                    <td><a href="{{route('db')}}"><img src="{{asset('images/database.svg')}}" alt="dokumen" width="30">
                            Database</a></td>
                    <td>
                    <td><a href="#" data-bs-toggle="modal" data-bs-target="#create-category"><img
                                src="{{asset('images/kategori.svg')}}" alt="dokumen" width="30"> Tambah Kategori</a>
                    </td>
                    <td>
                        <a href="{{route('db.product.create')}}">
                            <img src=" {{asset('images/product.svg')}}" alt="dokumen" width="30"> Tambah Product</a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="container mt-5 table-responsive ">
    <table class="table table-bordered" id="dataTable">
        <thead class="table-success">
            <tr>
                <th class="text-center align-middle" style="width: 15px">No</th>
                <th class="text-center align-middle" style="width: 90px">Kategori (KODE)</th>
                <th class="text-center align-middle">Product</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $d)
            <tr>
                <td class="text-center align-middle">{{$loop->iteration}}</td>
                <td class="text-center align-middle"><strong>{{$d->nama}}<br>({{$d->kode}})</strong></td>
                <td class="align-middle">
                    @if ($d->product->count() > 0)
                    <table class="table table-bordered" id="dataTable2">
                        <thead class="table-primary">
                            <tr>
                                <th class="text-center align-middle">Nama Product</th>
                                <th class="text-center align-middle">Kode</th>
                                <th class="text-center align-middle">Komposisi</th>
                                <th class="text-center align-middle">Konversi</th>
                                <th class="text-center align-middle">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($d->product as $p)
                            <tr>
                                <td class="text-center align-middle" style="width: 20%">{{$p->nama}}</td>
                                <td class="text-center align-middle" style="width: 10%">{{$p->kode}}</td>

                                <td class="text-start align-middle">
                                    @if ($p->komposisi->count() > 0)
                                    <ul>
                                        @foreach ($p->komposisi as $k)
                                        <li class="mb-2">
                                            {{$k->bahan_baku->kategori ? $k->bahan_baku->kategori->nama : '-'}} - {{$k->bahan_baku->nama}} ({{number_format($k->jumlah, 2, ',','.')}}%)
                                        </li>
                                        @endforeach
                                    </ul>
                                    @else
                                    <div class="row text-center">
                                        <div class="col-md-12">
                                            <a href="{{route('db.product.create-komposisi', ['product' => $p->id])}}" class="btn btn-primary m-2">Tambah Komposisi</a>
                                        </div>
                                    </div>
                                    @endif
                                </td>
                                <td class="text-center align-middle">
                                    @if ($p->konversi_liter)
                                    1 KG = {{$p->konversi_liter}} Liter
                                    @endif
                                </td>
                                <td class="text-center align-middle" style="width: 20%">
                                    <div class="row">
                                        @if ($p->komposisi->count() > 0)
                                        <form action="{{route('db.product.kosongkan-komposisi', $p->id)}}" method="post" class="d-block mt-2 w-100 delete-form"  id="deleteForm{{ $p->id }}" data-id="{{ $p->id }}">
                                            @csrf
                                            <button type="submit" class="btn btn-primary w-100"><i class="fa fa-trash"></i> Kosongkan Komposisi</button>
                                        </form>
                                        @endif
                                        <div class="d-block mt-2">
                                            <a href="#" class="btn btn-warning w-100"><i class="fa fa-edit"></i> Edit</a>
                                        </div>
                                        <form action="{{route('db.product.delete', $p->id)}}" method="post" class="d-block mt-2 w-100">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Apakah anda yakin untuk menghapus data ini?')"><i class="fa fa-trash"></i> Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>

        </tfoot>
    </table>
</div>

@endsection
@push('css')
<link href="{{asset('assets/css/dt.min.css')}}" rel="stylesheet">
@endpush
@push('js')
<script src="{{asset('assets/plugins/datatable/datatables.min.js')}}"></script>
<script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>
<script>



    function editFun(data, id) {
        document.getElementById('edit_apa_konversi').value = data.apa_konversi;
        document.getElementById('edit_satuan_id').value = data.satuan_id;
        document.getElementById('edit_nama').value = data.nama;
        document.getElementById('edit_konversi').value = data.konversi;
        document.getElementById('edit_kategori_bahan_id').value = data.kategori_bahan_id;
        // Populate other fields...
        document.getElementById('editForm').action = '/db/bahan-baku/update/' + id;

        if (data.apa_konversi == 1) {
            document.getElementById('edit_konversi').setAttribute('required', true);
            document.getElementById('edit_satuan_id').removeAttribute('required');
            document.getElementById('divKonversiEdit').removeAttribute('hidden');
            document.getElementById('divSatuanEdit').setAttribute('hidden', true);
        } else {
            document.getElementById('edit_konversi').removeAttribute('required');
            document.getElementById('edit_satuan_id').setAttribute('required', true);
            document.getElementById('divKonversiEdit').setAttribute('hidden', true);
            document.getElementById('divSatuanEdit').removeAttribute('hidden');
        }
    }

    function addKomposisi(data, id) {

    }

    function toggleNamaJabatan(id) {

        // check if input is readonly
        if ($('#nama_jabatan-'+id).attr('readonly')) {
            // remove readonly
            $('#nama_jabatan-'+id).removeAttr('readonly');
            $('#kode-'+id).removeAttr('readonly');
            // show button
            $('#buttonJabatan-'+id).removeAttr('hidden');
        } else {
            // add readonly
            $('#nama_jabatan-'+id).attr('readonly', true);
            $('#kode-'+id).attr('readonly', true);
            // hide button
            $('#buttonJabatan-'+id).attr('hidden', true);
        }
    }

    $(document).ready(function() {
        $('#dataTable').DataTable({
            "paging": false,
            "scrollCollapse": true,
            "scrollY": "550px",
        });

        $('#komposisiModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id'); // Extract info from data-* attributes

            // Update the modal's content.
            var modal = $(this);
            modal.find('#product_id').val(id);
        });

    } );

    confirmAndSubmit('#masukForm', 'Apakah anda Yakin?');
    confirmAndSubmit('#editForm', 'Apakah anda Yakin?');
    confirmAndSubmit('#komposisiForm', 'Apakah anda Yakin?');

    $('.delete-form').submit(function(e){
        e.preventDefault();
        var formId = $(this).data('id');
        Swal.fire({
            title: 'Apakah Anda Yakin?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, simpan!'
        }).then((result) => {
            if (result.isConfirmed) {
                $(`#deleteForm${formId}`).unbind('submit').submit();
                $('#spinner').show();
            }
        });
    });
</script>
@endpush
