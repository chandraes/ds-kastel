<div class="modal fade" id="editInvestor" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    role="dialog" aria-labelledby="editInvestorTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editInvestorTitle">Edit Kemasan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" id="editForm">
                @csrf
                @method('patch')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="product_id" class="form-label">Product</label>
                            <select name="product_id" id="edit_product_id" class="form-select" required>
                                <option value="" selected disabled>Pilih Product</option>
                                @foreach ($product as $prod)
                                <option value="{{$prod->id}}">{{$prod->kategori->nama}} - {{$prod->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="kemasan_kategori_id" class="form-label">Nama Kemasan</label>
                            <select name="kemasan_kategori_id" id="edit_kemasan_kategori_id" class="form-select" required>
                                <option value="" selected disabled>Pilih Kemasan</option>
                                @foreach ($kategori as $k)
                                <option value="{{$k->id}}">{{$k->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cp" class="form-label">Satuan Kemasan</label>
                            <select name="satuan_id" id="edit_satuan_id" class="form-select" required>
                                <option value="" disabled>Pilih Satuan</option>
                                @foreach ($satuan as $s)
                                <option value="{{$s->id}}" selected>{{$s->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="packaging_id" class="form-label">Packaging</label>
                            <select name="packaging_id" id="edit_packaging_id" class="form-select" required>
                                <option value="" selected disabled>Pilih Packaging</option>
                                <option value="0">Tanpa Packaging</option>
                                @foreach ($packaging as $p)
                                <option value="{{$p->id}}">{{$p->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="konversi_liter" class="form-label">Liter : Isi Kemasan</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">1</span>
                                <span class="input-group-text" id="basic-addon1">:</span>
                                <input type="text" class="form-control" name="konversi_liter" id="edit_konversi_liter" required value="{{old('konversi_liter')}}">
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
