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
                        <div class="col-4 mb-3">
                            <label for="nama" class="form-label">Nama Kemasan</label>
                            <input type="text" class="form-control" name="nama" id="edit_nama" aria-describedby="helpId"
                                placeholder="" required value="{{old('nama')}}">
                        </div>
                        <div class="col-4 mb-3">
                            <label for="cp" class="form-label">Satuan</label>
                            <select name="satuan_id" id="edit_satuan_id" class="form-select" required>
                                <option value="" selected disabled>Pilih Satuan</option>
                                @foreach ($satuan as $s)
                                <option value="{{$s->id}}">{{$s->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-4 mb-3">
                            <label for="konversi_liter" class="form-label">Konversi Liter</label>
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
