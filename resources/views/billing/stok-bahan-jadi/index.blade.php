@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center mb-5">
        <div class="col-md-12 text-center">
            <h1><u>STOK BAHAN JADI</u></h1>
        </div>
    </div>
    <div class="row mb-3 d-flex">

        <div class="col-md-12">
            <a href="{{route('billing')}}" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Kembali</a>
        </div>
    </div>

    @include('billing.stok-bahan-jadi.keranjang')
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered table-sm" id="barangJadi">
                <thead class="">
                    <tr>
                        <th class="text-center align-middle table-primary">
                            NO
                        </th>
                        <th class="text-center align-middle table-primary">
                            KATEGORI<br>PRODUCT
                        </th>
                        <th class="text-center align-middle table-primary">
                            JENIS<br>PRODUCT
                        </th>
                        <th class="text-center align-middle table-primary">
                            STOK<br>KEMASAN
                        </th>
                        <th class="text-center align-middle table-primary">
                            SATUAN<br>KEMASAN
                        </th>
                        <th class="text-center align-middle table-danger">
                            STOK<br>PACKAGING
                        </th>
                        <th class="text-center align-middle table-danger">
                            SATUAN<br>PACKAGING
                        </th>
                        <th class="text-center align-middle table-danger">
                            HARGA JUAL
                        </th>
                        <th class="text-center align-middle table-danger">
                            JUAL<br>(PACKAGING)
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groupedData as $group)
                    @php $i = 0; @endphp
                    @foreach($group as $d)
                    <tr>
                        @if($i++ == 0)
                        <td class="text-center align-middle" rowspan="{{ count($group) }}">{{$loop->iteration}}</td>
                        <td class="text-center align-middle" rowspan="{{ count($group) }}">
                            {{$d->product->kategori->nama}}</td>
                        @endif
                        <td class="text-center align-middle">
                            {{$d->product->nama}}
                        </td>
                        <td class="text-center align-middle">
                            {{$d->stock_kemasan ?? 0}}
                        </td>
                        <td class="text-center align-middle">
                            {{$d->kemasan->satuan->nama}}
                        </td>
                        <td class="text-center align-middle">
                            {{$d->stock_packaging ?? 0}}
                        </td>
                        <td class="text-center align-middle">
                            @if ($d->kemasan->packaging)
                            {{$d->kemasan->packaging->satuan->nama}}
                            @else
                            {{$d->kemasan->satuan->nama}}
                            @endif
                        </td>
                        <td class="text-center align-middle">
                            {{$d->kemasan->nf_harga ?? 0}}
                        </td>
                        <td class="text-center align-middle p-3" style="width: 15%">
                            @if ($keranjang->where('product_jadi_id', $d->id)->first())
                            <div class="input-group">
                                <button class="btn btn-danger" onclick="updateCart({{$d->id}}, -1, {{$d->stock_packaging}})">-</button>
                                <input type="text" class="form-control text-center" value="{{$keranjang->where('product_jadi_id', $d->id)->first()->jumlah}}" readonly>
                                <button class="btn btn-success" onclick="updateCart({{$d->id}}, 1, {{$d->stock_packaging}})">+</button>
                            </div>
                            @else
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#keranjangModal"
                                onclick="setModalJumlah({{$d}}, {{$d->id}})">JUMLAH</button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

        <a class="btn btn-success btn-block px-5 mt-3">Checkout</a>
    </div </div>
</div>
@endsection
@push('js')

<script src="{{asset('assets/js/cleave.min.js')}}"></script>
<script>
    function setModalJumlah(data, id)
        {
            document.getElementById('titleJumlah').innerText = data.product.kategori.nama + ' ' + data.product.nama;
            document.getElementById('product_jadi_id').value = data.id;
        }

        function updateCart(productId, quantity, maxStock) {
            let currentQuantity = parseInt($(`button[onclick="updateCart(${productId}, 1, ${maxStock})"]`).siblings('input').val());

            if (currentQuantity + quantity > maxStock) {
                alert('Jumlah item tidak boleh melebihi stok yang tersedia.');
                return;
            }

            $.ajax({
                url: '{{route('billing.stok-bahan-jadi.keranjang.update')}}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    product_id: productId,
                    quantity: quantity
                },
                success: function(response) {
                    $('#spinner').show();
                    if (response.success) {
                        location.reload(); // Reload the page to reflect the changes
                    } else {
                        alert('Gagal memperbarui keranjang.');
                    }
                }
            });
        }

</script>
@endpush
