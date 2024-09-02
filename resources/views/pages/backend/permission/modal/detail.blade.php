<link href="{{ asset('/') }}admin_assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
<form class="form" id="form_edit">
    @csrf
    <div class="modal-body" id="modalCenterLargeContent">
        <div class="row mb-3">
            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <label for="role" class="form-label">Role</label>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <input type="text" class="form-control" name="id" id="id" value="{{encrypt($role->id)}}" readonly hidden>
                <input type="text" class="form-control" name="role" id="role" value="{{$role->nama}}" placeholder="Masukkan Role" readonly  style="background-color: #f8f9fa;">
                <div class="validation-role text-danger"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 mb-2">
                <table class="table table-bordeless dt-responsive nowrap w-100" id="tablePermission">
                    <thead>
                        <tr>
                            <td class="fw-semibold">
                                Nama Menu
                            </td>
                            <td class="fw-semibold">
                                Tipe Menu
                            </td>
                            <td class="fw-semibold text-end">
                                Aksi
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($role->menus->sortBy(function($menu){
                                                                            return [$menu->tipe_menu, $menu->ordinal_number];
                                                                        }) as $menu)
                            <tr>
                                <td class="w-30">{{$menu->nama}}</td>
                                <td class="w-20">{{$menu->tipe_menu}}</td>
                                <td class="w-50">
                                    <div class="d-flex justify-content-end">
                                        <div class="form-check me-3 me-lg-5">
                                            <input class="form-check-input" type="checkbox" name="canRead_{{$menu->pivot->role_id}}_{{$menu->pivot->menu_id}}" id="checkboxPermission"  {{$menu->pivot->can_read == true ? 'checked' : ''}}/>
                                            <label class="form-check-label" for="canRead_{{$menu->pivot->role_id}}_{{$menu->pivot->menu_id}}"> Read </label>
                                        </div>
                                        <div class="form-check me-3 me-lg-5">
                                            <input class="form-check-input" type="checkbox" name="canCreate_{{$menu->pivot->role_id}}_{{$menu->pivot->menu_id}}" id="checkboxPermission" {{$menu->pivot->can_create == true ? 'checked' : ''}} />
                                            <label class="form-check-label" for="canCreate_{{$menu->pivot->role_id}}_{{$menu->pivot->menu_id}}"> Create </label>
                                        </div>
                                        <div class="form-check me-3 me-lg-5">
                                            <input class="form-check-input" type="checkbox" name="canUpdate_{{$menu->pivot->role_id}}_{{$menu->pivot->menu_id}}" id="checkboxPermission" {{$menu->pivot->can_update == true ? 'checked' : ''}}/>
                                            <label class="form-check-label" for="canUpdate_{{$menu->pivot->role_id}}_{{$menu->pivot->menu_id}}"> Update </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="canDelete_{{$menu->pivot->role_id}}_{{$menu->pivot->menu_id}}" id="checkboxPermission" {{$menu->pivot->can_delete == true ? 'checked' : ''}}/>
                                            <label class="form-check-label" for="canDelete_{{$menu->pivot->role_id}}_{{$menu->pivot->menu_id}}"> Delete </label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfooter>
                        <th colspan="3" class="text-end">
                            <input class="form-check-input" type="checkbox" id="selectAll" />
                            <label class="form-check-label" for="selectAll"> Pilih Semua </label>
                        </th>
                    </tfooter>
                </table>
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

<script src="{{ asset('/') }}admin_assets/libs/sweetalert2/sweetalert2.min.js"></script>
<script>
    $(document).ready(function () {
        $('#tablePermission').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "responsive" : false,
            "scrollX": true,
        })
        //OPEN CHECKED button SelectALL, jika semua checkbox tercentang
            var allChecked = true;

            $('input[type="checkbox"]').not('#selectAll').each(function () {
                if (!$(this).prop('checked')) {
                    allChecked = false;
                    return false;
                }
            });

            $('#selectAll').prop('checked', allChecked);
        //CLOSE CHECKED button SelectALL, jika semua checkbox tercentang
        //OPEN BUTTON selectAll mengubah Semua Checkbox
            $('#selectAll').change(function () {
                var checked = $(this).prop('checked');

                $('input[type="checkbox"]').not('#selectAll').prop('checked', checked);
            });
        //CLOSE BUTTON selectAll mengubah Semua Checkbox
    });

    $('#form_edit').on('submit', function(event) {
        event.preventDefault();
        event.stopImmediatePropagation();

        idata = new FormData($('#form_edit')[0]);
        $.ajax({
            type: "POST",
            url: "{{ route('permission.update') }}",
            data: idata,
            processData: false,
            contentType: false,
            cache: false,
            success: function(data) {
                // $('#tableUser').DataTable().ajax.reload();
                $("#form_edit")[0].reset();
                $("#modalCenterExtraLarge").modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: data.title,
                    text: data.message,
                    customClass: {
                        confirmButton: 'btn btn-success'
                    }
                }).then(function (result) {
                    if (result.value === true) {
                        window.location.reload();
                    }
                });
            },
            error: function(xhr, status, error) {
                var response = xhr.responseJSON;
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
