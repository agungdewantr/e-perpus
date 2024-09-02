<form class="form" id="form_edit_rellarphp" enctype="multipart/form-data">
    @csrf
    <div class="modal-body" id="modalCenterLargeContent">
        <div class="row g-3">
            <input type="hidden" name="id" id="id" value="{{encrypt($anggota->id)}}">
            <div class="col-lg-4">
                <div>
                    <div class="mb-3">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" placeholder="Masukkan Nama Lengkap" name="nama_lengkap" id="nama_lengkap" value="{{$anggota->nama_lengkap ?? null}}" disabled style="background-color: #f8f9fa;">
                    </div>
                    <div class="mb-3">
                        <label for="tempat" class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" placeholder="Masukkan Tempat Lahir" name="tempat" id="tempat" value="{{$anggota->tempat ?? null}}" disabled style="background-color: #f8f9fa;">
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                        <input class="form-control" type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{$anggota->tanggal_lahir ?? null}}" disabled style="background-color: #f8f9fa;">
                    </div>
                    <div class="mb-3">
                        <label for="nomor_identitas" class="form-label">Nomor Identitas <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" placeholder="Masukkan Nomor Identitas" name="nomor_identitas" id="nomor_identitas" value="{{$anggota->nomor_identitas ?? null}}" disabled style="background-color: #f8f9fa;">
                    </div>
                    <div class="mb-3">
                        <label for="pekerjaan" class="form-label">Pekerjaan <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" placeholder="Masukkan Pekerjaan" name="pekerjaan" id="pekerjaan" value="{{$anggota->pekerjaan ?? null}}">
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" placeholder="Masukkan Alamat" name="alamat" id="alamat" value="{{$anggota->alamat ?? null}}" disabled style="background-color: #f8f9fa;">
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div>
                    <div class="mb-3">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" placeholder="Masukkan Jenis Kelamin" name="jenis_kelamin" id="jenis_kelamin" value="{{$anggota->jenis_kelamin ?? null}}" disabled style="background-color: #f8f9fa;">
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_masuk" class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                        <input class="form-control" type="date" name="tanggal_masuk" id="tanggal_masuk" value="{{$anggota->tanggal_masuk ?? null}}" disabled style="background-color: #f8f9fa;">
                    </div>
                    <div class="mb-3">
                        <label for="nomor_telepon" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                        <input class="form-control" type="string" placeholder="Masukkan Nomor Telepon" name="nomor_telepon" id="nomor_telepon" value="{{$anggota->nomor_telepon ?? null}}" disabled style="background-color: #f8f9fa;">
                        <small class="validation-nomor_telepon text-danger"></small>
                    </div>
                    <div class="mb-3 row">
                        <label for="alamat" class="form-label col-12">Status <span class="text-danger">*</span></label>
                        <div class="col-12">
                            <input  class="form-control"  type="checkbox" id="switch1" name="isActive" switch="none" {{$anggota->is_active ? 'checked' : ''}} />
                            <label for="switch1" data-on-label="On" data-off-label="Off"></label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="foto_kartu_identitas_id" class="form-label">Unggah Foto Kartu Identitas <span class="text-danger">*</span></label>
                        <input class="form-control" type="file" name="foto_kartu_identitas_id" id="foto_kartu_identitas_id" disabled style="background-color: #f8f9fa;">
                        <small class="validation-foto_kartu_identitas_id text-danger"></small>
                    </div>
                    <div class="mb-3">
                        @if($anggota->fotoKartuIdentitas()->exists())
                            <img src="{{ asset('storage/'. $anggota->fotoKartuIdentitas->file_path)}}" alt="Foto Kartu Identitas" style="width: 100px;">
                        @else

                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" placeholder="Masukkan Username" name="username" id="username" value="{{$anggota->user->username ?? null}}" disabled style="background-color: #f8f9fa;">
                        <div  style="font-size: 11px;" class="validation-username text-danger"></div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input class="form-control" type="email" placeholder="Masukkan Email" name="email" id="email" value="{{$anggota->email ?? null}}" disabled style="background-color: #f8f9fa;">
                        <div  style="font-size: 11px;" class="validation-email text-danger"></div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password </label>
                        <div class="input-group">
                            <input class="form-control text-black" name="password" type="password" value="" placeholder="Masukkan Password" id="password">
                            <button type="button" id="togglePassword" class="btn btn-outline-light text-black" style="border: 1px solid #d6d6d6;">
                                <i class="fa fa-eye-slash" aria-hidden="true"></i>
                            </button>
                        </div>
                        <small class="text-muted">
                            {{-- Jika tidak diganti password, kosongkan. --}}
                            <div class="validation-password text-danger"></div>
                        </small>
                    </div>
                    <div class="mb-3">
                        <label for="konfirmasi_password" class="form-label">Konfirmasi Password </label>
                        <div class="input-group">
                            <input class="form-control text-black" name="ulangi_password" type="password" value="" placeholder="Masukkan Password" id="ulangi_password">
                            <button type="button" id="toggleUlangiPassword" class="btn btn-outline-light text-black" style="border: 1px solid #d6d6d6;">
                                <i class="fa fa-eye-slash" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div id="msg"></div>
                    </div>
                    <div class="mb-3">
                        <label for="foto_id" class="form-label">Unggah Foto </label>
                        <input class="form-control" type="file" name="foto_id" id="foto_id">
                        <small class="validation-foto_id text-danger"></small>
                    </div>
                    <div class="mb-3">
                        @if($anggota->user->foto()->exists())
                            <img src="{{ asset('storage/'.$anggota->user->foto->file_path)}}" alt="Foto Profil" style="width: 100px;">
                        @else
                            <img src="{{ asset('admin_assets/images/users/not-found.png')}}" alt="Foto Profil" style="width: 100px;">
                        @endif
                    </div>
                </div>
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
                                    <div class="text-danger" style="font-size: 11px;">
                                        Password Tidak Sesuai!
                                    </span>
                    `);
                    $("#button_simpan").prop('disabled', true);
                } else {
                    $("#msg").html(`
                                        <div class="text-success" style="font-size: 11px;">
                                            Password Telah Sesuai!
                                        </span>
                                    `);
                    $("#button_simpan").prop('disabled', false);
                }
            });
        //CLOSE CONFIRM PASSWORD
    });
    $('#form_edit_rellarphp').on('submit', function(event) {
        event.preventDefault();
        event.stopImmediatePropagation();
        idata = new FormData($('#form_edit_rellarphp')[0]);
        $.ajax({
            type: "POST",
            url: "{{ route('keanggotaan.update') }}",
            data: idata,
            processData: false,
            contentType: false,
            cache: false,
            success: function(data) {
                $("#form_edit_rellarphp")[0].reset();
                $("#modalCenterExtraLarge").modal('hide');
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
                if(response.messageValidate['pekerjaan']){
                    $('.validation-pekerjaan').text(response.messageValidate['pekerjaan'][0]);
                }
                else{
                    $('.validation-pekerjaan').text('');
                }
                if(response.messageValidate['password']){
                    $('.validation-password').text(response.messageValidate['password'][0]);
                }
                else{
                    $('.validation-password').text('');
                }
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
