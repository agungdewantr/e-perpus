    {{-- OPEN GANTI PASSWORD --}}
            <div class="shop-bx-title clearfix">
                <h5 class="text-uppercase">Ganti Password</h5>
            </div>
            <form class="form" id="form_ganti_password_anggota_rellarphp" enctype="multipart/form-data">
                @csrf
                <input type="hidden" class="form-control" name="id" id="id" value="{{encrypt($user->id)}}" readonly>
                <div class="row m-b30">
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="password_lama" class="form-label">Password Lama <span class="text-danger"> *</span></label>

                            <div class="input-group">
                                <input type="password" class="form-control text-black" name="password_lama" id="password_lama" placeholder="Masukkan Password Lama" tabindex="1" required value="">
                                <button type="button" id="togglePasswordLama" class="btn btn-outline-light text-black" style="border: 1px solid #d6d6d6;">
                                    <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                </button>
                            </div>
                            <small class="validation-password_lama text-danger"></small>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="password_baru" class="form-label">Password Baru <span class="text-danger"> *</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control text-black" name="password_baru" id="password_baru" placeholder="Masukkan Password Baru" tabindex="2" required value="">
                                <button type="button" id="togglePasswordBaru" class="btn btn-outline-light text-black" style="border: 1px solid #d6d6d6;">
                                    <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                </button>
                            </div>
                            <small class="validation-password_baru text-danger"></small>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="konfirmasi_password_baru" class="form-label">Konfirmasi Password Baru <span class="text-danger"> *</span></label>
                            <div class="input-group">
                            <input type="password" class="form-control text-black" name="konfirmasi_password_baru" id="konfirmasi_password_baru" placeholder="Masukkan Konfirmasi Password Baru" tabindex="3" required value="">
                            <button type="button" id="togglePasswordKonfirmasi" class="btn btn-outline-light text-black" style="border: 1px solid #d6d6d6;">
                                <i class="fa fa-eye-slash" aria-hidden="true"></i>
                            </button>
                        </div>
                            <small class="validation-konfirmasi_password_baru text-danger"></small>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-danger btnhover mx-2">Batal</button>
                    <button type="submit" class="btn btn-success btnhover">Simpan Perubahan</button>
                </div>
            </form>
    {{-- CLOSE GANTI PASSWORD --}}
    <script>
        //OPEN GANTI PASSWORD
            $('#form_ganti_password_anggota_rellarphp').on('submit', function(event) {
                event.preventDefault();
                idata = new FormData($('#form_ganti_password_anggota_rellarphp')[0]);
                $.ajax({
                    type: 'POST',
                    url: "{{ route('detailanggota.gantiPassword')}}",
                    data: idata,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        swal.fire({
                            icon: data.status,
                            title: data.title,
                            text: data.message,
                        }).then(function () {
                            window.location.reload();
                        });
                    },error: function(xhr, status, error) {
                        var response = xhr.responseJSON;
                        if(response.messageValidate['password_lama']){
                            $('.validation-password_lama').text(response.messageValidate['password_lama'][0]);
                        }
                        else{
                            $('.validation-password_lama').text('');
                        }
                        if(response.messageValidate['password_baru']){
                            $('.validation-password_baru').text(response.messageValidate['password_baru'][0]);
                        }
                        else{
                            $('.validation-password_baru').text('');
                        }
                        if(response.messageValidate['konfirmasi_password_baru']){
                            $('.validation-konfirmasi_password_baru').text(response.messageValidate['konfirmasi_password_baru'][0]);
                        }
                        else{
                            $('.validation-konfirmasi_password_baru').text('');
                        }
                        Swal.fire({
                            icon: response.status,
                            title: response.title,
                            text: response.message
                        });
                    }
                })
            });
        // CLOSE GANTI PASSWORD

        // OPEN PASSWORD LAMA LIHAT
        var passwordLamaInput = $('#password_lama');
        var togglePasswordLamaButton = $('#togglePasswordLama');
        togglePasswordLamaButton.click(function () {
            if (passwordLamaInput.attr('type') === 'password') {
                passwordLamaInput.attr('type', 'text');
                togglePasswordLamaButton.html('<i class="fa fa-eye" aria-hidden="true"></i>');
            } else {
                passwordLamaInput.attr('type', 'password');
                togglePasswordLamaButton.html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
            }
        });
        // CLOSE PASSWORD LAMA LIHAT

        // OPEN PASSWORD BARU LIHAT
        var passwordBaruInput = $('#password_baru');
        var togglePasswordBaruButton = $('#togglePasswordBaru');
        togglePasswordBaruButton.click(function () {
            if (passwordBaruInput.attr('type') === 'password') {
                passwordBaruInput.attr('type', 'text');
                togglePasswordBaruButton.html('<i class="fa fa-eye" aria-hidden="true"></i>');
            } else {
                passwordBaruInput.attr('type', 'password');
                togglePasswordBaruButton.html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
            }
        });
        // CLOSE PASSWORD BARU LIHAT

        // OPEN KONFIRMASI PASSWORD BARU LIHAT
        var passwordKonfirmInput = $('#konfirmasi_password_baru');
        var togglePasswordKonfirmButton = $('#togglePasswordKonfirmasi');
        togglePasswordKonfirmButton.click(function () {
            if (passwordKonfirmInput.attr('type') === 'password') {
                passwordKonfirmInput.attr('type', 'text');
                togglePasswordKonfirmButton.html('<i class="fa fa-eye" aria-hidden="true"></i>');
            } else {
                passwordKonfirmInput.attr('type', 'password');
                togglePasswordKonfirmButton.html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
            }
        });
        // CLOSE KONFIRMASI PASSWORD BARU LIHAT


    </script>
