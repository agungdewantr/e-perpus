<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style>
    .select2-container--open {
        z-index: 1200;
    }
    .select2-search__field{
        height:26px !important;
    }
    .select2-selection__rendered {
        line-height: 31px !important;
    }
    .select2-container .select2-selection--single {
        height: 38px !important;
    }
    .select2-selection__arrow {
        height: 34px !important;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<form class="form" id="form_edit_rellarphp" enctype="multipart/form-data">
    @csrf
    <div class="modal-body" id="modalCenterLargeContent">
        <div class="row g-3">
            <div class="col-lg-12">
                <div>
                    <div class="mb-3">
                        <label for="jenis_id" class="form-label">Jenis <span class="text-danger">*</span></label>
                        <select id="jenis_id" name="jenis_id" class="form-select" disabled>
                            <option class="">-- Pilih Jenis --</option>
                            <option class="Buku" {{ $buku->jenis == 'Buku' ? 'selected' : '' }}>Buku</option>
                            <option class="Buku Digital" {{ $buku->jenis == 'Buku Digital' ? 'selected' : '' }}>Buku
                                Digital</option>
                            <option class="Buku Audio" {{ $buku->jenis == 'Buku Audio' ? 'selected' : '' }}>Buku Audio
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 d-none" id="formJudul">
                <div class="mb-3">
                    <label for="judul" class="form-label">Judul <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" placeholder="Masukkan Judul" name="judul"
                        id="judul" value="{{ $buku->judul }}" required>
                    <small class="validation-judul text-danger"></small>
                </div>
                <div class="col-lg-12 d-none" id="formDeskripsi">
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                        <textarea class="form-control" placeholder="Masukkan deskripsi" id="deskripsi" name="deskripsi" required>{{$buku->deskripsi}}</textarea>
                        <small class="validation-deskripsi text-danger"></small>
                    </div>
                </div>
            </div>
            {{-- OPEN JENIS BUKU  --}}
            <div class="row" id="jenisBuku"></div>
            {{-- CLOSE JENIS BUKU --}}
            {{-- OPEN JENIS BUKU DIGITAL  --}}
            <div class="row" id="jenisBukuDigital"></div>
            {{-- CLOSE JENIS BUKU DIGITAL  --}}
            {{-- OPEN JENIS BUKU DIGITAL  --}}
            <div class="row" id="jenisBukuAudio"></div>
            {{-- CLOSE JENIS BUKU DIGITAL  --}}
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary waves-effect text-left" data-bs-dismiss="modal"
            aria-label="Close">
            <i class="fa fa-times"></i> &nbsp; Batal
        </button>
        <button type="submit" class="btn btn-sm btn-success waves-effect text-left" id="btn_s" disabled>
            <i class="fa fa-save"></i> &nbsp; Simpan
        </button>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        let kategori_id = {{$buku->subKategori->kategori_id}};
        var val = $('#jenis_id').val();
        contentJenisBuku = `
                                        <input type="hidden" class="form-control" id="id" name="id" value="{{ encrypt($buku->id) }}">
                                        <div class="col-lg-4 col-md-12">
                                            <div>
                                                <div class="mb-3">
                                                    <label for="kategori_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                                                    <select id="kategori_id" name="kategori_id" class="form-select" required>
                                                        <option value="" disabled selected>--Pilih Kategori--</option>
                                                        @foreach ($kategoris as $kategori)
                                                            <option value="{{ $kategori->id }}" {{$buku->subKategori->kategori->id == $kategori->id ? 'selected' : ''}}>{{ $kategori->kode.'-'.$kategori->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                    <small class="validation-kategori_id text-danger"></small>
                                                </div>
                                                <div class="mb-3" style="margin-top:-1px">
                                                    <label for="sub_kategori_id" class="form-label">Sub Kategori <span class="text-danger">*</span></label>
                                                    <select id="sub_kategori_id" name="sub_kategori_id" class="form-select" required>
                                                        <option value="" disabled selected>--Pilih Sub Kategori--</option>
                                                        @foreach ($subkategoris as $subkategori)
                                                            <option value="{{ $subkategori->id }}" {{$buku->subKategori->id == $subkategori->id ? 'selected' : ''}}>{{ $subkategori->kode.'-'.$subkategori->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                    <small class="validation-sub_kategori_id text-danger"></small>
                                                </div>
                                                <div class="mb-3" style="margin-top:-1px">
                                                    <label for="isbn" class="form-label">ISBN <span class="text-danger">*</span></label>
                                                    <input class="form-control" id="isbn" name="isbn" placeholder="Masukkan isbn" value="{{ $buku->isbn ?? null }}" required></input>
                                                    <small class="validation-isbn text-danger"></small>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="penerbit_id" class="form-label">Penerbit <span class="text-danger">*</span></label>
                                                    <select id="penerbit_id" name="penerbit_id" class="form-select">
                                                        <option value="" disabled selected>-- Pilih Penerbit --</option>
                                                        @foreach ($penerbits as $penerbit)
                                                        <option value="{{ $penerbit->id }}" {{ $penerbit->id == $buku->penerbit->id ? 'selected' : '' }}>{{ $penerbit->namaPenerbit }}</option>
                                                        @endforeach
                                                    </select>
                                                    <small class="validation-penerbit_id text-danger"></small>
                                                </div>
                                                <div class="mb-3" style="margin-top:-1px">
                                                    <label for="penulis_id" class="form-label">Penulis <span class="text-danger">*</span></label>
                                                    <select id="penulis_id" name="penulis_id[]" class="form-select" multiple data-placeholder="-- Pilih Penulis --">
                                                        @foreach ($penulises as $penulis)
                                                            <option value="{{ $penulis->id }}" {{ in_array($penulis->id, $buku->penulises->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $penulis->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                    <small class="validation-penulis_id text-danger"></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-12">
                                            <div>
                                                <div class="mb-3">
                                                    <label for="tahun_cetak" class="form-label">Tahun Cetak</label>
                                                    <input type='number' class="form-control" name="tahun_cetak" id="tahun_cetak" value="{{$buku->tahun_cetak ?? date('Y')}}" max="{{date('Y')}}">
                                                    <small class="validation-tahun_cetak text-danger"></small>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="tanggal_terbit" class="form-label">Tanggal Terbit</label>
                                                    <input class="form-control" type="date" name="tanggal_terbit" id="tanggal_terbit" value="{{ $buku->tanggal_terbit }}">
                                                    <small class="validation-tanggal_terbit text-danger"></small>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="jumlah_halaman" class="form-label">Jumlah Halaman <span class="text-danger">*</span></label>
                                                    <input class="form-control" type="string" name="jumlah_halaman" id="jumlah_halaman" placeholder="Masukkan Jumlah Halaman" value="{{ $buku->jumlah_halaman }}" required>
                                                    <small class="validation-jumlah_halaman text-danger"></small>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="ukuran" class="form-label">Ukuran <span class="text-danger">*</span></label>
                                                    <input class="form-control" type="string" name="ukuran" id="ukuran" placeholder="Masukkan Ukuran" value="{{ $buku->ukuran }}" required>
                                                    <small class="validation-ukuran text-danger"></small>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="kode_buku" class="form-label">Kode Buku <span class="text-danger">*</span></label>
                                                    <input class="form-control" type="string" name="kode_buku" id="kode_buku" placeholder="Masukkan Kode Buku (Contoh: 100.1/RED/p)" value="{{ $buku->kode_buku }}" required>
                                                    <small class="validation-kode_buku text-danger"></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-12">
                                            <div>
                                                <div class="mb-3">
                                                    <label for="kertas_isi" class="form-label">Kertas Isi <span class="text-danger">*</span></label>
                                                    <input class="form-control" type="string" name="kertas_isi" id="kertas_isi" placeholder="Masukkan Kertas Isi" value="{{ $buku->kertas_isi }}" required>
                                                    <small class="validation-kertas_isi text-danger"></small>
                                                </div><div class="mb-3">
                                                    <label for="cetak_cover" class="form-label">Cetak Cover <span class="text-danger">*</span></label>
                                                    <input class="form-control" type="string" name="cetak_cover" id="cetak_cover" placeholder="Masukkan Cetak Cover" value="{{ $buku->cetak_cover }}" required>
                                                    <small class="validation-cetak_cover text-danger"></small>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="cetak_isi" class="form-label">Cetak Isi <span class="text-danger">*</span></label>
                                                    <input class="form-control" type="string" name="cetak_isi" id="cetak_isi" placeholder="Masukkan Cetak Isi" value="{{ $buku->cetak_isi }}" required>
                                                    <small class="validation-cetak_isi text-danger"></small>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="rak_id" class="form-label">Rak <span class="text-danger">*</span></label>
                                                    <select id="rak_id" name="rak_id" class="form-select">
                                                        <option value="" disabled selected>-- Pilih Rak --</option>
                                                        @foreach ($raks as $rak)
                                                            <option value="{{ $rak->id }}" {{ $buku->raks_id == $rak->id ? 'selected' : '' }}>{{ $rak->kode }}</option>
                                                        @endforeach
                                                    </select>
                                                    <small class="validation-rak_id text-danger"></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-10 col-md-12">
                                            <div>
                                                <div class="mb-3">
                                                    <label for="cover_id" class="form-label">Unggah Sampul Buku</label>
                                                    <input class="form-control" type="file" name="cover_id" id="cover_id">
                                                    <small class="validation-cover_id text-danger">Catatan: Jika tidak ada perubahan pada sampul buku, silakan biarkan kolom ini kosong.</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-12">
                                            <div class="text-center">
                                                <span class='text-danger' style="font-size:11px">Sampul saat ini</span>
                                                <div class="mb-3 d-flex justify-content-center">
                                                    @if ($buku->cover)
                                                        <a href="{{ asset('storage/' . $buku->cover->file_path) }}" title="Lihat Sampul Buku">
                                                            <img src="{{ asset('storage/' . $buku->cover->file_path) }}" alt="Foto Kartu Identitas" style="width: 40px;">
                                                        </a>
                                                    @else
                                                        <a href="{{ asset('web_assets/images/books/grid/not-found-book.png') }}" title="Lihat Sampul Buku">
                                                            <img src="{{ asset('web_assets/images/books/grid/not-found-book.png') }}" alt="Foto Kartu Identitas" style="width: 40px;">
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        {{-- OPEN ITEM BUKU --}}
                                            <div class="accordion" id="accordionExample">
                                                <div id="formItemBuku">
                                                    @foreach ($buku->itemBukus as $key => $itemBuku)
                                                        <div class='itemBukuContent' id="itemBukuContent{{ $key }}">
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="heading{{$key}}">
                                                                    <button class="accordion-button fw-medium judulItemBuku" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$key}}" aria-expanded="true" aria-controls="collapse{{$key}}">
                                                                        Item Buku #{{ $key+1 }}
                                                                    </button>
                                                                </h2>
                                                                <div id="collapse{{$key}}" class="accordion-collapse collapse show" aria-labelledby="heading{{$key}}" data-bs-parent="#accordionExample">
                                                                    <div class="accordion-body">
                                                                        <div class="row">
                                                                            <input type="hidden" class="form-control" id="idItemBuku{{ $key }}" name="idItemBuku[]" value="{{ encrypt($itemBuku->id) }}">
                                                                            <hr>
                                                                            <div class="d-flex justify-content-between">
                                                                                <h4 class='judulItemBuku'>Item Buku #{{ $key + 1 }}</h4>
                                                                                <div>
                                                                                    <button type="button" class="btnHapusItemBuku btn btn-sm btn-outline-danger waves-effect waves-light mx-2" id="btnHapusItemBuku0"><i class="fas fa-trash"></i> Hapus Item Buku</button>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-12">
                                                                                <div>
                                                                                    <div class="mb-3">
                                                                                        <label for="pengadaan" class="form-label">Pengadaan <span class="text-danger">*</span></label>
                                                                                        <select id="pengadaan" name="pengadaan[]" class="form-select selectpengadaan">
                                                                                            <option value="" disabled selected>-- Pilih Pengadaan --</option>
                                                                                            @foreach ($pengadaans as $pengadaan)
                                                                                                <option value="{{ $pengadaan->id }}" {{ $itemBuku->pengadaans_id == $pengadaan->id ? 'selected' : '' }}>{{ $pengadaan->nama }}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-6 col-md-12">
                                                                                <div>
                                                                                    <div class="mb-3">
                                                                                        <label for="harga" class="form-label">Harga <span class="text-danger">*</span></label>
                                                                                        <input class="form-control" type="text" placeholder="Masukkan Harga" name="harga[]" id="harga" value="{{ $itemBuku->harga }}" required>
                                                                                        <small class="validation-harga0 text-danger"></small>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-6 col-md-12">
                                                                                <div>
                                                                                    <div class="mb-3">
                                                                                        <label for="kondisi" class="form-label">Kondisi <span class="text-danger">*</span></label>
                                                                                        <select id="kondisi" name="kondisi[]" class="form-select">
                                                                                            <option value="" disabled selected>-- Pilih Kondisi --</option>
                                                                                            <option class="Baik" {{ $itemBuku->kondisi == 'Baik' ? 'selected' : '' }} {{ $itemBuku->kondisi == 'Rusak' ? 'disabled' : '' }}>Baik</option>
                                                                                            <option class="Rusak" {{ $itemBuku->kondisi == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-12">
                                                                                <div class="row">
                                                                                    <div class="col-lg-2">
                                                                                        <div class="mb-3">
                                                                                            <div class="row">
                                                                                                <div class="col-12">
                                                                                                    <label for="is_active{{ $key }}" class="form-label">Status <span class="text-danger">*</span></label>
                                                                                                </div>
                                                                                                <div class="col-12">
                                                                                                    <input type="checkbox" class="is_active" id="is_active{{ $key }}" name="is_active[]" switch="none" onclick="formItemBuku({{ $key }})" {{ $itemBuku->is_active == true ? 'checked' : '' }} />
                                                                                                    <label for="is_active{{ $key }}" data-on-label="On" data-off-label="Off"></label>
                                                                                                    <input type="hidden" id="is_active_value{{ $key }}" name="is_active_value[]" value="{{ $itemBuku->is_active ? "true" : "false" }}" />
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-lg-10 {{ $itemBuku->is_active ? 'd-none' : 'd-block' }}" id='keterangan{{ $key }}'>
                                                                                        <div class="mb-3">
                                                                                            <label for="keterangan_is_active{{ $key }}" class="form-label">Keterangan Status</label>
                                                                                            <textarea class="form-control" type="text" placeholder="Masukkan Keterangan" name="keterangan_is_active[]" id="keterangan_is_active{{ $key }}">{{ $itemBuku->keterangan_is_active ?? null }}</textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div id="itemBukuButton mt-2">
                                                    <div class="d-flex justify-content-end">
                                                        <button type="button" class="btn btn-sm btn-outline-success waves-effect waves-light" id="btnTambahItemBuku"><i class="fas fa-plus"></i> Tambah Item Buku</button>
                                                    </div>
                                                </div>
                                            </div>
                                        {{-- CLOSE ITEM BUKU --}}
            `;
        contentJenisBukuDigital = `
                                        <input type="hidden" class="form-control" id="id" name="id" value="{{ encrypt($buku->id) }}">
                                        <div class="col-lg-4 col-md-12">
                                            <div>
                                                <div class="mb-3">
                                                    <label for="kategori_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                                                    <select id="kategori_id" name="kategori_id" class="form-select" required>
                                                        <option value="" disabled selected>--Pilih Kategori--</option>
                                                        @foreach ($kategoris as $kategori)
                                                            <option value="{{ $kategori->id }}" {{$buku->subKategori->kategori->id == $kategori->id ? 'selected' : ''}}>{{ $kategori->kode.'-'.$kategori->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                    <small class="validation-kategori_id text-danger"></small>
                                                </div>
                                                <div class="mb-3" style="margin-top:-1px">
                                                    <label for="sub_kategori_id" class="form-label">Sub Kategori <span class="text-danger">*</span></label>
                                                    <select id="sub_kategori_id" name="sub_kategori_id" class="form-select" required>
                                                        <option value="" disabled selected>--Pilih Sub Kategori--</option>
                                                        @foreach ($subkategoris as $subkategori)
                                                            <option value="{{ $subkategori->id }}" {{$buku->subKategori->id == $subkategori->id ? 'selected' : ''}}>{{ $subkategori->kode.'-'.$subkategori->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                    <small class="validation-sub_kategori_id text-danger"></small>
                                                </div>
                                                <div class="mb-3" style="margin-top:-1px">
                                                    <label for="isbn" class="form-label">ISBN <span class="text-danger">*</span></label>
                                                    <input class="form-control" id="isbn" name="isbn" placeholder="Masukkan isbn" value="{{ $buku->isbn }}" required></input>
                                                    <small class="validation-isbn text-danger"></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-12">
                                            <div>
                                                <div class="mb-3">
                                                    <label for="penerbit_id" class="form-label">Penerbit <span class="text-danger">*</span></label>
                                                    <select id="penerbit_id" name="penerbit_id" class="form-select">
                                                        <option value="" disabled selected>-- Pilih Penerbit --</option>
                                                        @foreach ($penulises as $penerbit)
                                                            <option value="{{ $penerbit->id }}" {{ $penerbit->id == $buku->penerbit_id ? 'selected' : '' }}>{{ $penerbit->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                    <small class="validation-penerbit_id text-danger"></small>
                                                </div>
                                                <div class="mb-3" style="margin-top:-1px">
                                                    <label for="penulis_id" class="form-label">Penulis <span class="text-danger">*</span></label>
                                                    <select id="penulis_id" name="penulis_id[]" class="form-select" multiple data-placeholder="-- Pilih Penulis --">
                                                        @foreach ($penulises as $penulis)
                                                            <option value="{{ $penulis->id }}" {{ in_array($penulis->id, $buku->penulises->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $penulis->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                    <small class="validation-penulis_id text-danger"></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-12">
                                            <div>
                                                <div class="mb-3">
                                                    <label for="tanggal_terbit" class="form-label">Tanggal Terbit <span class="text-danger">*</span></label>
                                                    <input class="form-control" type="date" name="tanggal_terbit" id="tanggal_terbit" value="{{ $buku->tanggal_terbit }}" required>
                                                    <small class="validation-tanggal_terbit text-danger"></small>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="jumlah_halaman" class="form-label">Jumlah Halaman <span class="text-danger">*</span></label>
                                                    <input class="form-control" type="string" name="jumlah_halaman" id="jumlah_halaman" value="{{ $buku->jumlah_halaman }}" placeholder="Masukkan Jumlah Halaman" required>
                                                    <small class="validation-jumlah_halaman text-danger"></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-10 col-md-12">
                                            <div>
                                                <div class="mb-3">
                                                    <label for="cover_id" class="form-label">Unggah Sampul Buku</label>
                                                    <input class="form-control" type="file" name="cover_id" id="cover_id">
                                                    <small class="validation-cover_id text-danger">Catatan: Jika tidak ada perubahan pada sampul buku, silakan biarkan kolom ini kosong.</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-12">
                                            <div class="text-center">
                                                <span class='text-danger' style="font-size:11px">Sampul saat ini</span>
                                                <div class="mb-3 d-flex justify-content-center">
                                                    @if ($buku->cover)
                                                        <a href="{{ asset('storage/' . $buku->cover->file_path) }}" title="Lihat Sampul Buku">
                                                            <img src="{{ asset('storage/' . $buku->cover->file_path) }}" alt="Foto Kartu Identitas" style="width: 40px;">
                                                        </a>
                                                    @else
                                                        <a href="{{ asset('web_assets/images/books/grid/not-found-book.png') }}" title="Lihat Sampul Buku">
                                                            <img src="{{ asset('web_assets/images/books/grid/not-found-book.png') }}" alt="Foto Kartu Identitas" style="width: 40px;">
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @if ($buku->jenis == 'Buku Digital')
                                            <div class="col-lg-10 col-md-12">
                                                <div>
                                                    <div class="mb-3">
                                                        <label for="file_digital_id" class="form-label">Unggah Buku Digital</label>
                                                        <input class="form-control" type="file" name="file_digital_id" id="file_digital_id">
                                                        <small class="validation-file_digital_id text-danger">Catatan: Jika tidak ada perubahan pada file buku, silakan biarkan kolom ini kosong.</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2 col-md-12">
                                                <div class="text-center">
                                                    <span class='text-danger' style="font-size:11px">File saat ini</span>
                                                    <div class="mb-3 d-flex justify-content-center">
                                                        <a href="{{ route('manajemenbuku.show.pdf', encrypt($buku->id)) }}" target="_blank" noreferer noopener title="Lihat Sampul Buku">
                                                            <i class="fas fa-file-pdf fa-3x"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <div class="mb-3">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <label for="is_active" class="form-label">Status <span class="text-danger">*</span></label>
                                                            </div>
                                                            <div class="col-12">
                                                                <input type="checkbox" class="is_active" id="is_activeBukuDigital" name="is_active" switch="none" onclick="formItemBuku('BukuDigital')"  {{ $buku->is_active ? 'checked' : '' }} />
                                                                <label for="is_activeBukuDigital" data-on-label="On" data-off-label="Off"></label>
                                                                <input type="hidden" id="is_active_valueBukuDigital" name="is_active_value" value="{{ $buku->is_active ? "true" : "false" }}" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
            `;
        contentJenisBukuAudio = `
                                        <input type="hidden" class="form-control" id="id" name="id" value="{{ encrypt($buku->id) }}">
                                        <div class="col-lg-4 col-md-12">
                                            <div>
                                                <div class="mb-3">
                                                    <label for="kategori_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                                                    <select id="kategori_id" name="kategori_id" class="form-select" required>
                                                        <option value="" disabled selected>--Pilih Kategori--</option>
                                                        @foreach ($kategoris as $kategori)
                                                            <option value="{{ $kategori->id }}" {{$buku->subKategori->kategori->id == $kategori->id ? 'selected' : ''}}>{{ $kategori->kode.'-'.$kategori->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                    <small class="validation-kategori_id text-danger"></small>
                                                </div>
                                                <div class="mb-3" style="margin-top:-1px">
                                                    <label for="sub_kategori_id" class="form-label">Sub Kategori <span class="text-danger">*</span></label>
                                                    <select id="sub_kategori_id" name="sub_kategori_id" class="form-select" required>
                                                        <option value="" disabled selected>--Pilih Sub Kategori--</option>
                                                        @foreach ($subkategoris as $subkategori)
                                                            <option value="{{ $subkategori->id }}" {{$buku->subKategori->id == $subkategori->id ? 'selected' : ''}}>{{ $subkategori->kode.'-'.$subkategori->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                    <small class="validation-sub_kategori_id text-danger"></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-12">
                                            <div>
                                                <div class="mb-3">
                                                    <label for="penerbit_id" class="form-label">Penerbit <span class="text-danger">*</span></label>
                                                    <select id="penerbit_id" name="penerbit_id" class="form-select">
                                                        <option value="" disabled selected>-- Pilih Penerbit --</option>
                                                        @foreach ($penerbits as $penerbit)
                                                            <option value="{{ $penerbit->id }}" {{ $penerbit->id == $buku->penerbit_id ? 'selected' : '' }}>{{ $penerbit->namaPenerbit }}</option>
                                                        @endforeach
                                                    </select>
                                                    <small class="validation-penerbit_id text-danger"></small>
                                                </div>
                                                <div class="mb-3" style="margin-top:-1px">
                                                    <label for="penulis_id" class="form-label">Penulis <span class="text-danger">*</span></label>
                                                    <select id="penulis_id" name="penulis_id[]" class="form-select" multiple data-placeholder="-- Pilih Penulis --">
                                                        @foreach ($penulises as $penulis)
                                                            <option value="{{ $penulis->id }}" {{ in_array($penulis->id, $buku->penulises->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $penulis->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                    <small class="validation-penulis_id text-danger"></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-12">
                                            <div>
                                                <div class="mb-3">
                                                    <label for="narator" class="form-label">Narator <span class="text-danger">*</span></label>
                                                    <input class="form-control" id="narator" name="narator" placeholder="Masukkan Narator" value="{{ $buku->narator }}" required></input>
                                                    <small class="validation-narator text-danger"></small>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="tanggal_terbit" class="form-label">Tanggal Terbit <span class="text-danger">*</span></label>
                                                    <input class="form-control" type="date" name="tanggal_terbit" id="tanggal_terbit" value="{{ $buku->tanggal_terbit }}" required>
                                                    <small class="validation-tanggal_terbit text-danger"></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-10 col-md-12">
                                            <div>
                                                <div class="mb-3">
                                                    <label for="cover_id" class="form-label">Unggah Sampul Buku</label>
                                                    <input class="form-control" type="file" name="cover_id" id="cover_id">
                                                    <small class="validation-cover_id text-danger">Catatan: Jika tidak ada perubahan pada sampul buku, silakan biarkan kolom ini kosong.</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-12">
                                            <div class="text-center">
                                                <span class='text-danger' style="font-size:11px">Sampul saat ini</span>
                                                <div class="mb-3 d-flex justify-content-center">
                                                    @if ($buku->cover)
                                                        <a href="{{ asset('storage/' . $buku->cover->file_path) }}" title="Lihat Sampul Buku">
                                                            <img src="{{ asset('storage/' . $buku->cover->file_path) }}" alt="Foto Kartu Identitas" style="width: 40px;">
                                                        </a>
                                                    @else
                                                        <a href="{{ asset('web_assets/images/books/grid/not-found-book.png') }}" title="Lihat Sampul Buku">
                                                            <img src="{{ asset('web_assets/images/books/grid/not-found-book.png') }}" alt="Foto Kartu Identitas" style="width: 40px;">
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @if ($buku->jenis == 'Buku Audio')
                                            <div class="col-lg-6 col-md-12">
                                                <div>
                                                    <div class="mb-3">
                                                        <label for="file_audio_id" class="form-label">Unggah File Audio</label>
                                                        <input class="form-control" type="file" name="file_audio_id" id="file_audio_id">
                                                        <small class="validation-file_audio_id text-danger">Catatan: Jika tidak ada perubahan pada audio buku, silakan biarkan kolom ini kosong.</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-12">
                                                <div class="text-center">
                                                    <span class='text-danger' style="font-size:11px">Audio saat ini</span>
                                                    <div class="mb-3 d-flex justify-content-center">
                                                        <audio controls class="w-100" title="Dengarkan Audio Buku">
                                                            <source src="{{ asset('storage/' . $buku->audio->file_path) }}" type="audio/mpeg"/>
                                                        </audio>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="col-lg-12">
                                            <div class="row">
                                                <div class="col-lg-2">
                                                    <div class="mb-3">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <label for="is_active" class="form-label">Status <span class="text-danger">*</span></label>
                                                            </div>
                                                            <div class="col-12">
                                                                <input type="checkbox" class="is_active" id="is_activeBukuAudio" name="is_active" switch="none" onclick="formItemBuku('BukuAudio')" {{ $buku->is_active ? 'checked' : '' }} />
                                                                <label for="is_activeBukuAudio" data-on-label="On" data-off-label="Off"></label>
                                                                <input type="hidden" id="is_active_valueBukuAudio" name="is_active_value" value="{{ $buku->is_active ? "true" : "false" }}" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
            `;
        if (val === 'Buku') {
            $('#jenisBuku').html(contentJenisBuku);
            $('#jenisBukuDigital').html('');
            $('#jenisBukuAudio').html('');
            $('#formJudul').removeClass('d-none');
            $('#formDeskripsi').removeClass('d-none');
            $('#btn_s').prop('disabled', false);
        } else if (val === 'Buku Digital') {
            $('#jenisBuku').html('');
            $('#jenisBukuDigital').html(contentJenisBukuDigital);
            $('#jenisBukuAudio').html('');
            $('#formJudul').removeClass('d-none');
            $('#formDeskripsi').removeClass('d-none');
            $('#btn_s').prop('disabled', false);
        } else if (val === 'Buku Audio') {
            $('#jenisBuku').html('');
            $('#jenisBukuDigital').html('');
            $('#jenisBukuAudio').html(contentJenisBukuAudio);
            $('#formJudul').removeClass('d-none');
            $('#formDeskripsi').removeClass('d-none');
            $('#btn_s').prop('disabled', false);
        } else {
            $('#jenisBuku').html('');
            $('#jenisBukuDigital').html('');
            $('#jenisBukuAudio').html('');
            $('#formJudul').addClass('d-none');
            $('#formDeskripsi').addClass('d-none');
            $('#btn_s').prop('disabled', true);
        }
        //OPEN BUKU
            // OPEN TAMBAH FORM ITEM BUKU
            $('#btnTambahItemBuku').on('click', function(event) {
                event.preventDefault();
                let rowNumber = $('.itemBukuContent').length;
                $('#btn_s').prop('disabled', false);

                let newForm = `
                                        <div class='itemBukuContent' id='itemBukuContent${rowNumber}'>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="heading${rowNumber}">
                                                    <button class="accordion-button fw-medium judulItemBuku collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${rowNumber}" aria-expanded="true" aria-controls="collapse${rowNumber}">
                                                        Item Buku #${rowNumber+1}
                                                    </button>
                                                </h2>
                                                <div id="collapse${rowNumber}" class="accordion-collapse collapse" aria-labelledby="heading${rowNumber}" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="row">
                                                            <input type="hidden" class="form-control" id="idItemBuku${rowNumber}" name="idItemBuku[]" value="">
                                                            <hr>
                                                            <div class="d-flex justify-content-end">
                                                                <button type="button" class="btnHapusItemBuku btn btn-sm btn-outline-danger waves-effect waves-light mx-2" id="btnHapusItemBuku${rowNumber}"><i class="fas fa-trash"></i> Hapus Item Buku</button>
                                                            </div>
                                                            <div class="col-lg-12">
                                                                <div>
                                                                    <div class="mb-3">
                                                                        <label for="pengadaan" class="form-label">Pengadaan <span class="text-danger">*</span></label>
                                                                        <select id="pengadaan" style="width:100%" name="pengadaan[]" class="form-select selectpengadaan">
                                                                            <option value="" disabled selected>-- Pilih Pengadaan --</option>
                                                                            @foreach ($pengadaans as $pengadaan)
                                                                                <option value="{{$pengadaan->id}}">{{$pengadaan->nama}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-md-12">
                                                                <div>
                                                                    <div class="mb-3">
                                                                        <label for="harga" class="form-label">Harga <span class="text-danger">*</span></label>
                                                                        <input class="form-control" type="text" placeholder="Masukkan Harga" name="harga[]" id="harga" value="" required>
                                                                        <small class="validation-harga${rowNumber} text-danger"></small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-md-12">
                                                                <div>
                                                                    <div class="mb-3">
                                                                        <label for="kondisi" class="form-label">Kondisi <span class="text-danger">*</span></label>
                                                                        <select id="kondisi" name="kondisi[]" class="form-select">
                                                                            <option value="" disabled selected>-- Pilih Kondisi --</option>
                                                                            <option class="Baik">Baik</option>
                                                                            <option class="Rusak">Rusak</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-12">
                                                                <div class="row">
                                                                    <div class="col-lg-2">
                                                                        <div class="mb-3">
                                                                            <div class="row">
                                                                                <div class="col-12">
                                                                                    <label for="is_active" class="form-label">Status <span class="text-danger">*</span></label>
                                                                                </div>
                                                                                <div class="col-12">
                                                                                    <input type="checkbox" class="is_active" id="is_active${rowNumber}" name="is_active[]" switch="none" onclick="formItemBuku(${rowNumber})" checked />
                                                                                    <label for="is_active${rowNumber}" data-on-label="On" data-off-label="Off"></label>
                                                                                    <input type="hidden" id="is_active_value${rowNumber}" name="is_active_value[]" value="true" />
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-10 d-none" id='keterangan${rowNumber}'>
                                                                        <div class="mb-3">
                                                                            <label for="keterangan_is_active${rowNumber}" class="form-label">Keterangan Status</label>
                                                                            <textarea class="form-control" type="text" placeholder="Masukkan Keterangan" name="keterangan_is_active[]" id="keterangan_is_active${rowNumber}"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                $('#formItemBuku').append(newForm);
                $('.selectpengadaan').select2({
                            // tags: true,
                            // placeholder: 'Masukkan Pengadaan',
                            dropdownParent: $('#modalCenterExtraLarge > .modal-dialog > .modal-content'),
                            ajax: {
                                url: function (params) {
                                    var url = `{{ route('getdata.pengadaans') }}`;
                                    return url + "?search=" + params.term;
                                },
                                dataType: 'json',
                                delay: 250,
                                processResults: function(data) {
                                    return {
                                        results: $.map(data, function(item) {
                                            return {
                                                text: item.nama,
                                                id: item.id
                                            }
                                        })
                                    };
                                },
                                cache: true
                            }
                        });
                updateNomorUrutan();
            });
            // CLOSE TAMBAH FORM ITEM BUKU
            //OPEN HAPUS FORM ITEM BUKU
            $('#formItemBuku').on('click', '.btnHapusItemBuku', function() {
                let rowNumber = $('.itemBukuContent').length;
                if (rowNumber == 1) {
                    $('#btn_s').prop('disabled', true);
                } else if (rowNumber >= 2) {
                    $('#btn_s').prop('disabled', false);
                }
                $(this).closest('.itemBukuContent').remove();
                updateNomorUrutan();
            });
            //CLOSE HAPUS FORM ITEM BUKU
            //OPEN PROSES PERBARUI URUTAN
            function updateNomorUrutan() {
                $('.itemBukuContent').each(function(index) {
                    $(this).find('.judulItemBuku').text(`Item Buku #${index +1}`);
                });
            }
            //CLOSE PROSES PERBARUI URUTAN
        //CLOSE BUKU
        // OPEN select2
            $('#kategori_id').select2({
                // tags: true,
                placeholder: 'Masukkan Sub Kategori',
                dropdownParent: $('#modalCenterExtraLarge > .modal-dialog > .modal-content'),
                ajax: {
                        url: function (params) {
                            var url = `{{ route('getdata.kategoris') }}`;
                            return url + "?search=" + params.term;
                        },
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        text: item.nama,
                                        id: item.id
                                    }
                                })
                            };
                        },
                        cache: true
                    }
            });
            $('#penerbit_id').select2({
                    tags: true,
                    placeholder: 'Masukkan Penerbit',
                    dropdownParent: $('#modalCenterExtraLarge > .modal-dialog > .modal-content'),
                    ajax: {
                        url: function (params) {
                            var url = `{{ route('getdata.penerbit') }}`;
                            return url + "?search=" + params.term;
                        },
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        text: item.namaPenerbit,
                                        id: item.id
                                    }
                                })
                            };
                        },
                        cache: true
                    }
            });
            $('#rak_id').select2({
                    tags: true,
                    placeholder: 'Masukkan Rak',
                    dropdownParent: $('#modalCenterExtraLarge > .modal-dialog > .modal-content'),
                    ajax: {
                        url: function (params) {
                            var url = `{{ route('getdata.raks') }}`;
                            return url + "?search=" + params.term;
                        },
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        text: item.kode,
                                        id: item.id
                                    }
                                })
                            };
                        },
                        cache: true
                    }
            });
            $('#penulis_id').select2({
                tags: true,
            })

            $('.selectpengadaan').select2({
                    // tags: true,
                    // placeholder: 'Masukkan Pengadaan',
                    dropdownParent: $('#modalCenterExtraLarge > .modal-dialog > .modal-content'),
                    ajax: {
                        url: function (params) {
                            var url = `{{ route('getdata.pengadaans') }}`;
                            return url + "?search=" + params.term;
                        },
                        dataType: 'json',
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        text: item.nama,
                                        id: item.id
                                    }
                                })
                            };
                        },
                        cache: true
                    }
                });

            var kategori_id_select = document.getElementById("kategori_id").value;

             //OPEN SUB KATEGORI GET
            $('#kategori_id').on('change', function () {
                kategori_id_select = $(this).val();
                // $('#sub_kategori_id').empty();
                $("#sub_kategori_id").empty().trigger('change')
            });
            //CLOSE SUB KATEGORI GET

            $('#sub_kategori_id').select2({
                placeholder: 'Masukkan Sub Kategori',
                dropdownParent: $('#modalCenterExtraLarge > .modal-dialog > .modal-content'),
                ajax: {
                    url: function (params) {
                        var url = `{{ route('getdata.subkategoris', ':param') }}`.replace(':param', kategori_id_select);
                        return url + "?search=" + params.term;
                    },
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    text: item.nama,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
        // CLOSE select2
        $('#penulis_id').select2({
            tags: true,
            tokenSeparators: [',', ' ']
        })
    });
    //OPEN IS ACTIVE IF FALSE SHOW KETERANGAN
    function formItemBuku(rowNumber) {
        var val = $(`#is_active${rowNumber}`).is(":checked");
        if (val === true) {
            $(`#keterangan${rowNumber}`).addClass('d-none');
            $(`#keterangan${rowNumber}`).removeClass('d-block');
        } else if (val === false) {
            $(`#keterangan${rowNumber}`).removeClass('d-none');
            $(`#keterangan${rowNumber}`).addClass('d-block');
        }
        const checkbox = document.getElementById('is_active' + rowNumber);
        const valueInput = document.getElementById('is_active_value' + rowNumber);

        // Mengubah nilai input sesuai status checkbox
        valueInput.value = checkbox.checked ? 'true' : 'false';
    }
    //CLOSE IS ACTIVE IF FALSE SHOW KETERANGAN
    //OPEN PROSES TAMBAH MANAJEMEN BUKU
    $('#form_edit_rellarphp').on('submit', function(event) {
        event.preventDefault();
        event.stopImmediatePropagation();
        $('#btn_s').prop('disabled', true);
        idata = new FormData($('#form_edit_rellarphp')[0]);
        $.ajax({
            type: "POST",
            url: "{{ route('manajemenbuku.update') }}",
            data: idata,
            processData: false,
            contentType: false,
            cache: false,
            success: function(data) {
                $("#form_edit_rellarphp")[0].reset();
                $("#modalCenterExtraLarge").modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: data.title,
                    text: data.message,
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                }).then(function(result) {
                    if (result.value === true) {
                        window.location.reload();
                    }
                });

            },
            error: function(xhr, status, error) {
                var response = xhr.responseJSON;
                $('#btn_s').prop('disabled', false);
                if (response.messageValidate['judul']) {
                    $('.validation-judul').text(response.messageValidate['judul'][0]);
                } else {
                    $('.validation-judul').text('');
                }
                if (response.messageValidate['kategori_id']) {
                    $('.validation-kategori_id').text(response.messageValidate['kategori_id'][0]);
                } else {
                    $('.validation-kategori_id').text('');
                }
                if (response.messageValidate['penerbit_id']) {
                    $('.validation-penerbit_id').text(response.messageValidate['penerbit_id'][0]);
                } else {
                    $('.validation-penerbit_id').text('');
                }
                if (response.messageValidate['penulis_id']) {
                    $('.validation-penulis_id').text(response.messageValidate['penulis_id'][0]);
                } else {
                    $('.validation-penulis_id').text('');
                }
                if (response.messageValidate['narator']) {
                    $('.validation-narator').text(response.messageValidate['narator'][0]);
                } else {
                    $('.validation-narator').text('');
                }
                if (response.messageValidate['tanggal_terbit']) {
                    $('.validation-tanggal_terbit').text(response.messageValidate['tanggal_terbit'][
                        0
                    ]);
                } else {
                    $('.validation-tanggal_terbit').text('');
                }
                if (response.messageValidate['cover_id']) {
                    $('.validation-cover_id').text(response.messageValidate['cover_id'][0]);
                } else {
                    $('.validation-cover_id').text('');
                }
                if (response.messageValidate['file_audio_id']) {
                    $('.validation-file_audio_id').text(response.messageValidate['file_audio_id'][
                        0
                    ]);
                } else {
                    $('.validation-file_audio_id').text('');
                }
                if (response.messageValidate['isbn']) {
                    $('.validation-isbn').text(response.messageValidate['isbn'][0]);
                } else {
                    $('.validation-isbn').text('');
                }
                if (response.messageValidate['tahun_cetak']) {
                    $('.validation-tahun_cetak').text(response.messageValidate['tahun_cetak'][0]);
                } else {
                    $('.validation-tahun_cetak').text('');
                }
                if (response.messageValidate['jumlah_halaman']) {
                    $('.validation-jumlah_halaman').text(response.messageValidate['jumlah_halaman'][
                        0
                    ]);
                } else {
                    $('.validation-jumlah_halaman').text('');
                }
                if (response.messageValidate['ukuran']) {
                    $('.validation-ukuran').text(response.messageValidate['ukuran'][0]);
                } else {
                    $('.validation-ukuran').text('');
                }
                if (response.messageValidate['kertas_isi']) {
                    $('.validation-kertas_isi').text(response.messageValidate['kertas_isi'][0]);
                } else {
                    $('.validation-kertas_isi').text('');
                }
                if (response.messageValidate['cetak_cover']) {
                    $('.validation-cetak_cover').text(response.messageValidate['cetak_cover'][0]);
                } else {
                    $('.validation-cetak_cover').text('');
                }
                if (response.messageValidate['cetak_isi']) {
                    $('.validation-cetak_isi').text(response.messageValidate['cetak_isi'][0]);
                } else {
                    $('.validation-cetak_isi').text('');
                }
                if (response.messageValidate['rak_id']) {
                    $('.validation-rak_id').text(response.messageValidate['rak_id'][0]);
                } else {
                    $('.validation-rak_id').text('');
                }
                Swal.fire({
                    icon: 'error',
                    title: response.title,
                    text: response.message,
                    customClass: {
                        confirmButton: 'btn btn-danger'
                    }
                });
            }
        });
    });
    //CLOSE PROSES TAMBAH MANAJEMEN BUKU
</script>
