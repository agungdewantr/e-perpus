<form class="form" id="form_tambah">
    @csrf
    <div class="modal-body" id="modalCenterContent">
        <div class="row g-3">
            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <label for="kode" class="form-label">Kategori <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <select class="form-select" id="kategori_id" nama="kategori_id">
                    <option value="" disabled selected>-- Pilih Kategori --</option>
                    @foreach ($kategories as $kategori)
                        <option value="{{ $kategori->id }}">{{ $kategori->kode . '-' . $kategori->nama }}</option>
                    @endforeach
                </select>
                <div class="validation-kategori_id text-danger"></div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <label for="kode" class="form-label">Kode <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <input type="text" class="form-control" name="kode" id="kode" value=""
                    placeholder="Masukkan Kode" required>
                <div class="validation-kode text-danger"></div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <label for="nama" class="form-label">Sub Kategori </label>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <textarea class="form-control" name="nama" id="nama" placeholder="Masukkan Kategori"></textarea>
                <div class="validation-nama text-danger"></div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <label for="isActive" class="form-label">Status <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <input type="checkbox" id="switch1" name="isActive" switch="none" checked />
                <label for="switch1" data-on-label="On" data-off-label="Off"></label>
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

<script>
    $(document).ready(function() {

    });
    $('#form_tambah').on('submit', function(event) {
        event.preventDefault();
        event.stopImmediatePropagation();
        data = {
            kategori_id: $("#kategori_id").val(),
            kode: $("#kode").val(),
            nama: $("#nama").val(),
            status: $("#switch1").val(),
            _token: '{{ csrf_token() }}'
        }
        $.ajax({
            type: "POST",
            url: "{{ route('subkategori.store') }}",
            data: data,

            success: function(data) {
                $("#form_tambah")[0].reset();
                $("#modalCenter").modal('hide');
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
                if (response.messageValidate['nama']) {
                    $('.validation-nama').text(response.messageValidate['nama'][0]);
                } else {
                    $('.validation-nama').text('');
                }
                if (response.messageValidate['kode']) {
                    $('.validation-kode').text(response.messageValidate['kode'][0]);
                } else {
                    $('.validation-kode').text('');
                }
                if (response.messageValidate['kategori_id']) {
                    $('.validation-kategori_id').text(response.messageValidate['kategori_id'][0]);
                } else {
                    $('.validation-kategori_id').text('');
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
