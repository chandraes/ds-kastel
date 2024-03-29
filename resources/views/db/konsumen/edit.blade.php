<div class="modal fade" id="editInvestor" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    role="dialog" aria-labelledby="investorTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="investorTitle">Tambah Investor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" id="editForm">
                @csrf
                @method('patch')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-6 mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control" name="nama" id="edit_nama" aria-describedby="helpId"
                                placeholder="" required>
                        </div>
                        <div class="col-md-4 col-sm-6 mb-3">
                            <label for="singkatan" class="form-label">Singkatan</label>
                            <input type="text" class="form-control" name="singkatan" id="edit_singkatan" aria-describedby="helpId"
                                placeholder="" required>
                        </div>
                        <div class="col-md-4 col-sm-6 mb-3">
                            <label for="cp" class="form-label">Contact Person</label>
                            <input type="text" class="form-control" name="cp" id="edit_cp" aria-describedby="helpId"
                                placeholder="" required>
                        </div>
                        <div class="col-md-4 col-sm-6 mb-3">
                            <label for="no_hp" class="form-label">No HP</label>
                            <input type="text" class="form-control" name="no_hp" id="edit_no_hp" aria-describedby="helpId"
                                placeholder="" required>
                        </div>
                        <div class="col-md-4 col-sm-6 mb-3">
                            <label for="npwp" class="form-label">NPWP</label>
                            <input type="text" class="form-control" name="npwp" id="edit_npwp" aria-describedby="helpId"
                                placeholder="" required>
                        </div>
                        <div class="col-md-12 col-sm-12 mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea name="alamat" id="edit_alamat" cols="30" rows="5" class="form-control"></textarea>
                        </div>
                        <div class="row">
                            <div class="btn-group" role="group" data-bs-toggle="buttons">
                                <label class="btn btn-secondary active">
                                    <input type="checkbox" class="me-2" name="ppn" id="edit_ppn" autocomplete="off" />
                                    PPn
                                </label>
                                <label class="btn btn-secondary">
                                    <input type="checkbox" class="me-2" name="pph" id="edit_pph" autocomplete="off" />
                                    PPh
                                </label>
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
