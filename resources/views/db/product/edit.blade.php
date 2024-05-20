@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 text-center">
            <h1><u>TAMBAH PRODUCT</u></h1>
        </div>
    </div>
    @include('swal')
</div>
<div class="container mt-5">
    <form action="{{route('db.product.store')}}" method="post" id="masukForm">
        @csrf
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="kategori_product_id" class="form-label">Kategori Barang</label>
                <select class="form-select" name="kategori_product_id" id="kategori_product_id" required>
                    <option value="">-- Pilih Kategori Barang --</option>
                    @foreach ($kategori as $i)
                    <option value="{{$i->id}}">{{$i->nama}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label for="nama" class="form-label">Nama Product</label>
                <input type="text" class="form-control" name="nama" id="nama" aria-describedby="helpId" required value="{{old('nama')}}"/>
            </div>
            <div class="col-md-4 mb-3">
                <label for="kode" class="form-label">Kode Product</label>
                <input type="text" class="form-control" name="kode" id="kode" aria-describedby="helpId" required value="{{old('kode')}}"/>
            </div>
            <div class="col-md-4 mb-3" id="divKonversi">
                <label for="konversi" class="form-label">Konversi KG -> Liter</label>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">1</span>
                    <span class="input-group-text" id="basic-addon1">:</span>
                    <input type="text" class="form-control" name="konversi_liter" id="konversi_liter" required value="{{old('konversi_liter')}}">
                </div>
                <small class="text-danger">Gunakan "." untuk nilai desimal!!</small>
            </div>
            <div class="col-md-12 mb-3">
                {{-- submit button --}}
                <button type="submit" class="btn btn-primary form-control">Simpan</button>
                {{-- reset button --}}
            </div>
            <div class="col-md-12 mb-3">
                <a href="{{route('db.product')}}" class="btn btn-secondary form-control">Kembali</a>
            </div>
        </div>
    </form>
</div>
@endsection
@push('js')
<script>
    confirmAndSubmit('#masukForm', 'Apakah anda Yakin?');
</script>
@endpush
