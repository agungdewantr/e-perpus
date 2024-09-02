<form class="form" id="form_edit_rellarphp" enctype="multipart/form-data">
    @csrf
    <div class="modal-body" id="modalCenterLargeContent">
        <div class="row">
            <div class="col-lg-12">
                <div id="contentAnggota" class="">
                    <input class="form-control" type="hidden" value="{{encrypt($kunjunganPerpustakaan->id)}}" name="id" id="id" required>
                    <div class="mb-3">
                        <label for="profil_anggota_id" class="form-label">Nama Anggota <span class="text-danger">*</span></label>
                        <select class="form-select select2" name="profil_anggota_id" id="profil_anggota_id" disabled style="background-color: #f8f9fa;">
                            <option value=""  selected>{{$kunjunganPerpustakaan->profilAnggota->nomor_anggota ?? '-'}} - {{$kunjunganPerpustakaan->profilAnggota->nama_lengkap ?? '-'}}</option>
                        </select>
                    </div>
                </div>
                <div id='contentNonAnggota' class="">
                    <div class="mb-3">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="nama_lengkap" id="nama_lengkap" placeholder="Masukkan Nama Lengkap" value="{{$kunjunganPerpustakaan->nama_lengkap}}" disabled style="background-color: #f8f9fa;">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div id="contentJenisKelaminNonAnggota" class="">
                    <div class="mb-3">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                        <select class="form-select" name="jenis_kelamin" id="jenis_kelamin" required>
                            <option value="" disabled {{$kunjunganPerpustakaan->jenis_kelamin == null ? 'selected' : ''}}>-- Pilih Jenis Kelamin --</option>
                            <option value="laki-laki"{{$kunjunganPerpustakaan->jenis_kelamin =='laki-laki' ? 'selected' : ''}}>Laki-Laki</option>
                            <option value="perempuan"{{$kunjunganPerpustakaan->jenis_kelamin =='perempuan' ? 'selected' : ''}}>Perempuan</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div>
                    <div class="mb-3">
                        <label for="tanggal_kunjungan" class="form-label">Tanggal Kunjungan <span class="text-danger">*</span></label>
                        <input type="date" id="tanggal_kunjungan" name="tanggal_kunjungan" class="form-control" value="{{$kunjunganPerpustakaan->tanggal_kunjungan}}" required>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div>
                    <div class="mb-3">
                        <label for="keperluan" class="form-label">Keperluan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="keperluan" name="keperluan" placeholder="Masukkan Keperluan" required>{{$kunjunganPerpustakaan->keperluan}}</textarea>
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
        var profilAnggota = {{$kunjunganPerpustakaan->profil_anggota_id ?? 'null'}};
        if(profilAnggota != null){
            $('#contentAnggota').removeClass('d-none');
            $('#contentNonAnggota').addClass('d-none');
            $('#nama_lengkap').prop('required', false);
            $('#profil_anggota_id').prop('required', true);
        }
        else{
            $('#contentAnggota').addClass('d-none');
            $('#contentNonAnggota').removeClass('d-none');
            $('#nama_lengkap').prop('required', true);
            $('#profil_anggota_id').prop('required', false);
        }
    });
    $('#form_edit_rellarphp').on('submit', function(event) {
        event.preventDefault();
        event.stopImmediatePropagation();
        idata = new FormData($('#form_edit_rellarphp')[0]);
        $.ajax({
            type: "POST",
            url: "{{ route('kunjunganperpustakaan.update') }}",
            data: idata,
            processData: false,
            contentType: false,
            cache: false,
            success: function(data) {
                $("#form_edit_rellarphp")[0].reset();
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
</script>
