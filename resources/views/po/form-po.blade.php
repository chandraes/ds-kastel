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
            <div class="col-md-12 mb-3">
                <label for="bahanSelect" class="form-label">Pilih Bahan</label>
                <select id="bahanSelect" class="form-select">
                    <option value="">Tambahkan Bahan</option>
                    @foreach($bahan as $bahan)
                        <option value="{{ $bahan->id }}" data-nama="{{$bahan->kategori->nama}}" data-singkatan="{{ $bahan->nama }}">{{$bahan->kategori->nama}} - {{ $bahan->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div id="bahanContainer">
                <table class="table table-bordered table-hover" id="tableBahan">
                    <thead class="table-success">
                        <tr>
                            <th class="text-center align-middle">No</th>
                            <th class="text-center align-middle">KATEGORI</th>
                            <th class="text-center align-middle">JENIS</th>
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

                    </tbody>
                </table>
            </div>
            <div id="saveButton" class="col-md-12 mt-2" hidden>
                <button type="submit" class="btn btn-primary d-block w-100">Simpan</button>
            </div>
            <div class="col-md-12 mt-2">
                <a href="{{route('po')}}" class="btn btn-secondary d-block w-100">Kembali</a>
            </div>
        </div>
    </form>
</div>
@endsection
@push('js')
<script>
    confirmAndSubmit('#masukForm', 'Pastikan data sudah benar dan jumlah persentase sudah 100%?');

    document.addEventListener('DOMContentLoaded', function () {
        const bahanSelect = document.getElementById('bahanSelect');
        const bahanTable = document.getElementById('bahanTable');
        const selectedBahan = new Set();
        let grandTotal = 0;
        let totalHargaSatuan = 0;

        // Create a row for the grand total
        const totalRow = document.createElement('tr');
        totalRow.innerHTML = `
            <td  class="text-end" colspan="5">Grand Total:</td>
            <td id="grandTotal" class="text-center">0</td>
            <td></td>
        `;
        bahanTable.appendChild(totalRow);

        let bahanCounter = 0;

        bahanSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const bahanId = selectedOption.value;
            const bahanNama = selectedOption.getAttribute('data-nama');
            const bahanSingkatan = selectedOption.getAttribute('data-singkatan');

            if (bahanId && !selectedBahan.has(bahanId)) {
                selectedBahan.add(bahanId);
                bahanCounter++; // Increment the counter

                const row = document.createElement('tr');
                row.classList.add('bahan-row');
                row.innerHTML = `
                    <td>${bahanCounter}</td>
                    <td>
                        <input type="hidden" name="bahan_baku_id[]" value="${bahanId}">
                        <label class="form-control">${bahanNama}</label>
                    </td>
                    <td>
                        <input type="hidden" name="singkatan[]" value="${bahanSingkatan}">
                        <label class="form-control">${bahanSingkatan}</label>
                    </td>
                    <td>
                        <input type="text" name="jumlah[]" id="persentase_${bahanId}" class="form-control persentase" required value="0">
                    </td>
                    <td>
                        <input type="text" name="harga_satuan[]" id="harga_satuan_${bahanId}" class="form-control harga" required value="0">
                    </td>
                    <td>
                        <input type="text" name="total[]" id="total_${bahanId}" class="form-control total" required value="0" readonly>
                    </td>
                    <td class="text-center align-middle">
                        <button type="button" class="btn btn-danger remove-bahan w-100" data-id="${bahanId}">Hapus</button>
                    </td>
                `;
                bahanTable.insertBefore(row, totalRow);

                new Cleave(`#persentase_${bahanId}`, {
                    numeral: true,
                    numeralThousandsGroupStyle: 'thousand',
                    numeralDecimalMark: ',',
                    delimiter: '.'
                });

                new Cleave(`#harga_satuan_${bahanId}`, {
                    numeral: true,
                    numeralThousandsGroupStyle: 'thousand',
                    numeralDecimalMark: ',',
                    delimiter: '.'
                });

                // Calculate and update total when jumlah or harga_satuan changes
                const jumlahInput = row.querySelector(`#persentase_${bahanId}`);
                const hargaSatuanInput = row.querySelector(`#harga_satuan_${bahanId}`);
                const totalInput = row.querySelector(`#total_${bahanId}`);

                const updateTotals = () => {
                    // Corrected function name to match event listener
                    const jumlahRaw = jumlahInput.value.replace(/\./g, '').replace(',', '.');
                    const jumlah = parseFloat(jumlahRaw) || 0;
                    const hargaSatuanRaw = hargaSatuanInput.value.replace(/\./g, '').replace(',', '.');
                    const hargaSatuan = parseFloat(hargaSatuanRaw) || 0;
                    const total = jumlah * hargaSatuan;

                    totalInput.value = total.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                    // Recalculate totals from all rows
                    let newTotalJumlah = 0;
                    let newTotalHargaSatuan = 0;
                    let newGrandTotal = 0;

                    document.querySelectorAll('.total').forEach(input => {
                        const value = parseFloat(input.value.replace(/\./g, '').replace(',', '.')) || 0;
                        newGrandTotal += value;
                    });

                  
                    // Update totalHargaSatuan if you have an element for it
                    document.getElementById('grandTotal').textContent = newGrandTotal.toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                };

                jumlahInput.addEventListener('input', updateTotals);
                hargaSatuanInput.addEventListener('input', updateTotals);

                row.querySelector('.remove-bahan').addEventListener('click', function () {
                    row.remove();
                    selectedBahan.delete(bahanId);

                    // Update the No column for all rows after removal
                    let rowIndex = 1;
                    document.querySelectorAll('.bahan-row').forEach((row) => {
                        row.cells[0].textContent = rowIndex++;
                    });

                    bahanCounter--; // Decrement the counter
                    updateTotals();
                });
            }

            // Reset the select box
            this.value = '';
        });
    });
</script>
@endpush
