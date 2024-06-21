@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center mb-5">
        <div class="col-md-12 text-center">
            <h1><u>Form Inventaris</u></h1>
        </div>
    </div>
    @include('swal')
    <form action="#" method="post" id="masukForm">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="pembayaran" class="form-label">Kategori</label>
                    <select class="form-select" id="pembayaran" name="pembayaran" required onchange="checkPembayaran()">
                        <option value=""> -- Pilih Pembayaran -- </option>
                        <option value="1">Cash</option>
                        <option value="2">Tempo</option>
                        <option value="3">Kredit</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row" id="row-first" >
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="kategori_id" class="form-label">Kategori</label>
                    <select class="form-select" id="kategori_id" name="kategori_id" required onchange="getJenis()">
                        <option value=""> -- Pilih Kategori -- </option>
                        @foreach ($data as $k)
                        <option value="{{$k->id}}">{{$k->nama}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="inventaris_jenis_id" class="form-label">Kategori</label>
                    <select class="form-select" id="inventaris_jenis_id" name="inventaris_jenis_id" required>
                        <option value=""> -- Pilih Jenis -- </option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="uraian" class="form-label">Apakah menggunakan PPn?</label>
                    <select class="form-select" name="apa_ppn" id="apa_ppn" onchange="add_ppn()" required>
                        <option value="">-- Pilih Salah Satu --</option>
                        <option value="1">Dengan PPn</option>
                        <option value="0">Tanpa PPn</option>
                    </select>
                </div>
            </div>

        </div>
        <div class="row" id="row-second">
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="jumlah" class="form-label">Jumlah</label>
                    <div class="input-group mb-3">
                        <input type="number" class="form-control" name="jumlah" id="jumlah" aria-describedby="helpId"
                            placeholder="" required value="0" onkeyup="calculateTotal()">
                        <span class="input-group-text" id="basic-addon1">Buah</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <label for="harga" class="form-label">Harga Satuan</label>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">Rp</span>
                    <input type="text" class="form-control @if ($errors->has('harga'))
                    is-invalid
                @endif" name="harga" id="harga" data-thousands="." required value="0" onkeyup="calculateTotal()">
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <label for="ppn" class="form-label">PPN</label>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">Rp</span>
                    <input type="text" class="form-control @if ($errors->has('ppn'))
                    is-invalid
                @endif" name="ppn" id="ppn" required readonly value="0">
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <label for="total" class="form-label">Total Harga</label>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">Rp</span>
                    <input type="text" class="form-control @if ($errors->has('total'))
                    is-invalid
                @endif" name="total" id="total" required readonly value="0">
                </div>
            </div>
        </div>
        <hr>

        <div class="d-grid gap-3 mt-3">
            <button class="btn btn-primary">Beli</button>
            <a href="{{route('billing.form-transaksi')}}" class="btn btn-secondary" type="button">Batal</a>
        </div>
    </form>
</div>
@endsection
@push('js')
<script>
        function checkPembayaran(){
            console.log('checkPembayaran');
            var pembayaran = document.getElementById('pembayaran').value;
            if (pembayaran != "") {
                document.getElementById('row-first').hidden = false;
            } else {
                document.getElementById('row-first').hidden = true;
            }
        }

        function getJenis(){
            console.log('getJenis');
            var kategori_id = document.getElementById('kategori_id').value;
            $.ajax({
                url: "{{route('billing.form-inventaris.get-jenis')}}",
                type: "GET",
                data: {
                    kategori_id: kategori_id
                },
                success: function(data){
                    console.log(data);
                    if(data.status === 0 ){
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Data Jenis Inventaris Kosong!',
                        });
                        document.getElementById('kategori_id').value = '';
                    }
                    var html = '';
                    html += '<option value=""> -- Pilih Jenis -- </option>';
                    data.data.forEach(function(item){
                        html += '<option value="'+item.id+'">'+item.nama+'</option>';
                    });
                    document.getElementById('inventaris_jenis_id').innerHTML = html;
                }
            });
        }

        function calculateTotal(){
            var harga = document.getElementById('harga').value;
            harga = harga.replace(/\./g, '');
            var jumlah = document.getElementById('jumlah').value;
            var apa_ppn = document.getElementById('apa_ppn').value;
            if (apa_ppn === "1") {
                var ppn = {!! $ppn !!} / 100;
                var tot = (harga * jumlah);
                document.getElementById('ppn').value = ((tot * ppn)).toLocaleString('id-ID');
            } else {
                document.getElementById('ppn').value = 0;
                var tot = (harga * jumlah);
            }
            var ppn = document.getElementById('ppn').value;
            ppn = ppn.replace(/\./g, '');

            var total = (harga * jumlah) + Number(ppn);
            total = total.toLocaleString('id-ID');
            document.getElementById('total').value = total;
        }

    function add_ppn(){
        var apa_ppn = document.getElementById('apa_ppn').value;
        var ppn = {!! $ppn !!} / 100;

        if (apa_ppn === "1") { // compare with string "1"
            var gt = Number(document.getElementById('tdTotalSetelahDiskon').textContent.replace(/\./g, ''));

            var vPpn = gt * ppn;
            var totalap = gt + (gt *ppn);

            var tF = totalap.toLocaleString('id-ID');
            var vF = vPpn.toLocaleString('id-ID');
            document.getElementById('grand_total').textContent = tF;
            document.getElementById('tdPpn').textContent = vF;
        } else {
            document.getElementById('tdPpn').textContent = 0;
            add_diskon();
        }
    }

        $(function() {
            var nominal = new Cleave('#harga', {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand',
                numeralDecimalMark: ',',
                delimiter: '.'
            });
            var diskoTn = new Cleave('#diskon', {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand',
                numeralDecimalMark: ',',
                delimiter: '.'
            });

            var add_fee = new Cleave('#add_fee', {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand',
                numeralDecimalMark: ',',
                delimiter: '.'
            });
        });

        confirmAndSubmit('#kosongKeranjang', 'Apakah anda Yakin?');
        confirmAndSubmit('#beliBarang', 'Apakah anda Yakin?');

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
