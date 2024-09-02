<form class="form" id="form_tambah">
    @csrf
    <div class="modal-body" id="modalCenterContent">
        <div class="row g-3">
            <div class="col-lg-12">
                <div>
                    <div class="mb-3">
                        <label for="profil_anggota_id" class="form-label">Nomor & Nama Lengkap Anggota Peminjaman <span class="text-danger">*</span></label>
                        <select id="profil_anggota_id" name="profil_anggota_id" class="form-select" required>
                            <option class="" disabled selected>-- Pilih Anggota Peminjam --</option>
                            @foreach ($anggotas as $anggota)
                                <option value="{{$anggota->id}}">{{$anggota->nomor_anggota.'-'.$anggota->nama_lengkap}}</option>
                            @endforeach
                        </select>
                        <small class="validation-profil_anggota_id text-danger"></small>
                    </div>
                    <div class="mb-3">
                        <label for="item_bukus_id" class="form-label">Kode & Judul Buku <span class="text-danger">*</span></label>
                        <select id="item_bukus_id" name="item_bukus_id" class="form-select" required>
                            <option class="" disabled selected>-- Pilih Buku --</option>
                            @foreach ($bukus as $buku)
                                <option value="{{$buku->id}}">{{$buku->subKategori->kode. ' '. strtoupper(substr($buku->penulises[0]->nama, 0, 3)). ' '.strtolower(substr($buku->penerbit->namaPenerbit, 0, 1)).' - '.$buku->judul}}</option>
                            @endforeach
                        </select>
                        <small class="validation-item_bukus_id text-danger"></small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div>
                    <div class="mb-3">
                        <label for="tanggal_pengambilan_pinjaman" class="form-label">Tanggal Peminjaman <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_pengambilan_pinjaman" id="tanggal_pengambilan_pinjaman" class="form-control" value="{{ now()->format('Y-m-d') }}" readonly style="background-color: #e9e9ef;">
                        <small class="validation-tanggal_pengambilan_pinjaman text-danger"></small>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_batas_pinjaman" class="form-label">Tanggal Batas Kembali <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_batas_pinjaman" id="tanggal_batas_pinjaman" class="form-control" value="{{ now()->addDay(7)->format('Y-m-d') }}" readonly style="background-color: #e9e9ef;">
                        <small class="validation-tanggal_batas_pinjaman text-danger"></small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div>
                    <div class="mb-3">
                        <label for="tanggal_pengembalian_pinjaman" class="form-label">Tanggal Pengembalian<span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_pengembalian_pinjaman" id="tanggal_pengembalian_pinjaman" class="form-control" value="" disabled style="background-color: #e9e9ef;">
                        <small class="validation-tanggal_pengembalian_pinjaman text-danger"></small>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select" name="status" id="status">
                            <option value="" disabled selected>-- Pilih Status --</option>
                            <option value="Sedang Dipinjam">Sedang Dipinjam</option>{{-- JIKA BUKU SEDANG DALAM WAKTU PINJAMAN --}}
                            <option value="Belum Kembali" disabled>Belum Kembali</option>{{-- JIKA BUKU SUDAH DILUAR WAKTU PINJAMAN --}}
                            <option value="Sudah Kembali" disabled>Sudah Kembali</option>{{-- JIKA BUKU SUDAH DIKEMBALIKAN --}}
                        </select>
                        <small class="validation-status text-danger"></small>
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

<script>
    $(document).ready(function() {

    });
    //get date tanggal batas kembali (on change tanggal peminjaman)
    $('#tanggal_pengambilan_pinjaman').on('change', function(){
        var selectedDate = new Date($(this).val());
        selectedDate.setDate(selectedDate.getDate() + 7);
        var formattedDate = selectedDate.toISOString().split('T')[0];
        $('#tanggal_batas_pinjaman').val(formattedDate);
    });
    //Progres tambah
    $('#form_tambah').on('submit', function(event) {
        event.preventDefault();
        event.stopImmediatePropagation();
        idata = new FormData($('#form_tambah')[0]);
        $.ajax({
            type: "POST",
            url: "{{ route('daftarpeminjaman.store') }}",
            data: idata,
            processData: false,
            contentType: false,
            cache: false,
            success: function(data) {
                $("#form_tambah")[0].reset();
                $("#modalCenter").modal('hide');
                Swal.fire({
                    icon: data.icon,
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
                if(response.messageValidate['profil_anggota_id']){
                    $('.validation-profil_anggota_id').text(response.messageValidate['profil_anggota_id'][0]);
                }
                else{
                    $('.validation-profil_anggota_id').text('');
                }
                if(response.messageValidate['item_bukus_id']){
                    $('.validation-item_bukus_id').text(response.messageValidate['item_bukus_id'][0]);
                }
                else{
                    $('.validation-item_bukus_id').text('');
                }
                if(response.messageValidate['tanggal_pengambilan_pinjaman']){
                    $('.validation-tanggal_pengambilan_pinjaman').text(response.messageValidate['tanggal_pengambilan_pinjaman'][0]);
                }
                else{
                    $('.validation-tanggal_pengambilan_pinjaman').text('');
                }
                if(response.messageValidate['tanggal_batas_pinjaman']){
                    $('.validation-tanggal_batas_pinjaman').text(response.messageValidate['tanggal_batas_pinjaman'][0]);
                }
                else{
                    $('.validation-tanggal_batas_pinjaman').text('');
                }
                if(response.messageValidate['status']){
                    $('.validation-status').text(response.messageValidate['status'][0]);
                }
                else{
                    $('.validation-status').text('');
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
