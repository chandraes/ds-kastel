
<div class="modal fade" id="create-category" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="creat-category-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="creat-category-title">Tambah Nama Kimia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-responsive table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center align-middle">No</th>
                            <th class="text-center align-middle">Nama Kimia</th>
                            <th class="text-center align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kategori as $j)
                        <tr>
                            <td class="text-center align-middle">{{$loop->iteration}}</td>
                            <td class="text-center align-middle">
                                <form action="{{route('db.bahan-baku.kategori.update', $j->id)}}" method="post" id="updateJabatan">
                                    @csrf
                                    @method('patch')
                                    <input type="text" class="form-control" name="nama" id="nama_jabatan-{{$j->id}}"
                                        aria-describedby="helpId" placeholder="" value="{{$j->nama}}" readonly>
                                    <div class="btn-group m-3" role="group" aria-label="Save or cancel" id="buttonJabatan-{{$j->id}}" hidden>
                                        <button type="submit" class="btn btn-success">Simpan</button>
                                        <a onclick="toggleNamaJabatan({{$j->id}})" type="button" class="btn btn-secondary">Batal</a>
                                    </div>
                                </form>
                            </td>
                            <td class="text-center align-middle">
                                {{-- button to submit form #updateJabatan --}}

                                <a onclick="toggleNamaJabatan({{$j->id}})" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                                {{-- form delete with confirmation --}}
                                <form action="{{route('db.bahan-baku.kategori.delete', $j)}}" method="post" class="d-inline">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah anda yakin untuk menghapus data ini?')"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <form action="{{route('db.bahan-baku.kategori.store')}}" method="post">
                    @csrf
                    <div class="input-group mb-3 mt-3">
                        <input type="text" class="form-control" name="nama" id="nama_jabatan_tambah"
                            aria-describedby="helpId" placeholder="Nama Kategori" required>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

