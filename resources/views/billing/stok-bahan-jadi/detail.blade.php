@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center mb-5">
        <div class="col-md-12 text-center">
            <h1><u>STONE CARE</u></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered table-sm" id="barangJadi" style="font-size: 11px">
                <thead class="table-primary">
                    <tr>
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
                            PRODUKSI KE
                        </th>
                    </tr>
                </thead>
                <tbody>

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
