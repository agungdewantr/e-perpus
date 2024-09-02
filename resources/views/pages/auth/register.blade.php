<div class="row mt-4">
    <h4 class="text-center text-black mb-2">Daftar</h4>
    <form class="form" id="form_daftar">
        @csrf
        <div class="mb-3">
            <label class="form-label">Username <span class="text-danger">*</span></label>
            <input type="text" class="form-control text-black" id="username" name="username" placeholder="Masukkan Username" required>
            <div  style="font-size: 11px;" class="validation-username text-danger"></div>
        </div>
        <div class="mb-3">
            <label class="form-label">Email </label>
            <input type="email" class="form-control text-black" id="email" name="email" placeholder="Masukkan Email">
            <div  style="font-size: 11px;" class="validation-email text-danger"></div>
        </div>
        <div class="mb-3">
            <label class="form-label">Password <span class="text-danger">*</span></label>

            <div class="input-group">
                <input class="form-control text-black" name="password" type="password" value="" placeholder="Masukkan Password" id="password" required>
                <button type="button" id="togglePassword" class="btn btn-outline-light text-black" style="border: 1px solid #d6d6d6;">
                    <i class="fa fa-eye-slash" aria-hidden="true"></i>
                </button>
            </div>
            <div  style="font-size: 11px; margin-top:-11px;" class="validation-password text-danger">
                <span class="text-muted">
                    Minimal 8 Karakter kombinasi huruf kapital, huruf kecil, angka dan simbol(@$!#%*?&).
                </span>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
            <div class="input-group">
                <input class="form-control text-black" name="ulangi_password" type="password" value="" placeholder="Masukkan Password" id="ulangi_password" required>
                <button type="button" id="toggleUlangiPassword" class="btn btn-outline-light text-black" style="border: 1px solid #d6d6d6;">
                    <i class="fa fa-eye-slash" aria-hidden="true"></i>
                </button>
            </div>
            <div id="msg"></div>
        </div>
        <div class="mb-3">
            <button class="btn btn-primary w-100" type="submit" id='button_simpan'>{{ __('Daftar') }}</button>
        </div>
    </form>
</div>
<div class="row">
    <div class="text-center">
        <p class="text-muted mb-0">Sudah memiliki akun ?
            <button class="btn btn-link text-primary fw-semibold" data-bs-target="#modalLogin" onclick="show_login()"> Klik Masuk </button>
        </p>
    </div>
</div>
    <script src="{{ asset('/') }}web_assets/js/jquery.min.js"></script><!-- JQUERY MIN JS -->

    <script type="text/javascript">
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
            //OPEN PASSWORD LIHAT
                var ulangiPasswordInput = $('#ulangi_password');
                var toggleUlangiPasswordButton = $('#toggleUlangiPassword');

                toggleUlangiPasswordButton.click(function () {
                    if (ulangiPasswordInput.attr('type') === 'password') {
                        ulangiPasswordInput.attr('type', 'text');
                        toggleUlangiPasswordButton.html('<i class="fa fa-eye" aria-hidden="true"></i>');
                    } else {
                        ulangiPasswordInput.attr('type', 'password');
                        toggleUlangiPasswordButton.html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
                    }
                });
            //CLOSE PASSWORD LIHAT
            //OPEN CONFIRM PASSWORD
                $("#ulangi_password").keyup(function() {
                    if ($("#password").val() != $("#ulangi_password").val()) {
                        $("#msg").html(`
                                        <div class="text-danger" style="font-size: 11px; margin-top: -13px;">
                                            Password Tidak Sesuai!
                                        </span>
                        `);
                        $("#button_simpan").prop('disabled', true);
                    } else {
                        $("#msg").html(`
                                            <div class="text-success" style="font-size: 11px; margin-top: -13px;">
                                                Password Telah Sesuai!
                                            </span>
                                        `);
                        $("#button_simpan").prop('disabled', false);
                    }
                });
            //CLOSE CONFIRM PASSWORD
            $('#form_daftar').on('submit', function(event) {
                event.preventDefault();
                event.stopImmediatePropagation();

                // Pemeriksaan password
                if ($("#password").val() != $("#ulangi_password").val()) {
                    $("#msg").html( `
                                        <div class="text-danger" style="font-size: 11px; margin-top: -13px;">
                                            Password Tidak Sesuai!
                                        </span>
                    `);
                    return;
                }

                idata = new FormData($('#form_daftar')[0]);
                $.ajax({
                    type: "POST",
                    url: "{{ route('proses.register') }}",
                    data: idata,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function(data) {
                        // $('#tableUser').DataTable().ajax.reload();
                        $("#form_daftar")[0].reset();
                        // $("#modalLogin").modal('hide');
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        var response = xhr.responseJSON;
                        if(response.messageValidate['username']){
                            $('.validation-username').text(response.messageValidate['username'][0]);
                        }
                        else{
                            $('.validation-username').text('');
                        }
                        if(response.messageValidate['email']){
                            $('.validation-email').text(response.messageValidate['email'][0]);
                        }
                        else{
                            $('.validation-email').text('');
                        }
                        if(response.messageValidate['password']){
                            $('.validation-password').text(response.messageValidate['password'][0]);
                            $("#msg").html( ``);
                        }
                        else{
                            $('.validation-password').text('');
                        }
                    }
                });
            });
        });
    </script>
