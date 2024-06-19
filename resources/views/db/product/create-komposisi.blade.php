@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 text-center">
            <h1><u>TAMBAH KOMPOSISI PRODUCT</u></h1>
            <h1><u>{{$product->kategori->nama}} - {{$product->nama}}</u></h1>
        </div>
    </div>
    @include('swal')
</div>
<div class="container mt-5">
    <form action="{{route('db.product.store-komposisi')}}" method="post" id="masukForm">
        @csrf
        <input type="hidden" name="product_id" value="{{$product->id}}">
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
                            <th class="text-center align-middle">NAMA KIMIA</th>
                            <th class="text-center align-middle">SINGKATAN</th>
                            <th class="text-center align-middle">
                                Persentase<br>
                                <small class="text-danger">Gunakan "." untuk nilai desimal!!</small>
                            </th>
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
                <a href="{{route('db.product')}}" class="btn btn-secondary d-block w-100">Kembali</a>
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

        // Create a row for the grand total
        const totalRow = document.createElement('tr');
        totalRow.innerHTML = `
            <td  class="text-end" colspan="2">Persentase Total:</td>
            <td id="grandTotal" class="text-center">0%</td>
            <td></td>
        `;
        bahanTable.appendChild(totalRow);

        bahanSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const bahanId = selectedOption.value;
            const bahanNama = selectedOption.getAttribute('data-nama');
            const bahanSingkatan = selectedOption.getAttribute('data-singkatan');

            if (bahanId && !selectedBahan.has(bahanId)) {
                selectedBahan.add(bahanId);

                const row = document.createElement('tr');
                row.classList.add('bahan-row');
                row.innerHTML = `
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
                    <td class="text-center align-middle">
                        <button type="button" class="btn btn-danger remove-bahan w-100" data-id="${bahanId}">Hapus</button>
                    </td>
                `;
                bahanTable.insertBefore(row, totalRow);

                // Update the grand total
                const persentaseInput = row.querySelector('.persentase');
                persentaseInput.addEventListener('input', function () {
                    grandTotal -= this.dataset.lastValue || 0;
                    grandTotal += parseFloat(this.value) || 0;
                    this.dataset.lastValue = this.value;
                    document.getElementById('grandTotal').textContent = grandTotal.toFixed(2);
                });

                // Remove selected option from dropdown
                selectedOption.style.display = 'none';

                // Show the save button
                document.getElementById('saveButton').hidden = false;

                row.querySelector('.remove-bahan').addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    selectedBahan.delete(id);
                    row.remove();

                    // Subtract the value of the removed item from the grand total
                    grandTotal -= parseFloat(persentaseInput.value) || 0;
                    document.getElementById('grandTotal').textContent = grandTotal.toFixed(2);

                    // Show the removed option back in the dropdown
                    const optionToShow = bahanSelect.querySelector(`option[value="${id}"]`);
                    if (optionToShow) {
                        optionToShow.style.display = 'block';
                    }

                    // Hide the save button if there are no bahanIds left
                    if (selectedBahan.size === 0) {
                        document.getElementById('saveButton').hidden = true;
                    }
                });
            }

            // Reset the select box
            this.value = '';
        });
    });
</script>
@endpush
