<div class="modal fade" id="createModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="createTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createTitle">
                    Tambah Jenis Bahan Baku
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('db.bahan-baku.store')}}" method="post" id="masukForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="apa_konversi" class="form-label">Apakah membutuhkan Konversi?</label>
                            <select class="form-select" name="apa_konversi" id="apa_konversi" required onchange="createFun()">
                                <option value="">-- Pilih --</option>
                                <option value="0">Tidak</option>
                                <option value="1">Ya</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                                <label for="kategori_bahan_id" class="form-label">Nama Kimia</label>
                                <select class="form-select" name="kategori_bahan_id" id="kategori_bahan_id" required>
                                    <option value="">-- Pilih Kategori Barang --</option>
                                    @foreach ($kategori as $i)
                                    <option value="{{$i->id}}">{{$i->nama}}</option>
                                    @endforeach
                                </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="nama" class="form-label">Singkatan</label>
                            <input type="text" class="form-control" name="nama" id="nama" aria-describedby="helpId"
                                placeholder="" />
                        </div>
                        <div class="col-md-4 mb-3" id="divKonversi" hidden>
                            <label for="konversi" class="form-label">Konversi</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1">1</span>
                                <span class="input-group-text" id="basic-addon1">:</span>
                                <input type="text" class="form-control" name="konversi" id="konversi">
                            </div>
                            <small class="text-danger">Gunakan "." untuk nilai desimal!!</small>
                        </div>
                        <div class="col-md-4 mb-3" id="divSatuan" hidden>
                            <label for="satuan_id" class="form-label">Satuan</label>
                            <select class="form-select" name="satuan_id" id="satuan_id">
                                <option value="">-- Pilih Satuan --</option>
                                @foreach ($satuan as $i)
                                <option value="{{$i->id}}">{{$i->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batalkan
                    </button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
