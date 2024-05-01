@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 text-center">
            <h1><u>BAHAN BAKU</u></h1>
        </div>
    </div>
    @include('swal')
    @include('db.bahan-baku.create')
    @include('db.bahan-baku.create-kategori')
    @include('db.bahan-baku.edit')
    <div class="flex-row justify-content-between mt-3">
        <div class="col-md-12">
            <table class="table">
                <tr class="text-center">
                    <td><a href="{{route('home')}}"><img src="{{asset('images/dashboard.svg')}}" alt="dashboard"
                                width="30"> Dashboard</a></td>
                    <td><a href="{{route('db')}}"><img src="{{asset('images/database.svg')}}" alt="dokumen"
                                width="30"> Database</a></td>
                    <td>
                        <td><a href="#" data-bs-toggle="modal" data-bs-target="#create-category"><img src="{{asset('images/kategori.svg')}}" alt="dokumen"
                            width="30"> Tambah Kategori</a></td>
                    </td>
                    <td>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#createModal"">
                            <img src=" {{asset('images/bahan-baku.svg')}}" alt="dokumen" width="30"> Tambah Bahan Baku</a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="container mt-5 table-responsive ">
    <div class="text-center">
        <h2>KONVERSI</h2>
    </div>
    <table class="table table-bordered" id="dataTable">
        <thead class="table-success">
            <tr>
                <th class="text-center align-middle">No</th>
                <th class="text-center align-middle">Kategori</th>
                <th class="text-center align-middle">Bahan Baku</th>
                <th class="text-center align-middle">Konversi</th>
                <th class="text-center align-middle">Liter</th>
                <th class="text-center align-middle">Kg</th>
                <th class="text-center align-middle">Modal</th>
                <th class="text-center align-middle">ACT</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data->where('apa_konversi', 1) as $d)
                <tr>
                    <td class="text-center align-middle">{{$loop->iteration}}</td>
                    <td class="text-center align-middle">
                        @if ($d->kategori)
                        {{$d->kategori->nama}}
                        @endif
                    </td>
                    <td class="text-center align-middle">{{$d->nama}}</td>
                    <td class="text-center align-middle">1 : {{$d->konversi}}</td>
                    <td class="text-center align-middle">{{$d->stock}}</td>
                    <td class="text-center align-middle">{{$d->stock * $d->konversi}}</td>
                    <td class="text-end align-middle">
                        {{$d->nf_modal}}
                    </td>
                    <td class="text-center align-middle">
                        <div class="d-flex justify-content-center m-2">
                            <button type="button" class="btn btn-primary mx-2" data-bs-toggle="modal"
                                data-bs-target="#editModal" onclick="editFun({{ $d }}, {{ $d->id }})">
                                Edit
                            </button>
                        <form action="{{ route('db.bahan-baku.delete', $d->id) }}" method="post" id="deleteForm{{ $d->id }}"
                            class="delete-form" data-id="{{ $d->id }}">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th class="text-center align-middle" colspan="4">
                    Grand Total
                </th>
                <th class="text-center align-middle">
                    {{$data->where('apa_konversi', 1)->sum('stock')}}
                </th>
                <th class="text-center align-middle">
                    {{$data->where('apa_konversi', 1)->sum('stock') * $data->where('apa_konversi', 1)->sum('konversi')}}
                </th>
                <th class="text-end align-middle">
                    {{$data->where('apa_konversi', 1)->sum('nf_modal')}}
                </th>
                <th class="text-center align-middle"></th>
            </tr>
        </tfoot>
    </table>
