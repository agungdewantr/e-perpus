<form class="form" id="form_tambah_rellarphp" enctype="multipart/form-data">
    @csrf
    <div class="modal-body" id="modalCenterLargeContent">
        <div class="row">
            <div class="col-lg-12">
                <input type="checkbox" id="is_anggotaPerpustakaan" name="is_anggotaPerpustakaan" value="iya">
                <label for="is_anggotaPerpustakaan"> Anggota Perpustakaan ?</label>
            </div>
            <div class="col-lg-12">
                <div id="contentNamaAnggota" class="d-none">
                    <div class="mb-3">
                        <label for="profil_anggota_id" class="form-label">Nama Anggota <span class="text-danger">*</span></label>
                        <select class="form-select select2" name="profil_anggota_id" id="profil_anggota_id">
                            <option value="" readonly>-- Masukkan Nomor Anggota --</option>
                            @foreach ($anggotas as $anggota)
                                <option value="{{$anggota->id}}">{{$anggota->nomor_anggota}} - {{$anggota->nama_lengkap}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div id='contentNamaNonAnggota' class="">
                    <div class="mb-3">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_lengkap" id="nama_lengkap" placeholder="Masukkan Nama Lengkap">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div id="contentJenisKelaminAnggota" class="d-none">
                    <div class="mb-3">
                        <label for="jenis_kelaminAnggota" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                        <input type="input" id="jenis_kelaminAnggota" name="jenis_kelaminAnggota" class="form-control" placeholder="Masukkan Jenis Kelamin" required>
                    </div>
                </div>
                <div id="contentJenisKelaminNonAnggota" class="">
                    <div class="mb-3">
                        <label for="jenis_kelaminNonAnggota" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                        <select class="form-select" name="jenis_kelaminNonAnggota" id="jenis_kelaminNonAnggota" required>
                            <option value="" disabled selected>-- Pilih Jenis Kelamin --</option>
                            <option value="laki-laki">Laki-Laki</option>
                            <option value="perempuan">Perempuan</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div>
                    <div class="mb-3">
                        <label for="tanggal_kunjungan" class="form-label">Tanggal Kunjungan <span class="text-danger">*</span></label>
                        <input type="date" id="tanggal_kunjungan" name="tanggal_kunjungan" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div>
                    <div class="mb-3">
                        <label for="keperluan" class="form-label">Keperluan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="keperluan" name="keperluan" placeholder="Masukkan Keperluan" required></textarea>
                        <div  style="font-size: 11px;" class="validation-keperluan text-danger"></div>
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

    });
    //OPEN PROSES TAMBAH
        $('#form_tambah_rellarphp').on('submit', function(event) {
            event.preventDefault();
            event.stopImmediatePropagation();
            idata = new FormData($('#form_tambah_rellarphp')[0]);
            $.ajax({
                type: "POST",
                url: "{{ route('kunjunganperpustakaan.store') }}",
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
                    if(response.messageValidate['keperluan']){
                        $('.validation-keperluan').text(response.messageValidate['keperluan'][0]);
                    }
                    else{
                        $('.validation-keperluan').text('');
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
    //CLOSE PROSES TAMBAH
    //OPEN CHANGE IS ANGGOTA
        $('#is_anggotaPerpustakaan').on('change', function(){
            if($(this).is(":checked") == true){
                $('#contentNamaAnggota').removeClass('d-none');
                $('#contentNamaNonAnggota').addClass('d-none');
                $('#nama_lengkap').prop('required', false);
                $('#profil_anggota_id').prop('required', true);

                $('#contentJenisKelaminAnggota').removeClass('d-none');
                $('#contentJenisKelaminNonAnggota').addClass('d-none');
                $('#jenis_kelaminNonAnggota').prop('required', false);
                $('#jenis_kelaminAnggota').prop('required', true);
            }
            else{
                $('#contentNamaAnggota').addClass('d-none');
                $('#contentNamaNonAnggota').removeClass('d-none');
                $('#nama_lengkap').prop('required', true);
                $('#profil_anggota_id').prop('required', false);

                $('#contentJenisKelaminAnggota').addClass('d-none');
                $('#contentJenisKelaminNonAnggota').removeClass('d-none');
                $('#jenis_kelaminNonAnggota').prop('required', true);
                $('#jenis_kelaminAnggota').prop('required', false);

            }
        });
    //CLOSE CHANGE IS ANGGOTA
    //OPEN GET JENIS KELAMIN ANGGOTA
        $('#profil_anggota_id').on('change', function(){
            var value = $(this).val();
            var act = "{{ route('kunjunganperpustakaan.get.jeniskelamin', ['param' => ':param']) }}";
                    act = act.replace(':param', value);
            $.ajax({
                type: "GET",
                url: act,
                success: function(data) {
                    $('#jenis_kelaminAnggota').val(data.jenis_kelamin);
                },
            });
        });
    //CLOSE GET JENIS KELAMIN ANGGOTA
</script>
