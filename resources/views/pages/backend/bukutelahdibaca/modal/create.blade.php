<form class="form" id="form_tambah_rellarphp" enctype="multipart/form-data">
    @csrf
    <div class="modal-body" id="modalCenterLargeContent">
        <div class="row">
            <div class="col-lg-12">
                <div>
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal<span class="text-danger">*</span></label>
                        <input type="date" id="tanggal" name="tanggal" class="form-control" required>
                    </div>
                </div>
            </div>
            <div id="formItemBuku">
                <div class="row itemBukuContent" id="itemContent0">
                    <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="bukus_id" class="form-label">Buku <span class="text-danger">*</span></label>
                                <select class="form-select select2" name="bukus_id[]" id="bukus_id">
                                    <option value="" readonly>-- Masukkan Judul Buku --</option>
                                    @foreach ($bukus as $buku)
                                        <option value="{{$buku->id}}">{{$buku->judul}} - ({{$buku->kode_buku}})</option>
                                    @endforeach
                                </select>
                            </div>
                    </div>
                    <div class="col-lg-4">
                        <div>
                            <div class="mb-3">
                                <label for="jumlah" class="form-label">Jumlah <span class="text-danger">*</span></label>
                                <input type="text" id="jumlah0" class="form-control" name="jumlah[]" placeholder="masukkan jumlah buku yang telah dibaca" required>
                                <div  style="font-size: 11px;" class="validation-jumlah0 text-danger"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="d-flex justify-content-end mt-4">
                            <div>
                                <button type="button" class="btnHapusItemBuku btn btn-sm btn-outline-danger waves-effect waves-light mx-2" id="btnHapusData0"><i class="fas fa-trash"></i> Hapus Data</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="dataButton" class="mt-2">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-sm btn-outline-success waves-effect waves-light" id="btnTambahData"><i class="fas fa-plus"></i> Tambah Data</button>
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

                //OPEN BUKU
                // OPEN TAMBAH FORM ITEM BUKU
                $('#btnTambahData').on('click', function (event) {
                        event.preventDefault();
                        let rowNumber = $('.itemBukuContent').length;
                        $('#btn_s').prop('disabled', false);
                        let newForm = `
                                        <div class="row itemBukuContent" id="itemBukuContent${rowNumber}">
                                            <div class="col-lg-6">
                                                    <div class="mb-3">
                                                        <label for="bukus_id${rowNumber}" class="form-label">Buku <span class="text-danger">*</span></label>
                                                        <select class="form-select select2" name="bukus_id[]" id="bukus_id${rowNumber}">
                                                            <option value="" readonly>-- Masukkan Judul Buku --</option>
                                                            @foreach ($bukus as $buku)
                                                                <option value="{{$buku->id}}">{{$buku->judul}} - ({{$buku->kode_buku}})</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div>
                                                    <div class="mb-3">
                                                        <label for="jumlah${rowNumber}" class="form-label">Jumlah <span class="text-danger">*</span></label>
                                                        <input type="text" id="jumlah${rowNumber}" class="form-control" name="jumlah[]" placeholder="masukkan jumlah buku yang telah dibaca" required>
                                                        <div  style="font-size: 11px;" class="validation-jumlah${rowNumber} text-danger"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="d-flex justify-content-end mt-4">
                                                    <div>
                                                        <button type="button" class="btnHapusItemBuku btn btn-sm btn-outline-danger waves-effect waves-light mx-2" id="btnHapusData${rowNumber}"><i class="fas fa-trash"></i> Hapus Data</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                        $('#formItemBuku').append(newForm);
                    });
                // CLOSE TAMBAH FORM ITEM BUKU
                //OPEN HAPUS FORM ITEM BUKU
                $('#formItemBuku').on('click','.btnHapusItemBuku' ,function () {
                        let rowNumber = $('.itemBukuContent').length;
                        if (rowNumber == 1)
                        {
                            $('#btn_s').prop('disabled', true);
                        } else if (rowNumber >= 2) {
                            $('#btn_s').prop('disabled', false);
                        }
                            $(this).closest('.itemBukuContent').remove();
                    });
                //CLOSE HAPUS FORM ITEM BUKU



    //OPEN PROSES TAMBAH
        $('#form_tambah_rellarphp').on('submit', function(event) {
            event.preventDefault();
            event.stopImmediatePropagation();
            $('#btn_s').prop('disabled', true);
            idata = new FormData($('#form_tambah_rellarphp')[0]);
            $.ajax({
                type: "POST",
                url: "{{ route('bukutelahdibaca.store') }}",
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
                // error: function(xhr, status, error) {
                //     var response = xhr.responseJSON;
                //     if(response.messageValidate['jumlah']){
                //         $('.validation-jumlah').text(response.messageValidate['jumlah'][0]);
                //     }
                //     else{
                //         $('.validation-jumlah').text('');
                //     }
                //     Swal.fire({
                //         icon: 'error',
                //         title: response.title,
                //         text: response.message,
                //         customClass: {
                //             confirmButton: 'btn btn-danger'
                //         }
                //     });
                // }
            });
        });
    //CLOSE PROSES TAMBAH


    $('body').on('input', 'input[name="jumlah[]"]', function() {
            var jumlahInput = $(this).val();
            var bukuId = $(this).closest('.itemBukuContent').find('select[name="bukus_id[]"]').val();
            var indeks = $(this).attr('id').replace('jumlah', ''); // Ambil indeks dari id

            // Lakukan pengecekan hanya jika kedua nilai (jumlah dan bukuId) valid
            if (jumlahInput && bukuId) {
                // Lakukan request Ajax untuk memeriksa jumlah buku di database
                $.ajax({
                    url: "{{ route('cekjumlahbuku') }}",
                    type: 'POST',
                    data: {
                        bukus_id: bukuId,
                        jumlah: jumlahInput,
                        indeks: indeks,
                        _token: "{{csrf_token()}}"
                    },
                    success: function(response) {
                        // Response dari server, misalnya berupa pesan atau informasi lain
                        if (response.error) {
                            $('.validation-jumlah' + indeks).text(response.error);
                            $('#btn_s').prop('disabled', true);
                        } else {
                            $('.validation-jumlah' + indeks).text('');
                            $('#btn_s').prop('disabled', false);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                        // Handle kesalahan Ajax
                    }
                });
            }
        });
</script>
