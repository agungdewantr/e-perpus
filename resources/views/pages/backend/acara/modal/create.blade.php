<form class="form" id="form_tambah_rellarphp" enctype="multipart/form-data">
    @csrf
    <div class="modal-body" id="modalCenterLargeContent">
        <div class="row g-3">
            <div class="col-lg-4 col-12">
                <label for="gambar" class="form-label">Gambar <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-8 col-12">
                <input type="file" class="form-control" name="gambar" id="gambar" required>
                <div class="invalid-feedback" id="gambarError"></div>
            </div>
            <div class="col-lg-4 col-12">
                <label for="judul" class="form-label">Judul <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-8 col-12">
                <input class="form-control" type="text" name="judul" id="judul" required placeholder="Judul">
                <div class="invalid-feedback" id="judulError"></div>
            </div>
            <div class="col-lg-4 col-12">
                <label for="deskripsi" class="form-label">Deskripsi</label>
            </div>
            <div class="col-lg-8 col-12">
                <textarea name="deskripsi" id="deskripsi" cols="30" rows="3" placeholder="Deskripsi" class="form-control"></textarea>
                <div id="deskripsiError">
                    <small class="text-muted">Maksimal 100 karakter</small>
                    <div  class="invalid-feedback"></div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-8 col-12">
                <input class="form-control" type="date" placeholder="Tanggal" name="tanggal" id="tanggal" required
                     value="{{ \Carbon\Carbon::now()->toDateString() }}">
                <div class="invalid-feedback" id="tanggalError"></div>
            </div>
            <div class="col-lg-4 col-12">
                <label for="waktu" class="form-label">Waktu <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-8 col-12">
                <div class="d-flex align-items-center">
                    <div class="input-group me-3">
                        <input class="form-control" type="time" name="waktu_mulai" id="waktuMulai" required
                            placeholder="Waktu">
                        <span class="input-group-text">WITA</span>
                        <div class="invalid-feedback" id="waktuMulaiError"></div>
                    </div>
                    <span>-</span>
                    <div class="input-group ms-3">
                        <input class="form-control" type="time" name="waktu_selesai" id="waktuSelesai"
                            placeholder="Waktu">
                        <span class="input-group-text">WITA</span>
                        <div class="invalid-feedback" id="waktuSelesaiError"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <label for="lokasi" class="form-label">Lokasi <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-8 col-12">
                <input class="form-control" type="text" name="lokasi" id="lokasi" required placeholder="Lokasi">
                <div class="invalid-feedback" id="lokasiError"></div>
            </div>
            {{-- <div class="col-lg-4 col-12">
                <label for="peserta" class="form-label">Peserta</label>
            </div>
            <div class="col-lg-8 col-12">
                <input class="form-control" type="text" name="peserta" id="peserta" placeholder="Peserta">
                <div class="invalid-feedback" id="pesertaError"></div>
            </div> --}}
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary waves-effect text-left" data-bs-dismiss="modal"
            aria-label="Close">
            <i class="fa fa-times"></i> &nbsp; Batal
        </button>
        <button type="submit" class="btn btn-sm btn-success waves-effect text-left" id="btn_s">
            <i class="fa fa-save"></i> &nbsp; Simpan
        </button>
    </div>
</form>
<script type="text/javascript">
    $('#form_tambah_rellarphp').on('submit', function(event) {
        event.preventDefault();
        event.stopImmediatePropagation();
        idata = new FormData($('#form_tambah_rellarphp')[0]);
        $.ajax({
            type: "POST",
            url: "{{ route('acara.store') }}",
            data: idata,
            processData: false,
            contentType: false,
            cache: false,
            success: function(data) {
                $("#form_tambah_rellarphp")[0].reset();
                $("#modalCenterLarge").modal('hide');
                $("#table_rak_rellarphp").DataTable().draw()
                Swal.fire({
                    icon: 'success',
                    title: data.title,
                    text: data.message,
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                });
            },
            error: function(xhr, status, error) {
                var response = xhr.responseJSON;
                if (response.errors.deskripsi) {
                    $('#deskripsi').addClass('is-invalid')
                    $('#deskripsiError').text(response.errors.deskripsi[0]);
                }
                if (response.errors.gambar) {
                    $('#gambar').addClass('is-invalid')
                    $('#gambarError').text(response.errors.gambar[0]);
                }
                if (response.errors.tanggal) {
                    $('#tanggal').addClass('is-invalid')
                    $('#tanggalError').text(response.errors.tanggal[0]);
                }
                if (response.errors.judul) {
                    $('#judul').addClass('is-invalid')
                    $('#judulError').text(response.errors.judul[0]);
                }
                if (response.errors.waktu_mulai) {
                    $('#waktuMulai').addClass('is-invalid')
                    $('#waktuMulaiError').text(response.errors.waktu_mulai[0]);
                }
                if (response.errors.waktu_selesai) {
                    $('#waktuSelesai').addClass('is-invalid')
                    $('#waktuSelesaiError').text(response.errors.waktu_selesai[0]);
                }
                if (response.errors.lokasi) {
                    $('#lokasi').addClass('is-invalid')
                    $('#lokasiError').text(response.errors.lokasi[0]);
                }
                if (response.errors.peserta) {
                    $('#peserta').addClass('is-invalid')
                    $('#pesertaError').text(response.errors.peserta[0]);
                }
            }
        });
    });
</script>
