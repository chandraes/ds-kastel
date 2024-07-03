@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 text-center">
            <h1><u>FORM PURCHASE ORDER</u></h1>
        </div>
    </div>
    @include('swal')
</div>
<div class="container mt-5">
    <form action="{{route('po.form.store')}}" method="post" id="masukForm">
        @csrf
        <div class="row">
            <div class="col-md-12 mb-3 text-end">
                <button type="button" class="btn btn-success" id="addRowButton">Tambah Baris</button>
            </div>

            <div id="bahanContainer">
                <table class="table table-bordered table-hover" id="tableBahan">
                    <thead class="table-success">
                        <tr>
                            <th class="text-center align-middle">No</th>
                            <th class="text-center align-middle">KATEGORI</th>
                            <th class="text-center align-middle">NAMA BARANG</th>
                            <th class="text-center align-middle">
                                Qty<br>
                                <small class="text-danger">Gunakan "." untuk nilai desimal!!</small>
                            </th>
                            <th class="text-center align-middle">
                                HARGA SATUAN<br>
                                <small class="text-danger">Gunakan "." untuk nilai desimal!!</small>
                            </th>
                            <th class="text-center align-middle">TOTAL</th>
                            <th class="text-center align-middle">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="bahanTable">
                        <!-- Rows will be added here dynamically -->
                    </tbody>
                    <tfoot class="table-secondary">
                        <tr>
                            <td colspan="3" class="text-end align-middle">Grand Total:</td>
                            <td class="text-center align-middle" id="grandTotalQty">0</td>
                            <td></td>
                            <td class="text-center align-middle" id="grandTotal">0</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="col-md-12 mt-2">
                <button type="submit" class="btn btn-primary d-block w-100" id="saveButton" hidden>Simpan</button>
            </div>
            <div class="col-md-12 mt-2">
                <a href="{{ route('po') }}" class="btn btn-secondary d-block w-100">Kembali</a>
            </div>
        </div>
    </form>
</div>
@endsection

@push('js')
<!-- DataTables JS -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const addRowButton = document.getElementById('addRowButton');
    const bahanTable = document.getElementById('bahanTable');
    const saveButton = document.getElementById('saveButton');
    let bahanCounter = 0;

    const dataTable = $('#tableBahan').DataTable({
        paging: false,
        info: false,
        searching: false,
        scrollY: '450px',
        scrollCollapse: true
    });

    addRowButton.addEventListener('click', function () {
        bahanCounter++;

        const row = document.createElement('tr');
        row.classList.add('bahan-row');
        row.innerHTML = `
            <td class="text-center align-middle">${bahanCounter}</td>
            <td><input type="text" name="kategori[]" class="form-control kategori" required></td>
            <td><input type="text" name="nama_barang[]" class="form-control nama_barang" required></td>
            <td><input type="text" name="jumlah[]" class="form-control persentase" required></td>
            <td><input type="text" name="harga_satuan[]" class="form-control harga" required></td>
            <td><input type="text" name="total[]" class="form-control total" readonly></td>
            <td class="text-center align-middle"><button type="button" class="btn btn-danger remove-bahan w-100">Hapus</button></td>
        `;
        dataTable.row.add(row).draw();

        new Cleave(row.querySelector('.persentase'), {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand',
            numeralDecimalMark: ',',
            delimiter: '.'
        });

        new Cleave(row.querySelector('.harga'), {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand',
            numeralDecimalMark: ',',
            delimiter: '.'
        });

        const jumlahInput = row.querySelector('.persentase');
        const hargaSatuanInput = row.querySelector('.harga');
        const totalInput = row.querySelector('.total');

        const updateTotals = () => {
            const jumlahRaw = jumlahInput.value.replace(/\./g, '').replace(',', '.');
            const jumlah = parseFloat(jumlahRaw) || 0;
            const hargaSatuanRaw = hargaSatuanInput.value.replace(/\./g, '').replace(',', '.');
            const hargaSatuan = parseFloat(hargaSatuanRaw) || 0;
            const total = jumlah * hargaSatuan;

            totalInput.value = total.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

            let grandTotalQty = 0;
            let grandTotal = 0;

            document.querySelectorAll('.persentase').forEach(input => {
                const value = parseFloat(input.value.replace(/\./g, '').replace(',', '.')) || 0;
                grandTotalQty += value;
            });

            document.querySelectorAll('.total').forEach(input => {
                const value = parseFloat(input.value.replace(/\./g, '').replace(',', '.')) || 0;
                grandTotal += value;
            });

            document.getElementById('grandTotalQty').textContent = grandTotalQty.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('grandTotal').textContent = grandTotal.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        };

        jumlahInput.addEventListener('input', updateTotals);
        hargaSatuanInput.addEventListener('input', updateTotals);

        row.querySelector('.remove-bahan').addEventListener('click', function () {
            dataTable.row($(this).closest('tr')).remove().draw();
            updateTotals();

            let rowIndex = 1;
            document.querySelectorAll('.bahan-row').forEach((row) => {
                row.cells[0].textContent = rowIndex++;
            });

            bahanCounter--;
            if (bahanCounter === 0) {
                saveButton.hidden = true;
            }
        });

        saveButton.hidden = false;
    });
});
</script>
@endpush
