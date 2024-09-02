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
                    <a class="btn btn-outline-secondary" href="{{ asset('storage/' . $news->gambar->file_path) }}"
                        target="_blank" noopener noreferer><i class="fas fa-eye"></i></a>
                    <div class="invalid-feedback" id="gambarError"></div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <label for="judul" class="form-label">Judul <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-8 col-12">
                <input class="form-control" type="text" name="judul" id="judul" required placeholder="Judul"
                    value="{{ $news->judul }}">
                <div class="invalid-feedback" id="judulError"></div>
            </div>
            <div class="col-lg-4 col-12">
                <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-8 col-12">
                <input class="form-control" type="date" placeholder="Tanggal" name="tanggal" id="tanggal" required
                    value="{{ $news->created_at->format('Y-m-d') }}">
                <div class="invalid-feedback" id="tanggalError"></div>
            </div>
            <div class="col-lg-4 col-12">
                <label for="" class="form-label">Aktif <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-8 col-12">
                <input class="form-control" type="checkbox" id="switch1" name="is_active" switch="none"
                    {{ $news->is_active ? 'checked' : '' }} />
                <label for="switch1" data-on-label="On" data-off-label="Off"></label>
                <div class="invalid-feedback" id="tanggalError"></div>
            </div>
            <div class="col-lg-4 col-12">
                <label for="deskripsi" class="form-label">Deskripsi <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-8 col-12">
                <textarea name="deskripsi" id="deskripsi" cols="30" rows="3" placeholder="Deskripsi" required
                    class="form-control">{{ $news->deskripsi }}</textarea>
                <div class="invalid-feedback" id="deskripsiError"></div>
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
            url: "{{ route('berita.update', $news->id) }}",
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
            }
        });
    });
</script>
