@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center mb-5">
        <div class="col-md-12 text-center">
            <h1><u>Penambahan Deposit</u></h1>
        </div>
    </div>
    @include('wa-status')
    <form action="{{route('form-deposit.masuk.store')}}" method="post" id="masukForm">
        @csrf
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="nominal" class="form-label">Kode</label>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">D</span>
                    <input type="text" class="form-control" value="{{$kode}}">
                  </div>
            </div>
            <div class="col-md-4 mb-3">
                <label for="uraian" class="form-label">Tanggal</label>
                <input type="text" class="form-control @if ($errors->has('uraian'))
                    is-invalid
                @endif" name="uraian" id="uraian" required value="{{date('d M Y')}}" disabled>
            </div>
            <div class="col-md-4 mb-3">
                <label for="uraian" class="form-label">Uraian</label>
                <input type="text" class="form-control @if ($errors->has('uraian'))
                    is-invalid
                @endif" name="uraian" id="uraian" required value="Deposit" disabled>
            </div>
            <div class="col-md-4 mb-3">
                <div class="mb-3">
                    <label for="investor_modal_id" class="form-label">Investor Modal</label>
                    <select class="form-select" name="investor_modal_id" id="investor_modal_id" required>
                        <option value="">-- Pilih Investor --</option>
                        @foreach ($investor as $s)
                        <option value="{{$s->id}}" {{session('investor_modal_id') == $s->id ? 'selected' : ''}}>{{$s->nama}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <label for="nominal" class="form-label">Nominal</label>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">Rp</span>
                    <input type="text" class="form-control @if ($errors->has('nominal'))
                    is-invalid
                @endif" name="nominal" id="nominal" required data-thousands="." >
                  </div>
                @if ($errors->has('nominal'))
                <div class="invalid-feedback">
                    {{$errors->first('nominal')}}
                </div>
                @endif
            </div>
        </div>
        <hr>
        <h3>Transfer Ke</h3>
        <br>
        <div class="row">

            <div class="col-md-4 mb-3">
                <label for="transfer_ke" class="form-label">Nama</label>
                <input type="text" class="form-control @if ($errors->has('transfer_ke'))
                    is-invalid
                @endif" name="transfer_ke" id="transfer_ke" disabled value="{{$rekening->nama_rek}}">
                @if ($errors->has('transfer_ke'))
                <div class="invalid-feedback">
                    {{$errors->first('transfer_ke')}}
                </div>
                @endif
            </div>
            <div class="col-md-4 mb-3">
                <label for="bank" class="form-label">Bank</label>
                <input type="text" class="form-control @if ($errors->has('bank'))
                    is-invalid
                @endif" name="bank" id="bank" disabled value="{{$rekening->bank}}">
                @if ($errors->has('bank'))
                <div class="invalid-feedback">
                    {{$errors->first('bank')}}
                </div>
                @endif
            </div>
            <div class="col-md-4 mb-3">
                <label for="no_rekening" class="form-label">Nomor Rekening</label>
                <input type="text" class="form-control @if ($errors->has('no_rekening'))
                    is-invalid
                @endif" name="no_rekening" id="no_rekening" disabled value="{{$rekening->no_rek}}">
                @if ($errors->has('no_rekening'))
                <div class="invalid-feedback">
                    {{$errors->first('no_rekening')}}
                </div>
                @endif
            </div>
            <input type="hidden" name="no_rekening" value="12351293851203">
        </div>

        <div class="d-grid gap-3 mt-3">
            <button class="btn btn-success" type="submit">Simpan</button>
            <a href="{{route('billing')}}" class="btn btn-secondary" type="button">Batal</a>
          </div>
    </form>
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

        $('#investor_modal_id').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Pilih Investor Modal'
        });

         var nominal = new Cleave('#nominal', {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand',
            numeralDecimalMark: ',',
            delimiter: '.'
        });
        // masukForm on submit, sweetalert confirm
        $('#masukForm').submit(function(e){
            e.preventDefault();
            Swal.fire({
                title: 'Apakah data sudah benar?',
                text: "Pastikan data sudah benar sebelum disimpan!",
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
    </script>
@endpush
