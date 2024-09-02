<link href="{{ asset('/') }}admin_assets/libs/choices.js/public/assets/styles/choices.min.css" rel="stylesheet" type="text/css" />
<link href="{{ asset('/') }}admin_assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
<form class="form" id="form_edit" enctype="multipart/form-data">
    @csrf
    <div class="modal-body" id="modalCenterLargeContent">
        <div class="row g-3">

            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <label for="nama" class="form-label">Nama</label>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <input type="text" class="form-control" name="id" id="id" value="{{encrypt($menu->id)}}" readonly hidden>
                <input type="text" class="form-control" name="nama" id="nama" value="{{$menu->nama}}" placeholder="Masukkan Nama">
                <div class="validation-nama text-danger"></div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <label for="icon" class="form-label">Icon</label>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <input type="text" class="form-control" name="icon" id="icon" value="{{$menu->icon}}" placeholder="Masukkan Icon">
                <div class="validation-icon text-danger"></div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <label for="ordinalNumber" class="form-label">Ordinal Number</label>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <input type="number" class="form-control" name="ordinalNumber" id="ordinalNumber" value="{{$menu->ordinal_number}}" min="0" max="999" placeholder="Masukkan Nomer Urutan" required>
                <div class="validation-ordinalNumber text-danger"></div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <label for="tipeMenu" class="form-label">Tipe Menu</label>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <select class="form-control" id="tipeMenu" name="tipeMenu" required>
                    <option selected disabled value="">-- Pilih Tipe Menu --</option>
                    @foreach ($tipeMenus as $tipeMenu)
                        <option value="{{$tipeMenu}}" {{$tipeMenu == $menu->tipe_menu ? 'selected' : ''}}>{{$tipeMenu}}</option>
                    @endforeach
                </select>
                <div class="validation-tipeMenu text-danger"></div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <label for="isActive" class="form-label">Status</label>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <input type="checkbox" id="switch1" name="isActive" switch="none" {{$menu->is_active ? 'checked' : ''}}  />
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
        // OPEN VALIDATION CHOICES JS (tipe menu)
            const choices = new Choices(document.querySelector('#tipeMenu'));
        // CLOSE VALIDATION CHOICES JS (tipe menu)
    });
    $('#form_edit').on('submit', function(event) {
        event.preventDefault();
        event.stopImmediatePropagation();

        idata = new FormData($('#form_edit')[0]);
        $.ajax({
            type: "POST",
            url: "{{ route('menu.update') }}",
            data: idata,
            processData: false,
            contentType: false,
            cache: false,
            success: function(data) {
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
                if(response.messageValidate['nama']){
                    $('.validation-nama').text(response.messageValidate['nama'][0]);
                }
                else{
                    $('.validation-nama').text('');
                }
                if(response.messageValidate['icon']){
                    $('.validation-icon').text(response.messageValidate['icon'][0]);
                }
                else{
                    $('.validation-icon').text('');
                }
                if(response.messageValidate['ordinalNumber']){
                    $('.validation-ordinalNumber').text(response.messageValidate['ordinalNumber'][0]);
                }
                else{
                    $('.validation-ordinalNumber').text('');
                }
                if(response.messageValidate['tipeMenu']){
                    $('.validation-tipeMenu').text(response.messageValidate['tipeMenu'][0]);
                }
                else{
                    $('.validation-tipeMenu').text('');
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
