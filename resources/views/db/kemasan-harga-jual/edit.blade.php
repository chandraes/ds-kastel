<div class="modal fade" id="editInvestor" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    role="dialog" aria-labelledby="editInvestorTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editInvestorTitle">Edit Harga Jual Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" id="editForm">
                @csrf
                @method('patch')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="product_id" class="form-label">Product</label>
                            <select name="product_id" id="edit_product_id" class="form-select" required disabled>
                                <option value="" selected disabled>Pilih Product</option>
                                @foreach ($product as $prod)
                                <option value="{{$prod->id}}">{{$prod->kategori->nama}} - {{$prod->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="kemasan_kategori_id" class="form-label">Bentuk Kemasan</label>
                            <input type="text" id="kemasan_kategori_id" disabled class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="packaging_id" class="form-label">Packaging</label>
                            <select name="packaging_id" id="edit_packaging_id" class="form-select" required disabled>
                                <option value="" selected disabled>Pilih Packaging</option>
                                <option value="0">Tanpa Packaging</option>
                                @foreach ($packaging as $p)
                                <option value="{{$p->id}}">{{$p->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="harga_satuan" class="form-label">Isi Kemasan</label>
                            <div class="input-group mb-3">
                                <input type="text" name="edit_isi_kemasan" id="edit_isi_kemasan" disabled class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="harga_satuan" class="form-label">Harga Satuan Kemasan</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Rp</span>
                                <input type="text" class="form-control" name="harga_satuan" id="edit_harga_satuan" required value="{{old('harga_satuan')}}" onkeyup="calculateTotal()">
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="harga" class="form-label">Harga Per Packaging</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Rp</span>
                                <input type="text" class="form-control" name="harga" id="edit_harga" required value="{{old('harga')}}" disabled>
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
