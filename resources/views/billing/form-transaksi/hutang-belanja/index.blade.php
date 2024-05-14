@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 text-center">
            <h1><u>HUTANG BELANJA</u></h1>
        </div>
    </div>
    <div class="row justify-content-between mt-3">
        <div class="col-md-6">
            <table class="table">
                <tr class="text-center">
                    <td><a href="{{route('home')}}"><img src="{{asset('images/dashboard.svg')}}" alt="dashboard"
                                width="30"> Dashboard</a></td>
                    <td><a href="{{route('billing.form-transaksi')}}"><img src="{{asset('images/transaksi.svg')}}" alt="dokumen" width="30">
                            Form Transaksi</a></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<div class="container table-responsive ml-3">
    <div class="row mt-3">
        <table class="table table-hover table-bordered" id="rekapTable">
            <thead class=" table-success">
                <tr>
                    <th class="text-center align-middle">Tanggal</th>
                    <th class="text-center align-middle">Supplier</th>
                    <th class="text-center align-middle">Nota</th>
                    <th class="text-center align-middle">Uraian</th>
                    <th class="text-center align-middle">Nilai<br>DPP</th>
                    <th class="text-center align-middle">Diskon</th>
                    <th class="text-center align-middle">PPn</th>
                    <th class="text-center align-middle">Total<br>Belanja</th>
                    <th class="text-center align-middle">DP</th>
                    <th class="text-center align-middle">Sisa<br>Tagihan</th>
                    <th class="text-center align-middle">Jatuh<br>Tempo</th>
                    <th class="text-center align-middle">ACT</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($data as $d)
                <tr>
                    <td class="text-center align-middle">{{$d->tanggal}}</td>
                    <td class="text-center align-middle">{{$d->supplier->nama}}</td>
                    <td class="text-center align-middle">
                        <a href="{{route('rekap.invoice-belanja.detail', ['invoice' => $d])}}">
                            {{$d->kode}}
                        </a>
                    </td>
                    <td class="text-start align-middle">{{$d->uraian}}</td>
                    <td class="text-end align-middle">
                        {{$d->dpp}}
                    </td>
                    <td class="text-end align-middle">
                        {{$d->nf_diskon}}
                    </td>
                    <td class="text-end align-middle">
                        {{$d->nf_ppn}}
                    </td>
                    <td class="text-end align-middle">
                        {{$d->nf_total}}
                    </td>
                    <td class="text-end align-middle">
                        {{$d->nf_dp}}
                    </td>
                    <td class="text-end align-middle">
                        {{$d->nf_sisa}}
                    </td>
                    <td class="text-center align-middle">
                        {{$d->id_jatuh_tempo}}
                    </td>
                    <td class="text-center align-middle">
                        <form action="{{route('billing.form-transaksi.bahan-baku.hutang-belanja.bayar', ['invoice' => $d])}}" method="post" id="bayarForm{{ $d->id }}"
                            class="bayar-form" data-id="{{ $d->id }}" data-nominal="{{$d->nf_sisa}}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">Bayar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
            {{-- <tfoot>
                <tr>
                    <td colspan="3" class="text-center align-middle"><strong>GRAND TOTAL</strong></td>
                    <td class="text-end align-middle"><strong>{{number_format($data->where('jenis',
                            1)->sum('nominal'), 0, ',', '.')}}</strong></td>
                    <td class="text-end align-middle text-danger"><strong>{{number_format($data->where('jenis',
                            0)->sum('nominal'), 0, ',', '.')}}</strong></td>
                    <td class="text-end align-middle">
                        <strong>
                            {{$data->last() ? number_format($data->last()->saldo, 0, ',', '.') : ''}}
                        </strong>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tfoot> --}}
        </table>
    </div>
</div>
@endsection
@push('css')
<link href="{{asset('assets/css/dt.min.css')}}" rel="stylesheet">
@endpush
@push('js')
<script src="{{asset('assets/js/dt5.min.js')}}"></script>
<script>

    $(document).ready(function() {
        $('#rekapTable').DataTable({
            "paging": false,
            "ordering": false,
            "searching": false,
            "scrollCollapse": true,
            "scrollY": "550px",

        });

        $('.bayar-form').submit(function(e){
            e.preventDefault();
            var formId = $(this).data('id');
            var nominal = $(this).data('nominal');
            Swal.fire({
                title: 'Apakah Anda Yakin? Sisa Tagihan Sebesar: Rp. ' + nominal,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, simpan!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $(`#bayarForm${formId}`).unbind('submit').submit();
                    $('#spinner').show();
                }
            });
        });

    });


</script>
@endpush
