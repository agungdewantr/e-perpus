<form class="form" id="form_edit_rellarphp" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="modal-body" id="modalCenterLargeContent">
        <div class="row g-3">
            <div class="col-lg-4 col-12">
                <label for="gambar" class="form-label">Gambar <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-8 col-12">
                <div class="input-group">
                    <input type="file" class="form-control" name="gambar" id="gambar">
                    <a class="btn btn-outline-secondary" href="{{ asset('storage/' . $agenda->gambar->file_path) }}"
                        target="_blank" noopener noreferer><i class="fas fa-eye"></i></a>
                    <div class="invalid-feedback" id="gambarError"></div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <label for="judul" class="form-label">Judul <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-8 col-12">
                <input class="form-control" type="text" name="judul" id="judul" required placeholder="Judul"
                    value="{{ $agenda->judul }}">
                <div class="invalid-feedback" id="judulError"></div>
            </div>
            <div class="col-lg-4 col-12">
                <label for="deskripsi" class="form-label">Deskripsi <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-8 col-12">
                <textarea name="deskripsi" id="deskripsi" cols="30" rows="3" placeholder="Deskripsi"
                    class="form-control">{{ $agenda->deskripsi }}</textarea>
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
                    value="{{ \Carbon\Carbon::parse($agenda->created_at)->toDateString() }}">
                <div class="invalid-feedback" id="tanggalError"></div>
            </div>
            <div class="col-lg-4 col-12">
                <label for="waktu" class="form-label">Waktu <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-8 col-12">
                <div class="d-flex align-items-center">
                    <div class="input-group me-3">
                        <input class="form-control" type="time" name="waktu_mulai" id="waktuMulai" required
                            placeholder="Waktu"
                            value="{{ \Carbon\Carbon::parse($agenda->waktu_mulai)->isoFormat('HH:mm') }}">
                        <span class="input-group-text">WITA</span>
                        <div class="invalid-feedback" id="waktuMulaiError"></div>
                    </div>
                    <span>-</span>
                    <div class="input-group ms-3">
                        <input class="form-control" type="time" name="waktu_selesai" id="waktuSelesai"
                            placeholder="Waktu"
                            value="{{ \Carbon\Carbon::parse($agenda->waktu_selesai)->isoFormat('HH:mm') }}">
                        <span class="input-group-text">WITA</span>
                        <div class="invalid-feedback" id="waktuSelesaiError"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <label for="lokasi" class="form-label">Lokasi <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-8 col-12">
                <input class="form-control" type="text" name="lokasi" id="lokasi" required placeholder="Lokasi"
                    value="{{ $agenda->lokasi }}">
                <div class="invalid-feedback" id="lokasiError"></div>
            </div>
            {{-- <div class="col-lg-4 col-12">
                <label for="peserta" class="form-label">Peserta <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-8 col-12">
                <input class="form-control" type="text" name="peserta" id="peserta" placeholder="Peserta"
                    value="{{ $agenda->peserta }}">
                <div class="invalid-feedback" id="pesertaError"></div>
            </div> --}}
            <div class="col-lg-4 col-12">
                <label for="" class="form-label">Aktif <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-8 col-12">
                <input class="form-control" type="checkbox" id="switch1" name="is_active" switch="none"
                    {{ $agenda->is_active ? 'checked' : '' }} />
                <label for="switch1" data-on-label="On" data-off-label="Off"></label>
                <div class="invalid-feedback" id="tanggalError"></div>
            </div>
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
    $('#form_edit_rellarphp').on('submit', function(event) {
        event.preventDefault();
        event.stopImmediatePropagation();
        idata = new FormData($('#form_edit_rellarphp')[0]);
        $.ajax({
            type: "POST",
            url: "{{ route('acara.update', $agenda->id) }}",
            data: idata,
            processData: false,
            contentType: false,
            cache: false,
            success: function(data) {
                $("#form_edit_rellarphp")[0].reset();
                $("#modalCenterLarge").modal('hide');
                $("#table_rak_rellarphp").DataTable().draw()
                Swal.fire({
                    icon: 'success',
                    title: data.title,
                    text: data.message,
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                })
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
