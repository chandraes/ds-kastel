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
                        <option value="{{ $bahan->id }}" data-nama="{{$bahan->kategori->nama}} - {{ $bahan->nama }}">{{$bahan->kategori->nama}} - {{ $bahan->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div id="bahanContainer"></div>
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
    const bahanContainer = document.getElementById('bahanContainer');
    const selectedBahan = new Set();

    bahanSelect.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const bahanId = selectedOption.value;
        const bahanNama = selectedOption.getAttribute('data-nama');

        if (bahanId && !selectedBahan.has(bahanId)) {
            selectedBahan.add(bahanId);

            const row = document.createElement('div');
            row.classList.add('mb-3', 'bahan-row');
            row.innerHTML = `
                <div class="row">
                    <input type="hidden" name="bahan_baku_id[]" value="${bahanId}">
                    <div class="col-md-6">
                        <label class="form-label">&nbsp;</label>
                        <label class="form-control">${bahanNama}</label>
                    </div>
                    <div class="col-md-4">
                        <label for="persentase_${bahanId}" class="form-label">Persentase Jumlah</label>
                        <input type="number" name="jumlah[]" id="persentase_${bahanId}" class="form-control" required>
                        <small class="text-danger">Gunakan "." untuk nilai desimal!!</small>
                    </div>
                    <div class="col-md-2 d-flex align-items-center">
                        <button type="button" class="btn btn-danger remove-bahan w-100" data-id="${bahanId}">Hapus</button>
                    </div>
                </div>
            `;
            bahanContainer.appendChild(row);

            // Remove selected option from dropdown
            selectedOption.style.display = 'none';

            // Show the save button
            document.getElementById('saveButton').hidden = false;

            row.querySelector('.remove-bahan').addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                selectedBahan.delete(id);
                row.remove();

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
