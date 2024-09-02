<form class="form" id="form_edit_rellarphp" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="modal-body" id="modalCenterLargeContent">
        <div class="row g-3">
            <div class="col-lg-4 col-12">
                <label for="judul" class="form-label">Judul <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-8 col-12">
                <input class="form-control" type="text" name="judul" id="judul" required placeholder="Judul"
                    value="{{ $prosedur->judul }}">
                <div class="invalid-feedback" id="judulError"></div>
            </div>
            <div class="col-lg-4 col-12">
                <label for="dokumen" class="form-label">File Prosedur <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-8 col-12">
                <div class="input-group">
                    <input type="file" class="form-control" name="dokumen" id="dokumen">
                    <a class="btn btn-outline-secondary" href="{{ asset('storage/' . $prosedur->dokumen->file_path) }}"
                        target="_blank" noopener noreferer><i class="fas fa-eye"></i></a>
                    <div class="invalid-feedback" id="dokumenError"></div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <label for="" class="form-label">Aktif <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-8 col-12">
                <input class="form-control" type="checkbox" id="switch1" name="is_active" switch="none"
                    {{ $prosedur->is_active ? 'checked' : '' }} />
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
            url: "{{ route('prosedur.update', $prosedur->id) }}",
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
                if (response.errors.dokumen) {
                    $('#dokumen').addClass('is-invalid')
                    $('#dokumenError').text(response.errors.dokumen[0]);
                }
                if (response.errors.judul) {
                    $('#judul').addClass('is-invalid')
                    $('#judulError').text(response.errors.judul[0]);
                }
            }
        });
    });
</script>
