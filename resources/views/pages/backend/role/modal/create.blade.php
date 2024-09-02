<link href="{{ asset('/') }}admin_assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
<form class="form" id="form_tambah" enctype="multipart/form-data">
    @csrf
    <div class="modal-body" id="modalCenterContent">
        <div class="row g-3">

            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <label for="role" class="form-label">Role</label>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <input type="text" class="form-control" name="role" id="role" value="" placeholder="Masukkan Role" required>
                    <div class="validation-role text-danger"></div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <label for="isActive" class="form-label">Status</label>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <input type="checkbox" id="switch1" name="isActive" switch="none" checked />
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

<script src="{{ asset('/') }}admin_assets/libs/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="{{ asset('/') }}admin_assets/libs/sweetalert2/sweetalert2.min.js"></script>
<script>
    $(document).ready(function() {

    });
    $('#form_tambah').on('submit', function(event) {
        event.preventDefault();
        event.stopImmediatePropagation();
        idata = new FormData($('#form_tambah')[0]);
        $.ajax({
            type: "POST",
            url: "{{ route('role.store') }}",
            data: idata,
            processData: false,
            contentType: false,
            cache: false,
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
                    if(result.value === true) {
                        window.location.reload();
                    }
                });
            },
            error: function(xhr, status, error) {
                var response = xhr.responseJSON;
                if(response.messageValidate['role']){
                    $('.validation-role').text(response.messageValidate['role'][0]);
                }
                else{
                    $('.validation-role').text('');
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
