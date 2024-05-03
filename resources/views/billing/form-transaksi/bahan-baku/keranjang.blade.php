<div class="modal fade" id="keranjangBelanja" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    role="dialog" aria-labelledby="keranjangTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="keranjangTitle">Keranjang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @php
            $diskon = 0;
            $ppn = 0;
            $total = $keranjang ? $keranjang->sum('total') : 0;
            $add_fee = $keranjang ? $keranjang->sum('add_fee') : 0;
            @endphp
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead class="table-success">
                        <tr>
                            <th class="text-center align-middle">Kategori Barang</th>
                            <th class="text-center align-middle">Nama Barang</th>
                            <th class="text-center align-middle">Banyak</th>
                            <th class="text-center align-middle">Satuan</th>
                            <th class="text-center align-middle">Harga Satuan</th>
                            <th class="text-center align-middle">Biaya Tambahan</th>
                            <th class="text-center align-middle">Total</th>
                            <th class="text-center align-middle">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($keranjang as $b)
                        <tr>
                            <td class="text-center align-middle">{{$b->bahan_baku->kategori->nama}}</td>
                            <td class="text-center align-middle">{{$b->bahan_baku->nama}}</td>
                            <td class="text-center align-middle">{{$b->nf_jumlah}}</td>
                            <td class="text-center align-middle">{{$b->satuan->nama}}</td>
                            <td class="text-center align-middle">{{number_format($b->harga, 0, ',','.')}}</td>
                            <td class="text-center align-middle">{{number_format($b->add_fee, 0, ',','.')}}</td>
                            <td class="text-end align-middle">{{number_format($b->total + $b->add_fee, 0, ',','.')}}
                            </td>
                            <td class="text-center align-middle">
                                <form action="" method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger btn-sm"><i
                                            class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="text-center align-middle"></td>
                            <td class="text-center align-middle"></td>
                            <td class="text-center align-middle">{{count($keranjang) > 0 ?
                                number_format($keranjang->sum('jumlah'), 0, ',','.') : ''}}</td>
                            <td class="text-center align-middle"></td>
                            <td class="text-center align-middle">{{count($keranjang) > 0 ?
                                number_format($keranjang->sum('harga'), 0, ',','.') : ''}}</td>
                            <td class="text-center align-middle">{{count($keranjang) > 0 ?
                                number_format($keranjang->sum('add_fee'), 0, ',','.') : ''}}</td>
                            <td class="text-end align-middle">{{count($keranjang) > 0 ?
                                number_format($keranjang->sum('total'), 0, ',','.') : ''}}</td>
                            <td class="text-center align-middle"></td>
                        </tr>
                        <tr>
                            <td class="text-end align-middle" colspan="6">Total DPP</td>
                            <td class="text-end align-middle">{{count($keranjang) > 0 ?
                                number_format($keranjang->sum('total') + $keranjang->sum('add_fee'), 0, ',','.') : ''}}
                            </td>
                            <td class="text-center align-middle"></td>
                        </tr>
                        <tr>
                            <td class="text-end align-middle" colspan="6">Diskon</td>
                            <td class="text-end align-middle" id="tdDiskon">
                                {{number_format($diskon, 0, ',','.')}}
                            </td>
                            <td class="text-center align-middle"></td>
                        </tr>
                        <tr>
                            <td class="text-end align-middle" colspan="6">Total DPP Setelah Diskon</td>
                            <td class="text-end align-middle" id="tdDiskon">
                                {{number_format($total-$diskon, 0, ',','.')}}
                            </td>
                            <td class="text-center align-middle"></td>
                        </tr>
                        <tr>
                            <td class="text-end align-middle" colspan="6">PPN</td>
                            <td class="text-end align-middle">
                                0
                            </td>
                            <td class="text-center align-middle"></td>
                        </tr>
                        <tr>
                            <td class="text-end align-middle" colspan="6">Grand Total</td>
                            <td class="text-end align-middle" id="grand_total">
                                {{number_format($total + $add_fee + $ppn - $diskon, 0, ',','.')}}
                            </td>
                            <td class="text-center align-middle"></td>
                        </tr>
                    </tfoot>
                </table>
                <form action="" method="get" id="beliBarang">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="uraian" class="form-label">Uraian</label>
                                <input type="text" class="form-control" name="uraian" id="uraian"
                                    aria-describedby="helpId" placeholder="" required maxlength="20">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="uraian" class="form-label">Apakah menggunakan PPn?</label>
                                <select class="form-select" name="ppn" id="ppn">
                                    <option value="">-- Pilih Salah Satu --</option>
                                    <option value="1">Dengan PPn</option>
                                    <option value="0">Tanpa PPn</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="diskon" class="form-label">Diskon</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">Rp</span>
                                    <input type="text" class="form-control" name="diskon" id="diskon"
                                        aria-describedby="helpId" placeholder="" required value="0" onkeyup="add_diskon()">
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h2>
                        Transfer Ke
                    </h2>
                    <br>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="transfer_ke" class="form-label">Nama Rekening</label>
                            <input type="text" class="form-control @if ($errors->has('transfer_ke'))
                        is-invalid
                    @endif" name="transfer_ke" id="transfer_ke" value="{{old('transfer_ke')}}" maxlength="15" required>
                            @if ($errors->has('transfer_ke'))
                            <div class="invalid-feedback">
                                {{$errors->first('transfer_ke')}}
                            </div>
                            @endif
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="bank" class="form-label">Bank</label>
                            <input type="text" class="form-control @if ($errors->has('bank'))
                        is-invalid
                    @endif" name="bank" id="bank" value="{{old('bank')}}" maxlength="10" required>
                            @if ($errors->has('bank'))
                            <div class="invalid-feedback">
                                {{$errors->first('bank')}}
                            </div>
                            @endif
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="no_rekening" class="form-label">Nomor Rekening</label>
                            <input type="text" class="form-control @if ($errors->has('no_rekening'))
                        is-invalid
                    @endif" name="no_rekening" id="no_rekening" value="{{old('no_rekening')}}" required>
                            @if ($errors->has('no_rekening'))
                            <div class="invalid-feedback">
                                {{$errors->first('no_rekening')}}
                            </div>
                            @endif
                        </div>
                    </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Beli Barang</button>
                </form>
            </div>
        </div>
    </div>
</div>
