<div class="modal fade" id="editProject" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    role="dialog" aria-labelledby="editProjectTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProjectTitle">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" id="editForm" data-csrf-token="{{ csrf_token() }}">
                @csrf
                @method('patch')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-12 mb-3">
                            <label for="customer_id" class="form-label text-capitalize">Nama Konsumen</label>
                            <select class="form-select" name="customer_id" id="edit_customer_id" required>
                                <option value="" selected>Pilih Konsumen</option>
                                @foreach ($customers as $customer)
                                <option value="{{$customer->id}}">{{$customer->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-12 mb-3">
                            <label for="nama" class="form-label">Nama Singkatan</label>
                            <input type="text" class="form-control" name="nama" id="edit_nama" aria-describedby="helpId"
                                placeholder="" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="npwp" class="form-label">NPWP</label>
                            <input type="text" class="form-control" name="npwp" id="npwp" aria-describedby="helpId"
                                placeholder="" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="cp" class="form-label">Nama Contact Person</label>
                            <input type="text" class="form-control" name="cp" id="cp" aria-describedby="helpId"
                                placeholder="" required>
                        </div>
                        <div class="col-lg-4 col-md-12 mb-3">
                            <label for="nomor_kontrak" class="form-label">No. WA</label>
                            <input type="text" class="form-control" name="nomor_kontrak" id="edit_nomor_kontrak" aria-describedby="helpId"
                                placeholder="" required>
                        </div>
                        <div class="col-lg-4 col-md-12 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="email"
                                aria-describedby="helpId" placeholder="" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <textarea class="form-control" name="alamat" id="alamat" rows="3" required></textarea>
                          </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-4 col-md-12 col-sm-12 mb-3">
                            <label for="nilai" class="form-label">Nilai DPP</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">Rp</span>
                                <input type="text" class="form-control" name="nilai" id="edit_nilai" required>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 mb-3">
                            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar"></i></span>
                                <input type="text" class="form-control" name="tanggal_mulai" id="edit_tanggal_mulai" aria-describedby="helpId"
                                    placeholder="" required readonly>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 mb-3">
                            <label for="jatuh_tempo" class="form-label">Tanggal Jatuh Tempo</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar"></i></span>
                                <input type="text" class="form-control" name="jatuh_tempo" id="edit_jatuh_tempo" aria-describedby="helpId"
                                    placeholder="" required readonly>
                            </div>
                        </div>
                    </div>
                    <hr>
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
