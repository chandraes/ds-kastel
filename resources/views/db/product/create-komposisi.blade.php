<div class="modal fade" id="komposisiModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    role="dialog" aria-labelledby="komposisiTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="komposisiTitle">
                    Tambah Komposisi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('db.product.store-komposisi')}}" method="post" id="komposisiForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="product_id" id="product_id">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="bahan_baku_id" class="form-label">Bahan Baku</label>
                                <select class="form-select" name="bahan_baku_id" id="bahan_baku_id" required>
                                    <option selected>-- Pilih Bahan Baku --</option>
                                    @foreach ($bahan as $i)
                                    <option value="{{$i->id}}">{{$i->nama}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="konversi" class="form-label">Persentase Komposisi</label>
                            <div class="input-group mb-3">
                                <input type="number" class="form-control" name="jumlah" id="jumlah" required
                                    value="{{old('jumlah')}}" required>
                                <span class="input-group-text" id="basic-addon1">%</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