</div>
<div class="container mt-5 table-responsive ">
    <div class="text-center">
        <h2>NON KONVERSI</h2>
    </div>
    <table class="table table-bordered" id="dataTable2">
        <thead class="table-success">
            <tr>
                <th class="text-center align-middle">No</th>
                <th class="text-center align-middle">Kategori</th>
                <th class="text-center align-middle">Bahan Baku</th>
                <th class="text-center align-middle">Stok</th>
                <th class="text-center align-middle">Satuan</th>
                <th class="text-center align-middle">Modal</th>
                <th class="text-center align-middle">ACT</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data->where('apa_konversi', 0) as $a)
                <tr>
                    <td class="text-center align-middle">{{$loop->iteration}}</td>
                    <td class="text-center align-middle">
                        @if ($a->kategori)
                        {{$a->kategori->nama}}
                        @endif
                    </td>
                    <td class="text-center align-middle">{{$a->nama}}</td>
                    <td class="text-center align-middle">{{$a->stock}}</td>
                    <td class="text-center align-middle">
                        @if ($a->satuan)
                        {{$a->satuan->nama}}
                        @endif
                    </td>
                    <td class="text-end align-middle">
                        {{$a->nf_modal}}
                    </td>
                    <td class="text-center align-middle">
                        <div class="d-flex justify-content-center m-2">
                            <button type="button" class="btn btn-primary mx-2" data-bs-toggle="modal"
                                data-bs-target="#editModal" onclick="editFun({{ $a }}, {{ $a->id }})">
                                Edit
                            </button>
                        <form action="{{ route('db.bahan-baku.delete', $a->id) }}" method="post" id="deleteForm{{ $a->id }}"
                            class="delete-form" data-id="{{ $a->id }}">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th class="text-center align-middle" colspan="3">
                    Grand Total
                </th>
                <th class="text-center align-middle">
                    {{$data->where('apa_konversi', 0)->sum('stock')}}
                </th>
                <th class="text-center align-middle"></th>
                <th class="text-end align-middle">
                    {{$data->where('apa_konversi', 0)->sum('nf_modal')}}
                </th>
                <th class="text-center align-middle"></th>
            </tr>
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
    function createFun() {
        var apa_konversi = document.getElementById('apa_konversi').value;
        if (apa_konversi == 1) {
            document.getElementById('konversi').setAttribute('required', true);
            document.getElementById('satuan_id').removeAttribute('required');
            document.getElementById('divKonversi').removeAttribute('hidden');
            document.getElementById('divSatuan').setAttribute('hidden', true);
        } else {
            document.getElementById('konversi').removeAttribute('required');
            document.getElementById('satuan_id').setAttribute('required', true);
            document.getElementById('divKonversi').setAttribute('hidden', true);
            document.getElementById('divSatuan').removeAttribute('hidden');
        }
    }

    function createFunEdit() {
        var apa_konversi = document.getElementById('edit_apa_konversi').value;
        if (apa_konversi == 1) {
            document.getElementById('edit_konversi').setAttribute('required', true);
            document.getElementById('edit_satuan_id').removeAttribute('required');
            document.getElementById('edit_satuan_id').value = '';

            document.getElementById('divKonversiEdit').removeAttribute('hidden');
            document.getElementById('divSatuanEdit').setAttribute('hidden', true);
        } else {
            document.getElementById('edit_konversi').removeAttribute('required');
            document.getElementById('edit_konversi').value = '';
            document.getElementById('edit_satuan_id').setAttribute('required', true);
            document.getElementById('divKonversiEdit').setAttribute('hidden', true);
            document.getElementById('divSatuanEdit').removeAttribute('hidden');
        }
    }

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

        function toggleNamaJabatan(id) {

            // check if input is readonly
            if ($('#nama_jabatan-'+id).attr('readonly')) {
                // remove readonly
                $('#nama_jabatan-'+id).removeAttr('readonly');
                // show button
                $('#buttonJabatan-'+id).removeAttr('hidden');
            } else {
                // add readonly
                $('#nama_jabatan-'+id).attr('readonly', true);
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
        $('#dataTable2').DataTable({
            "paging": false,
            "scrollCollapse": true,
            "scrollY": "550px",
        });
    } );

    $('#masukForm').submit(function(e){
            e.preventDefault();
            Swal.fire({
                title: 'Apakah Data yang anda masukan sudah benar?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, simpan!'
                }).then((result) => {
                if (result.isConfirmed) {
                    $('#spinner').show();
                    this.submit();
                }
            })
        });

        $('#editForm').submit(function(e){
            e.preventDefault();
            Swal.fire({
                title: 'Apakah Data yang anda masukan sudah benar?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, simpan!'
                }).then((result) => {
                if (result.isConfirmed) {
                    $('#spinner').show();
                    this.submit();
                }
            })
        });

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
