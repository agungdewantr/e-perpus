<form class="form" id="form_edit_rellarphp" enctype="multipart/form-data">
    @csrf
    <div class="modal-body" id="modalCenterLargeContent">
        <div class="row">
                <div class="col-lg-6">
                    <div>
                        <div class="mb-3">
                            <input type="hidden" value="{{encrypt($bukutelahdibaca->id)}}" name="id" id="id">
                            <label for="tanggal_kunjungan" class="form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" id="tanggal" name="tanggal" class="form-control" value="{{$bukutelahdibaca->tanggal}}" required>
                        </div>
                    </div>
                </div>

            <div class="col-lg-6">
                <div>
                    <div class="mb-3">
                        <label for="jenis_kelamin" class="form-label">Buku <span class="text-danger">*</span></label>
                        <select class="form-select" name="bukus_id" id="bukus_id" required>
                            <option value="" disabled {{$bukutelahdibaca->bukus_id == null ? 'selected' : ''}}>-- Pilih Buku --</option>
                            @foreach ($bukus as $buku)
                            <option value="{{$buku->id}}" {{$buku->id == $bukutelahdibaca->bukus_id ? 'selected' : ''}}>{{$buku->judul}} - ({{$buku->kode_buku}})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div>
                    <div class="mb-3">
                        <label for="keperluan" class="form-label">Jumlah <span class="text-danger">*</span></label>
                        <input class="form-control" id="jumlah" name="jumlah" placeholder="Masukkan Jumlah" value="{{$bukutelahdibaca->jumlah}}" required>
                        <div  style="font-size: 11px;" class="validation-jumlah text-danger"></div>
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
    $('#form_edit_rellarphp').on('submit', function(event) {
        event.preventDefault();
        event.stopImmediatePropagation();
        idata = new FormData($('#form_edit_rellarphp')[0]);
        $.ajax({
            type: "POST",
            url: "{{ route('bukutelahdibaca.update') }}",
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
