<form class="form" id="form_ubah">
    @csrf
    <div class="modal-body" id="modalCenterContent">
        <div class="row g-3">

            <div class="col-lg-12">
                <div>
                    <input type="hidden" class="form-control" id="id" name="id" value="{{encrypt($peminjaman->id)}}" readonly style="background-color: #e9e9ef;">
                    <div class="mb-3">
                        <label for="profil_anggota_id" class="form-label">Nomor & Nama Lengkap Anggota Peminjaman <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="profil_anggota_id" name="profil_anggota_id" value="{{$peminjaman->profilAnggota->nomor_anggota.'-'.$peminjaman->profilAnggota->nama_lengkap}}" disabled style="background-color: #e9e9ef;">
                    </div>
                    <div class="mb-3">
                        <label for="item_bukus_id" class="form-label">Kode & Judul Buku <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="item_bukus_id" name="item_bukus_id" value="{{$peminjaman->itemBuku->buku->subKategori->kode. ' '. strtoupper(substr($peminjaman->itemBuku->buku->penulises[0]->nama, 0, 3)). ' '.strtolower(substr($peminjaman->itemBuku->buku->penerbit->namaPenerbit, 0, 1))}}" disabled style="background-color: #e9e9ef;">
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div>
                    <div class="mb-3">
                        <label for="tanggal_pengambilan_pinjaman" class="form-label">Tanggal Peminjaman <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_pengambilan_pinjaman" id="tanggal_pengambilan_pinjaman" class="form-control" value="{{ $peminjaman->tanggal_pengambilan_pinjaman ?? null }}" readonly style="background-color: #e9e9ef;">
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_batas_pinjaman" class="form-label">Tanggal Batas Kembali <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_batas_pinjaman" id="tanggal_batas_pinjaman" class="form-control" value="{{ $peminjaman->tanggal_batas_pinjaman ?? null }}" readonly style="background-color: #e9e9ef;">
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div>
                    <div class="mb-3">
                        <label for="tanggal_pengembalian_pinjaman" class="form-label">Tanggal Pengembalian<span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_pengembalian_pinjaman" id="tanggal_pengembalian_pinjaman" class="form-control" value="{{$peminjaman->tanggal_pengembalian_pinjaman ?? null}}" disabled style="background-color: #e9e9ef;">
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select" name="status" id="status">
                            <option value="" disabled selected>-- Pilih Status --</option>
                            <option {{$peminjaman->status == 'Sedang Dipinjam' ? 'selected' : ''}} {{$peminjaman->status == 'Belum Kembali' || $peminjaman->status == 'Sudah Kembali'  ? 'disabled' : ''}} value="Sedang Dipinjam">Sedang Dipinjam</option>{{-- JIKA BUKU SEDANG DALAM WAKTU PINJAMAN --}}
                            <option {{$peminjaman->status == 'Belum Kembali' ? 'selected' : ''}} {{$peminjaman->status == 'Sudah Kembali'|| $peminjaman->status == 'Sudah Dipinjam' ? 'disabled' : ''}} value="Belum Kembali">Belum Kembali</option>{{-- JIKA BUKU SUDAH DILUAR WAKTU PINJAMAN --}}
                            <option {{$peminjaman->status == 'Sudah Kembali' ? 'selected' : ''}} value="Sudah Kembali">Sudah Kembali</option>{{-- JIKA BUKU SUDAH DIKEMBALIKAN --}}
                        </select>
                        <small class="validation-status text-danger"></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary waves-effect text-left" data-bs-dismiss="modal" aria-label="Close">
            <i class="fa fa-times"></i> &nbsp; Batal
        </button>
        <button type="submit" class="btn btn-sm btn-success waves-effect text-left" id="btn_s">
            <i class="fa fa-save"></i> &nbsp; Simpan
        </button>
    </div>
</form>

<script>
    $(document).ready(function() {

    });
    //Progres tambah
    $('#form_ubah').on('submit', function(event) {
        event.preventDefault();
        event.stopImmediatePropagation();
        idata = new FormData($('#form_ubah')[0]);
        $.ajax({
            type: "POST",
            url: "{{ route('daftarpeminjaman.update') }}",
            data: idata,
            processData: false,
            contentType: false,
            cache: false,
            success: function(data) {
                $("#form_ubah")[0].reset();
                $("#modalCenter").modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: data.title,
                    text: data.message,
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                }).then(function(result) {
                    if(result.value === true) {
                        window.location.reload();
                    }
                });
            },
            error: function(xhr, status, error) {
                var response = xhr.responseJSON;
                if(response.messageValidate['status']){
                    $('.validation-status').text(response.messageValidate['status'][0]);
                }
                else{
                    $('.validation-status').text('');
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
</script>
