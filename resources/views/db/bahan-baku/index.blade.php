@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 text-center">
            <h1><u>Kategori Barang Maintenance</u></h1>
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
    <table class="table table-bordered" id="dataTable">
        <thead class="table-success">
            <tr>
                <th class="text-center align-middle">No</th>
                <th class="text-center align-middle">Kategori</th>
                <th class="text-center align-middle">Bahan Baku</th>
                <th class="text-center align-middle">Konversi</th>
                <th class="text-center align-middle">Stock</th>
                <th class="text-center align-middle">ACT</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kategori as $d)
            @php
                $bahanBakuCount = count($d->bahanBaku);
            @endphp
            <tr>
                <td class="text-center align-middle" rowspan="{{ $bahanBakuCount }}">{{$loop->iteration}}</td>
                <td class="text-center align-middle" rowspan="{{ $bahanBakuCount }}">{{$d->nama}}</td>
                @foreach ($d->bahanBaku as $bahan)
                    @if ($loop->first)
                        <td class="text-center align-middle">{{ $bahan->nama }}</td>
                        <td class="text-center align-middle">1 : {{ $bahan->konversi }}</td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle">
                            <div class="d-flex justify-content-center m-2">
                                <button type="button" class="btn btn-primary mx-2" data-bs-toggle="modal"
                                    data-bs-target="#editModal" onclick="editFun({{ $bahan }}, {{ $bahan->id }})">
                                    Edit
                                </button>
                            <form action="{{ route('db.bahan-baku.delete', $bahan->id) }}" method="post"
                                class="delete-form" data-id="{{ $bahan->id }}">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                            </div>
                            {{-- delete form --}}

                        </td>
                    @else
                        </tr><tr>
                        <td class="text-center align-middle">{{ $bahan->nama }}</td>
                        <td class="text-center align-middle">1 : {{ $bahan->konversi }}</td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle">
                                {{-- edit button --}}
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal" title="Edit Data" onclick="editFun({{ $bahan }}, {{ $bahan->id }})">
                                    Edit
                                </button>
                               {{-- delete form --}}
                               <form action="{{ route('db.bahan-baku.delete', $bahan->id) }}" method="post" id="deleteForm{{ $bahan->id }}"
                                class="delete-form" data-id="{{ $bahan->id }}">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        </td>
                    @endif
                @endforeach
            </tr>
        @endforeach
        </tbody>
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

            document.getElementById('edit_nama').value = data.nama;
            document.getElementById('edit_konversi').value = data.konversi;
            document.getElementById('edit_kategori_bahan_id').value = data.kategori_bahan_id;
            // Populate other fields...
            document.getElementById('editForm').action = '/db/bahan-baku/update/' + id;
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
