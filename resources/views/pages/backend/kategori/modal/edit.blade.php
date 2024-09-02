<form class="form" id="form_edit" enctype="multipart/form-data">
    @csrf
    <div class="modal-body" id="modalCenterContent">
        <input type="hidden" class="form-control" name="id" id="id" value="{{encrypt($kategori->id)}}" placeholder="" required>
        <div class="row g-3">

            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <label for="kode" class="form-label">kode</label>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <input type="text" class="form-control" name="kode" id="kode" value="{{$kategori->kode}}" placeholder="Masukkan Kode" required>
                <div class="validation-kode text-danger"></div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <label for="nama" class="form-label">Kategori </label>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <textarea class="form-control" name="nama" id="nama" placeholder="Masukkan Kategori">{{$kategori->nama ?? ''}}</textarea>
                <div class="validation-nama text-danger"></div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <label for="isActive" class="form-label">Status</label>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <input type="checkbox" id="switch1" name="isActive" switch="none" {{$kategori->is_active ? 'checked' : ''}} />
                <label for="switch1" data-on-label="On" data-off-label="Off"></label>
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
    $('#form_edit').on('submit', function(event) {
        event.preventDefault();
        event.stopImmediatePropagation();
        idata = new FormData($('#form_edit')[0]);
        console.log(idata);
        $.ajax({
            type: "POST",
            url: "{{ route('kategori.update') }}",
            data: idata,
            processData: false,
            contentType: false,
            cache: false,
            success: function(data) {
                $("#form_edit")[0].reset();
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
                if(response.messageValidate['nama']){
                    $('.validation-nama').text(response.messageValidate['nama'][0]);
                }
                else{
                    $('.validation-nama').text('');
                }
                if(response.messageValidate['kode']){
                    $('.validation-kode').text(response.messageValidate['kode'][0]);
                }
                else{
                    $('.validation-kode').text('');
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
