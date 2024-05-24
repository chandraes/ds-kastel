@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center mb-5">
        <div class="col-md-12 text-center">
            <h1><u>Produksi</u></h1>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-4">
            <div class="mb-3">
                <label for="product_id" class="form-label">Product</label>
                <select class="form-select" name="product_id" id="product_id" required onchange="kemasan()">
                    <option value="" disabled selected>-- Pilih Product --</option>
                    @foreach ($data as $d)
                    <option value="{{$d->id}}">{{$d->kategori->nama}} - {{$d->nama}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="mb-3">
                <label for="kemasan_id" class="form-label">Kemasan</label>
                <select class="form-select" name="kemasan_id" id="kemasan_id" required onchange="checkInput()">
                    <option value="" disabled selected>-- Pilih Kemasan --</option>
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="mb-3">
                <label for="rencana_produksi" class="form-label">Rencana Produksi</label>
                <input type="text" name="rencana_produksi" id="rencana_produksi" class="form-control" required oninput="checkInput()">
            </div>
        </div>
        <div class="col-md-2">
            <div class="mb-3">
                <label for="rencana_produksi" class="form-label">&nbsp;</label>
                <button type="button" id="tampilkanButton" class="btn btn-rounded btn-success form-control" onclick="komposisi()" disabled>Tampilkan</button>
            </div>
        </div>
    </div>
    <div class="row mt-3" id="komposisiDiv" hidden>
        <div class="col-md-12">
            <table class="table table-bordered">
                <thead class="table-primary">
                    <tr>
                        <th class="text-center align middle">No</th>
                        <th class="text-center align middle">Bahan Baku</th>
                        <th class="text-center align middle">Qty</th>
                        <th class="text-center align middle">Satuan</th>
                        <th class="text-center align middle">Qty Per Unit</th>
                        <th class="text-center align middle">Satuan Per Unit</th>
                    </tr>
                </thead>
                <tbody id="komposisiBody">
                    <tr>
                        <td colspan="6" class="text-center">Data Kosong</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@push('css')
<link rel="stylesheet" href="{{asset('assets/plugins/select2/select2.bootstrap5.css')}}">
<link rel="stylesheet" href="{{asset('assets/plugins/select2/select2.min.css')}}">
@endpush
@push('js')
<script src="{{asset('assets/plugins/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/cleave.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $('#product_id').select2({
            theme: 'bootstrap5'
        });
        $('#kemasan_id').select2();
    });
    function checkInput() {
        const rencanaProduksi = document.getElementById('rencana_produksi').value;
        const tampilkanButton = document.getElementById('tampilkanButton');
        const kemasanId = document.getElementById('kemasan_id').value;

        if (rencanaProduksi === '' || kemasanId === '') {
            tampilkanButton.disabled = true;
        } else {
            tampilkanButton.disabled = false;
        }
    }
    function kemasan() {
        var product_id = $('#product_id').val();
        $.ajax({
            url: "{{route('billing.produksi.get-kemasan')}}",
            type: "GET",
            data: {
                product_id: product_id
            },
            success: function (data) {
                if(data.status == '1') {
                    $('#kemasan_id').empty();
                    $('#kemasan_id').append('<option value="" disabled selected>-- Pilih Kemasan --</option>');
                    $.each(data.data, function (key, value) {
                        $('#kemasan_id').append('<option value="'+value.id+'">'+value.nama+'</option>');
                    });
                } else {
                    $('#product_id').val('');
                    $('#kemasan_id').val('');
                    $('#rencana_produksi').val('');
                    Swal.fire({
                        title: data.message ,
                        icon: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#spinner').show();
                            window.location.reload();
                        }
                    })
                }
            }
        });
    }

    function komposisi()
    {
        var product_id = $('#product_id').val();
        var kemasan_id = $('#kemasan_id').val();

        $.ajax({
            url: "{{route('billing.produksi.get-komposisi')}}",
            type: "GET",
            data: {
                product_id: product_id,
                kemasan_id: kemasan_id
            },
            success: function (data) {
                if(data.status == '1') {
                    console.log(data.data);
                    displayData(data.data);
                } else {
                    $('#product_id').val('');
                    Swal.fire({
                        title: data.message ,
                        icon: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#spinner').show();
                            window.location.reload();
                        }
                    })
                }
            }
        });
    }

    function displayData(data)
    {
        console.log('masuk sini');
    }

</script>
@endpush
