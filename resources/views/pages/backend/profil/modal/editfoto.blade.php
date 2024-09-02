<link href="{{ asset('/') }}admin_assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
<form class="form" id="form_editfoto" enctype="multipart/form-data">
    @csrf
    <div class="modal-body" id="modalCenterLargeContent">
        <div class="row g-3">
            <div class="col-lg-10 col-md-12">
                <input type="hidden" name="id" id="id" value="{{encrypt($user->id)}}" readonly/>
                <div class="mb-3">
                    <label for="foto_id" class="form-label">Unggah Foto <span class="text-danger">*</span></label>
                    <input class="form-control" type="file" name="foto_id" id="foto_id" required>
                    <small class="validation-foto_id text-danger"></small>
                </div>
            </div>
            <div class="col-lg-2 col-md-12">
                <div class="mb-3">
                    @if($user->foto()->exists())
                        <img src="{{ asset('storage/'.$user->foto->file_path)}}" alt="Foto Profil" style="width: 100px;">
                    @else
                        File Not Found
                    @endif
                </div>
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary waves-effect text-left" data-bs-dismiss="modal" aria-label="Close">
            <i class="fa fa-times"></i> &nbsp; Batal
        </button>
        <button type="submit" class="btn btn-sm btn-success waves-effect text-left" >
            <i class="fa fa-save"></i> &nbsp; Simpan
        </button>
    </div>
</form>

<script src="{{ asset('/') }}admin_assets/libs/sweetalert2/sweetalert2.min.js"></script>
<script>
    $(document).ready(function() {
    });
    $('#form_editfoto').on('submit', function(event) {
        event.preventDefault();
        event.stopImmediatePropagation();

        idata = new FormData($('#form_editfoto')[0]);
        $.ajax({
            type: "POST",
            url: "{{ route('profilParam.updatefoto') }}",
            data: idata,
            processData: false,
            contentType: false,
            cache: false,
            success: function(data) {
                $("#form_editfoto")[0].reset();
                $("#modalCenterLarge").modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: data.title,
                    text: data.message,
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                }).then(function(result) {
                    if (result.value === true){
                        window.location.reload();
                    }
                });
            },
            error: function(xhr, status, error) {
                var response = xhr.responseJSON;
                    if(response.messageValidate['foto_id']){
                        $('.validation-foto_id').text(response.messageValidate['foto_id'][0]);
                    }
                    else{
                        $('.validation-foto_id').text('');
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
