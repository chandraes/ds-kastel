<div class="modal fade" id="createInvestor" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    role="dialog" aria-labelledby="investorTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="investorTitle">Tambah Kemasan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('db.kemasan.store')}}" method="post" id="createForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="product_id" class="form-label">Product</label>
                            <select name="product_id" id="product_id" class="form-select" required>
                                <option value="" selected disabled>Pilih Product</option>
                                @foreach ($product as $prod)
                                <option value="{{$prod->id}}">{{$prod->kategori->nama}} - {{$prod->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="harga" class="form-label">Harga Jual Price list (Per Packaging)</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Rp</span>
                                <input type="text" class="form-control" name="harga" id="harga" required value="{{old('harga')}}">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nama" class="form-label">Nama Kemasan</label>
                            <input type="text" class="form-control" name="nama" id="nama" aria-describedby="helpId"
                                placeholder="" required value="{{old('nama')}}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cp" class="form-label">Satuan Kemasan</label>
                            <select name="satuan_id" id="satuan_id" class="form-select" required>
                                <option value="" selected disabled>Pilih Satuan</option>
                                @foreach ($satuan as $s)
                                <option value="{{$s->id}}">{{$s->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="packaging_id" class="form-label">Packaging</label>
                            <select name="packaging_id" id="packaging_id" class="form-select" required>
                                <option value="" selected disabled>Pilih Packaging</option>
                                <option value="0">Tanpa Packaging</option>
                                @foreach ($packaging as $p)
                                <option value="{{$p->id}}">{{$p->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="konversi_liter" class="form-label">Konversi Liter</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">1</span>
                                <span class="input-group-text" id="basic-addon1">:</span>
                                <input type="text" class="form-control" name="konversi_liter" id="konversi_liter" required value="{{old('konversi_liter')}}">
                            </div>
                            <small class="text-danger">Gunakan "." untuk nilai desimal!!</small>
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
