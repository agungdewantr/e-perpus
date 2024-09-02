<div class="modal-body">
    <div class="row">
        <div class="col-1">
            <i class="fa fa-book me-1"></i>
        </div>
        <div class="col-11">
            <span class="">
                Sebelum melakukan import buku telah dibaca menggunakan file excel, silahkan mengunduh template  melalui tombol berikut :
            </span>
            <br>
            <br>
            <form action="{{route('bukutelahdibaca.downloadtemplate')}}">
                <button type="submit" class="form-control btn btn-sm btn-success">
                    <i class="fa fa-file-excel"></i> Template Excel
                </button>
            </form>
        </div>
    </div>
</div>
<div class="modal-footer mt-2">
    <button type="button" class="btn btn-sm btn-secondary waves-effect text-left" data-bs-dismiss="modal" aria-label="Close">
        <i class="fa fa-times"></i> &nbsp; Batal
    </button>
    <button type="submit" class="btn btn-sm btn-success waves-effect text-left" onclick="lanjut('Buku Telah Dibaca')">
        <i class="fa fa-chevron-right"></i> &nbsp; Lanjutkan
    </button>
</div>


<script>
    //Modal Show Import
    function lanjut(param){
        $("#modalCenterStandart").modal(`hide`);
        $("#modalCenterStandart2").modal(`show`);
        $("#modalCenterStandartLabel2").html('Import ' + param);
        $("#modalCenterStandartContent2").html(`
                                        <br><br>
                                        <div class="modal-body">
                                            <form id="form_tambah"  enctype="multipart/form-data">
                                                @csrf
                                                <p class="text-danger">*Pastikan Berkas <strong>Telah Sesuai</strong> dengan Template yang Telah Disedikan!</p>
                                                <div class="input-group mb-3">
                                                    <input type="file" name="file" class="form-control" placeholder="Masukkan file berkas"
                                                        aria-label="Masukkan file berkas" aria-describedby="button-addon2">
                                                    <button class="btn btn-success" type="submit" id="button-addon2">
                                                        Import
                                                    </button>
                                                </div>
                                            </form>
                                            <br><br>
                                        </div>
        `);

        $('#form_tambah').on('submit', function(event) {
            event.preventDefault();
            idata = new FormData($('#form_tambah')[0]);
            $.ajax({
                type: "POST",
                url: "{{route('bukutelahdibaca.importexcel')}}",
                data: idata,
                processData: false,
                contentType: false,
                cache: false,
                success: function(data) {
                    datatable().ajax.reload();
                    Swal.fire({
                        icon: data.status,
                        title: data.title,
                        text: data.messages,
                        customClass: {
                            confirmButton: 'btn btn-success'
                        }
                    });
                    $("#modalCenterStandart2").modal(`hide`);
                    $("#form_tambah")[0].reset();
                },
                error: function(data) {
                    // console.log(data);
                    Swal.fire({
                        icon: 'error',
                        title: 'Proses Gagal!',
                        text: data.responseJSON.messages,
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        }
                    });
                }
            });
        });
    }
</script>
