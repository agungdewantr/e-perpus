<form class="form" id="form_tambah_rellarphp" enctype="multipart/form-data">
    @csrf
    <div class="modal-body" id="modalCenterLargeContent">
        <div class="row g-3">

            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <label for="nip" class="form-label">NIP</label>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <input type="text" class="form-control" name="nip" id="nip" value="" placeholder="Masukkan NIP">
                <small class="validation-nip text-danger"></small>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <input type="text" class="form-control" name="nama_lengkap" id="nama_lengkap" value="" placeholder="Masukkan Nama Lengkap" required>
                <small class="validation-nama_lengkap text-danger"></small>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <label for="nomor_telepon" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <input type="text" class="form-control" name="nomor_telepon" id="nomor_telepon" value="" placeholder="Masukkan Nomor Telepon" required>
                <small class="validation-nomor_telepon text-danger"></small>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <label for="jadwal_shift" class="form-label">Jadwal Shift <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-lg-6 col-sm-12">
                        <input type="time" class="form-control" name="jadwal_shift_mulai" id="jadwal_shift_mulai" value="" required>
                        <small class="validation-jadwal_shift_mulai text-danger"></small>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <input type="time" class="form-control" name="jadwal_shift_selesai" id="jadwal_shift_selesai" value="" required>
                        <small class="validation-jadwal_shift_selesai text-danger"></small>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <input type="text" class="form-control" name="username" id="username" value="" placeholder="Masukkan Username" required>
                <small class="validation-username text-danger"></small>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <label for="email" class="form-label">Email</label>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <input type="email" class="form-control" name="email" id="email" value="" placeholder="Masukkan Email">
                <small class="validation-email text-danger"></small>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <div class="input-group">
                    <input class="form-control" name="password" type="password" value="" placeholder="Masukkan Password" id="password" required>
                    <button type="button" id="togglePassword" class="btn btn-outline-secondary">
                        <i class="fa fa-eye-slash" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="validation-password text-danger">
                    <small class="text-muted">Minimal 8 Karakter kombinasi huruf kapital, huruf kecil, angka dan simbol(@$!#%*?&).</small>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <label class="form-label" style="font-weight: bold;font-size:13px;">Ulangi Password <span class="text-danger">*</span></label>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <div class="input-group">
                    <input class="form-control" type="password" value="" placeholder="Masukkan Ulang Password" id="confirmPassword" required>
                    <button type="button" id="toggleConfirmPassword" class="btn btn-outline-secondary">
                        <i class="fa fa-eye-slash" aria-hidden="true"></i>
                    </button>
                </div>
                <small id="msg"></small>
                <div class="invalid-feedback">
                    Password Harus Cocok!
                </div>
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
        <button type="submit" class="btn btn-sm btn-success waves-effect text-left" id="btn_s" disabled>
            <i class="fa fa-save"></i> &nbsp; Simpan
        </button>
    </div>
</form>
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
    $('#form_tambah_rellarphp').on('submit', function(event) {
        event.preventDefault();
        event.stopImmediatePropagation();

        // Pemeriksaan password
        if ($("#password").val() != $("#confirmPassword").val()) {
            $("#msg").html("Password Tidak Sesuai").css("color", "red");
            return;
        }

        idata = new FormData($('#form_tambah_rellarphp')[0]);
        $.ajax({
            type: "POST",
            url: "{{ route('petugasadmin.store') }}",
            data: idata,
            processData: false,
            contentType: false,
            cache: false,
            success: function(data) {
                $("#form_tambah_rellarphp")[0].reset();
                $("#modalCenterLarge").modal('hide');
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
                if(response.messageValidate['nip']){
                    $('.validation-nip').text(response.messageValidate['nip'][0]);
                }
                else{
                    $('.validation-nip').text('');
                }
                if(response.messageValidate['nama_lengkap']){
                    $('.validation-nama_lengkap').text(response.messageValidate['nama_lengkap'][0]);
                }
                else{
                    $('.validation-nama_lengkap').text('');
                }
                if(response.messageValidate['nomor_telepon']){
                    $('.validation-nomor_telepon').text(response.messageValidate['nomor_telepon'][0]);
                }
                else{
                    $('.validation-nomor_telepon').text('');
                }
                if(response.messageValidate['jadwal_shift_mulai']){
                    $('.validation-jadwal_shift_mulai').text(response.messageValidate['jadwal_shift_mulai'][0]);
                }
                else{
                    $('.validation-nama_lengkap').text('');
                }
                if(response.messageValidate['jadwal_shift_selesai']){
                    $('.validation-jadwal_shift_selesai').text(response.messageValidate['jadwal_shift_selesai'][0]);
                }
                else{
                    $('.validation-jadwal_shift_selesai').text('');
                }
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
