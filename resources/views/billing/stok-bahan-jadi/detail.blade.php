@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center mb-5">
        <div class="col-md-12 text-center">
            <h1><u>RENCANA STOK BAHAN JADI</u></h1>
        </div>
    </div>
    {{-- back button --}}
    <div class="row mb-3">
        <div class="col-md-12">
            <a href="{{route('billing')}}" class="btn btn-primary">Kembali</a>
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
                            KODE<br>PRODUKSI
                        </th>
                        <th class="text-center align-middle">
                            TANGGAL<br>PRODUKSI
                        </th>
                        <th class="text-center align-middle">
                            TANGGAL<br>EXPIRED
                        </th>
                        <th class="text-center align-middle">
                            RENCANA<br>KEMASAN
                        </th>
                        <th class="text-center align-middle">
                            RENCANA<br>PACKAGING
                        </th>
                        <th class="text-center align-middle">
                            JUMLAH<br>PRODUKSI
                        </th>
                        <th class="text-center align-middle">
                            REAL<br>KEMASAN
                        </th>
                        <th class="text-center align-middle">
                            REAL<br>PACKAGING
                        </th>
                        <th class="text-center align-middle">
                            ACT
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center align-middle">
                            1
                        </td>
                        <td class="text-center align-middle">
                            VERNIZ
                        </td>
                        <td class="text-center align-middle">
                            STONE CARE
                        </td>
                        <td class="text-center align-middle">
                            VZ/SBC/01
                        </td>
                        <td class="text-center align-middle">
                            10-10-2024
                        </td>
                        <td class="text-center align-middle">
                            10-3-2025
                        </td>
                        <td class="text-center align-middle">
                            3200
                        </td>
                        <td class="text-center align-middle">
                            32
                        </td>
                        <td class="text-center align-middle">
                            <!-- Modal trigger button -->
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#modalId">
                                Tambah
                            </button>

                            <!-- Modal Body -->
                            <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
                            <div class="modal fade" id="modalId" tabindex="-1" data-bs-backdrop="static"
                                data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm"
                                    role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalTitleId">

                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form action="{{route('billing.stok-bahan-jadi.produksi-ke')}}" method="get"></form>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="jumlah_produksi" class="form-label">Masukan Jumlah
                                                    Produksi</label>
                                                <input type="number" class="form-control" name="jumlah_produksi"
                                                    id="jumlah_produksi" aria-describedby="helpId" placeholder="" />

                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Tutup
                                            </button>
                                            <button type="button" class="btn btn-primary">Lanjutkan</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center align-middle">
                            32
                        </td>
                        <td class="text-center align-middle">
                            32
                        </td>
                        <td class="text-center align-middle">
                            <button class="btn btn-sm btn-warning my-2">Edit</button>
                            <button class="btn btn-sm btn-primary my-2">OK</button>
                        </td>
                    </tr>
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
