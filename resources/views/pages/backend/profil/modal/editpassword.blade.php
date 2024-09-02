<link href="{{ asset('/') }}admin_assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
<form class="form" id="form_editpassword" enctype="multipart/form-data">
    @csrf
    <div class="modal-body" id="modalCenterLargeContent">
        <div class="row g-3">

            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <label for="password" class="form-label">Password</label>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <input type="text" class="form-control" name="id" id="id" value="{{encrypt($user->id)}}" readonly hidden>
                <div class="input-group">
                    <input class="form-control" name="password" type="password" value="" placeholder="Masukkan Password" id="password" required>
                    <button type="button" id="togglePassword" class="btn btn-outline-secondary">
                        <i class="fa fa-eye-slash" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="validation-password text-danger">
                    <small class="text-muted">
                        Minimal 8 Karakter kombinasi huruf kapital, huruf kecil, angka dan simbol(@$!#%*?&).
                    </small>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <label class="form-label" style="font-weight: bold;font-size:13px;">Ulangi Password</label>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <div class="input-group">
                    <input class="form-control" type="password" value="" placeholder="Masukkan Ulang Password" id="confirmPassword" required>
                    <button type="button" id="toggleConfirmPassword" class="btn btn-outline-secondary">
                        <i class="fa fa-eye-slash" aria-hidden="true"></i>
                    </button>
                </div>
                <div id="msg"></div>
                <div class="invalid-feedback">
                    Password Harus Cocok!
                </div>
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary waves-effect text-left" data-bs-dismiss="modal" aria-label="Close">
            <i class="fa fa-times"></i> &nbsp; Batal
        </button>
        <button type="submit" class="btn btn-sm btn-success waves-effect text-left" id="btn_s" disabled>
            <i class="fa fa-save"></i> &nbsp; Simpan
        </button>
    </div>
</form>

<script src="{{ asset('/') }}admin_assets/libs/sweetalert2/sweetalert2.min.js"></script>
<script>
    $(document).ready(function() {
        //OPEN PASSWORD LIHAT
            var passwordInput = $('#password');
            var togglePasswordButton = $('#togglePassword');

            togglePasswordButton.click(function () {
                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    togglePasswordButton.html('<i class="fa fa-eye" aria-hidden="true"></i>');
                } else {
                    passwordInput.attr('type', 'password');
                    togglePasswordButton.html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
                }
            });
        //CLOSE PASSWORD LIHAT
        //OPEN PASSWORD CONFIRM LIHAT
            var confirmPasswordInput = $('#confirmPassword');
            var toggleConfirmPasswordButton = $('#toggleConfirmPassword');

            toggleConfirmPasswordButton.click(function () {
                if (confirmPasswordInput.attr('type') === 'password') {
                    confirmPasswordInput.attr('type', 'text');
                    toggleConfirmPasswordButton.html('<i class="fa fa-eye" aria-hidden="true"></i>');
                } else {
                    confirmPasswordInput.attr('type', 'password');
                    toggleConfirmPasswordButton.html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
                }
            });
        //CLOSE PASSWORD LIHAT
        // OPEN VALIDATION CONFIRM PASSWORD (COCOK/TIDAK COCOK)
            $("#confirmPassword").keyup(function() {
                if ($("#password").val() != $("#confirmPassword").val()) {
                    $("#msg").html("Password Tidak Sesuai").css("color", "red");
                    $("#btn_s").prop('disabled', true);
                } else {
                    $("#msg").html("Password Sesuai").css("color", "green");
                    $("#btn_s").prop('disabled', false);
                }
            });
        // CLOSE VALIDATION CONFIRM PASSWORD (COCOK/TIDAK COCOK)
    });
    $('#form_editpassword').on('submit', function(event) {
        event.preventDefault();
        event.stopImmediatePropagation();

        // Pemeriksaan password
        if ($("#password").val() != $("#confirmPassword").val()) {
            $("#msg").html("Password Tidak Sesuai").css("color", "red");
            return;
        }

        idata = new FormData($('#form_editpassword')[0]);
        $.ajax({
            type: "POST",
            url: "{{ route('profilParam.updatepassword') }}",
            data: idata,
            processData: false,
            contentType: false,
            cache: false,
            success: function(data) {
                $("#form_editpassword")[0].reset();
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
                    if(response.messageValidate['password']){
                        $('.validation-password').text(response.messageValidate['password'][0]);
                    }
                    else{
                        $('.validation-password').text('');
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
