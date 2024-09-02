    {{-- OPEN PROFIL --}}
            <div class="shop-bx-title clearfix">
                <div class="row">
                    <div class="col">
                <h5 class="text-uppercase">Informasi Profil</h5>
            </div>
                <div class="col d-flex justify-content-end">
                    <button type="button" onclick="edit_active()" class="btn btn-sm btn-biru" id="btn-aksi"><i id="icon-btn" class="fa-solid fa-pen-to-square me-2"></i> <span id="text-btn">Ubah</span></button>
                </div>
            </div>
            </div>
            <form class="form" id="form_profil_anggota_rellarphp" enctype="multipart/form-data">
                @csrf
                <input type="hidden" class="form-control" name="id" id="id" value="{{$user->profilAnggota ? encrypt($user->profilAnggota->id) : null}}" readonly>
                <div class="row m-b30">
                    <div class="col-lg-6 col-md-6">
                        <div class="mb-3">
                            <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger"> *</span></label>
                            <input type="text" class="form-control" name="nama_lengkap" id="nama_lengkap" placeholder="Masukkan Nama Lengkap" readonly tabindex="1" required value="{{$user->profilAnggota->nama_lengkap ?? ''}}">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat <span class="text-danger"> *</span></label>
                            <input type="text" class="form-control" name="alamat" id="alamat" placeholder="Masukkan Alamat" readonly tabindex="2" required value="{{$user->profilAnggota->alamat ?? ''}}">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="mb-3">
                            <label for="tempat" class="form-label">Tempat Lahir <span class="text-danger"> *</span></label>
                            <input type="text" class="form-control" name="tempat" id="tempat" placeholder="Masukkan Tempat Lahir" readonly tabindex="3" required value="{{$user->profilAnggota->tempat ?? ''}}">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="mb-3">
                            <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger"> *</span></label>
                            <input type="date" class="form-control" name="tanggal_lahir" id="tanggal_lahir" readonly tabindex="4" required value="{{$user->profilAnggota->tanggal_lahir ?? ''}}">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger"> *</span></label>
                            <select class="form-select" name="jenis_kelamin" id="jenis_kelamin" tabindex="5" required disabled>
                                <option value="">-- Masukkan Jenis Kelamin --</option>
                                <option value="laki-laki" {{$user->profilAnggota ? ($user->profilAnggota->jenis_kelamin == 'laki-laki' ? 'selected' : '') : ''}}>Laki-laki</option>
                                <option value="perempuan" {{$user->profilAnggota ? ($user->profilAnggota->jenis_kelamin == 'perempuan' ? 'selected' : '') : ''}}>Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="mb-3">
                            <label for="foto_kartu_identitas_id" class="form-label">Unggah Foto Kartu Identitas <span class="text-danger"> *</span></label>
                            <input type="file" class="form-control" id="foto_kartu_identitas_id" name="foto_kartu_identitas_id" tabindex="6" required disabled>
                            <small class="validation-foto text-danger"></small>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="mb-3">
                            <label for="nomor_identitas" class="form-label">Nomor Identitas</label>
                            <input type="text" class="form-control" id="nomor_identitas" readonly name="nomor_identitas" placeholder="Masukkan Nomor Identitas" tabindex="7" value="{{$user->profilAnggota->nomor_identitas ?? ''}}">
                            <small class="validation-nomor_identitas text-danger"></small>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" readonly id="email" placeholder="Masukkan Email" tabindex="8" value="{{$user->email ?? $user->profilAnggota->email ?? ''}}">
                            <small class="validation-email text-danger"></small>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="mb-3">
                            <label for="pekerjaan" class="form-label">Pekerjaan</label>
                            <input type="text" class="form-control" id="pekerjaan" name="pekerjaan" readonly placeholder="Masukkan Pekerjaan" tabindex="9" value="{{$user->profilAnggota->pekerjaan ?? ''}}">
                            <small class="validation-pekerjaan text-danger"></small>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="mb-3">
                            <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" name="nomor_telepon" readonly id="nomor_telepon" placeholder="Masukkan Nomor Telepon" tabindex="10" value="{{$user->profilAnggota->nomor_telepon ?? ''}}">
                            <small class="validation-nomor_telepon text-danger"></small>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <div id="submit_edit" style="display: none;">
                        <button type="button" class="btn btn-danger btnhover mx-2" id="btn-batal">Batal</button>
                        <button type="submit" class="btn btn-success btnhover">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
    {{-- CLOSE PROFIL --}}
    <script>
        //OPEN PROFIL
            $('#form_profil_anggota_rellarphp').on('submit', function(event) {
                event.preventDefault();
                idata = new FormData($('#form_profil_anggota_rellarphp')[0]);
                $.ajax({
                    type: 'POST',
                    url: "{{ route('detailanggota.profil')}}",
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
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                        var response = xhr.responseJSON;
                        if(response.messageValidate['foto_kartu_identitas_id']){
                            $('.validation-foto').text(response.messageValidate['foto_kartu_identitas_id'][0]);
                        }
                        else{
                            $('.validation-foto').text('');
                        }
                        if(response.messageValidate['nomor_identitas']){
                            $('.validation-nomor_identitas').text(response.messageValidate['nomor_identitas'][0]);
                        }
                        else{
                            $('.validation-nomor_identitas').text('');
                        }
                        if(response.messageValidate['email']){
                            $('.validation-email').text(response.messageValidate['email'][0]);
                        }
                        else{
                            $('.validation-email').text('');
                        }
                        if(response.messageValidate['pekerjaan']){
                            $('.validation-pekerjaan').text(response.messageValidate['pekerjaan'][0]);
                        }
                        else{
                            $('.validation-pekerjaan').text('');
                        }
                        if(response.messageValidate['nomor_telepon']){
                            $('.validation-nomor_telepon').text(response.messageValidate['nomor_telepon'][0]);
                        }
                        else{
                            $('.validation-nomor_telepon').text('');
                        }
                        Swal.fire({
                            icon: response.status,
                            title: response.title,
                            text: response.message
                        });
                    }
                })
            });



                @if (auth()->check())
                var auth = @json(auth()->user());
                var profilAnggota = @json(auth()->user()->profilAnggota);
                if (!profilAnggota) {
                    const inputIds = ['nama_lengkap','alamat','tempat','tanggal_lahir','jenis_kelamin','foto_kartu_identitas_id',
                    'nomor_identitas','email','pekerjaan', 'nomor_telepon'];
                    const btn = document.getElementById('submit_edit');
                    btn.style.display = (btn.style.display === 'none') ? 'block' : 'none';
                    inputIds.forEach(id => {
                    const inputElement = document.getElementById(id);
                    if (inputElement) {
                        inputElement.readOnly = !inputElement.readOnly;
                        inputElement.removeAttribute('disabled');
                    }
                });
                }
            @endif



            function edit_active() {
                const inputIds = ['pekerjaan', 'nomor_telepon'];
                const btn = document.getElementById('submit_edit');
                const buttonTextElement = document.getElementById('text-btn');
                const iconElement = document.getElementById('icon-btn');
                inputIds.forEach(id => {
                    const inputElement = document.getElementById(id);
                    if (inputElement) {
                        inputElement.readOnly = !inputElement.readOnly;
                    }
                });

                btn.style.display = (btn.style.display === 'none') ? 'block' : 'none';
                // buttonTextElement.innerText = (buttonTextElement.innerText === 'Ubah') ? 'Batal' : 'Ubah';
                // iconElement.classList.toggle('fa-pen-to-square');
                // iconElement.classList.toggle('fa-times-square');
            }

            $("#btn-batal").click(function() {
                var pekerjaan = "{{$user->profilAnggota->pekerjaan ?? ''}}";
                var nomor_telepon = "{{$user->profilAnggota->nomor_telepon ?? ''}}";
                $("#pekerjaan").val(pekerjaan);
                $("#nomor_telepon").val(nomor_telepon);
                edit_active();
            });


        // CLOSE PROFIL
    </script>
