<div class="modal fade" id="createInvestor" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    role="dialog" aria-labelledby="investorTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="investorTitle">Tambah Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('db.product-jadi.store')}}" method="post" id="createForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="nama" class="form-label">Product</label>
                            <select class="form-select" name="product_id" id="product_id">
                                <option value="">-- Pilih Product --</option>
                                @foreach ($product as $p)
                                <option value="{{$p->id}}">{{$p->kategori->nama}} - {{$p->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="kemasan_id" class="form-label">Kemasan</label>
                            <select class="form-select" name="kemasan_id" id="kemasan_id">
                                <option value="">-- Pilih Kemasan --</option>
                                @foreach ($kemasan as $k)
                                <option value="{{$k->id}}">{{$k->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="konversi" class="form-label">Konversi KG -> Liter</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">1</span>
                                <span class="input-group-text" id="basic-addon1">:</span>
                                <input type="text" class="form-control" name="konversi_liter" id="konversi_liter" required value="{{old('konversi_liter')}}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
