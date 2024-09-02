<link href="{{ asset('/') }}admin_assets/libs/choices.js/public/assets/styles/choices.min.css" rel="stylesheet" type="text/css" />
<link href="{{ asset('/') }}admin_assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
<form class="form" id="form_edit" enctype="multipart/form-data">
    @csrf
    <div class="modal-body" id="modalCenterLargeContent">
        <div class="row g-3">

            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <label for="username" class="form-label">Username</label>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <input type="text" class="form-control" name="id" id="id" value="{{encrypt($user->id)}}" readonly hidden>
                <input type="text" class="form-control" name="username" id="username" value="{{$user->username}}" placeholder="Masukkan Username" readonly style="background-color: #f8f9fa;">
                <div class="validation-username text-danger"></div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <label for="validationEmail" class="form-label">Email</label>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <input type="email" class="form-control" name="email" id="email" value="{{$user->email}}" placeholder="Masukkan Email" readonly style="background-color: #f8f9fa;">
                <div class="validation-email text-danger"></div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <label for="role" class="form-label">Role</label>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                @if($user->role->id == $superadmin->id)
                    <input type="hidden" name="role" class="form-control" value="{{$superadmin->id}}" readonly style="background-color: #f8f9fa;">
                    <input type="text" class="form-control" value="{{$superadmin->nama}}" disabled style="background-color: #f8f9fa;">
                @else
                    <select class="form-select" id="role" name="role" required>
                        <option selected disabled value="">-- Pilih Role --</option>
                        @foreach ($roles as $role)
                            <option value="{{$role->id}}" {{$role->id == $user->role_id ? 'selected' : ''}}>{{$role->nama}}</option>
                        @endforeach
                    </select>
                    <div class="validation-role text-danger"></div>
                @endif
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <label for="password" class="form-label">Password</label>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <div class="input-group">
                    <input class="form-control" name="password" type="password" value="" placeholder="Masukkan Password" id="password">
                    <button type="button" id="togglePassword" class="btn btn-outline-secondary">
                        <i class="fa fa-eye-slash" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="validation-password text-danger">
                    <small class="text-muted">
                        Minimal 8 Karakter kombinasi huruf kapital, huruf kecil, angka dan simbol(@$!#%*?&).
                    </small>
                    <br>
                    <small class="text-danger">
                        *Tidak perlu diisi bila tidak ingin mengubah password.
                    </small>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <label class="form-label" style="font-weight: bold;font-size:13px;">Ulangi Password</label>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <div class="input-group">
                    <input class="form-control" type="password" value="" placeholder="Masukkan Ulang Password" id="confirmPassword">
                    <button type="button" id="toggleConfirmPassword" class="btn btn-outline-secondary">
                        <i class="fa fa-eye-slash" aria-hidden="true"></i>
                    </button>
                </div>
                <div id="msg"></div>
                <div class="invalid-feedback">
                    Password Harus Cocok!
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <label class="form-label">Foto</label>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-lg-9 col-md-12">
                        <input class="form-control" type="file" name="foto" id="foto" accept="image/*">
                        <div class="validation-foto text-danger"></div>
                    </div>
                    <div class="col-lg-3 col-md-12 text-center">
                        @if($user->foto)
                            <img src="{{ Storage::url($user->foto->file_path) }}" alt="" style="width: 100px;" id="logo_edit">
                        @else
                            <img src="{{ Storage::url('user/default.png') }}" alt="" style="width: 100px;" id="logo_edit">
                        @endif
                        <br>
                        <small class="form-text" style="color: red">Foto sekarang</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <label for="isActive" class="form-label">Status</label>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <input type="checkbox" id="switch1" name="isActive" switch="none" {{$user->is_active ? 'checked' : ''}}  {{$user->role->id == 4 ? 'readonly':''}}/>
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

                } else {
                    $("#msg").html("Password Sesuai").css("color", "green");
                }
            });
        // CLOSE VALIDATION CONFIRM PASSWORD (COCOK/TIDAK COCOK)

        // OPEN VALIDATION CHOICES JS (role_id)
            const choices = new Choices(document.querySelector('#role'));
        // CLOSE VALIDATION CHOICES JS (role_id)
    });
    $('#form_edit').on('submit', function(event) {
        event.preventDefault();
        event.stopImmediatePropagation();

        // Pemeriksaan password
        if ($("#password").val() != $("#confirmPassword").val()) {
            $("#msg").html("Password Tidak Sesuai").css("color", "red");
            return;
        }

        idata = new FormData($('#form_edit')[0]);
        $.ajax({
            type: "POST",
            url: "{{ route('user.update') }}",
            data: idata,
            processData: false,
            contentType: false,
            cache: false,
            success: function(data) {
                // $('#tableUser').DataTable().ajax.reload();
                $("#form_edit")[0].reset();
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
                    if(response.messageValidate['role']){
                        $('.validation-role').text(response.messageValidate['role'][0]);
                    }
                    else{
                        $('.validation-role').text('');
                    }
                    if(response.messageValidate['password']){
                        $('.validation-password').text(response.messageValidate['password'][0]);
                    }
                    else{
                        $('.validation-password').text('');
                    }
                    if(response.messageValidate['foto']){
                        $('.validation-foto').text(response.messageValidate['foto'][0]);
                    }
                    else{
                        $('.validation-foto').text('');
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
