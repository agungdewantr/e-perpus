<form class="form" id="form_edit" enctype="multipart/form-data">
    @csrf
    <div class="modal-body" id="modalCenterLargeContent">
        <div class="row g-3">

            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <label for="user" class="form-label">User</label>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <input type="text" class="form-control" name="user" id="user" value="{{$activity->causer->username ?? '-'}}" disabled style="background-color: #f8f9fa;">
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <label for="aktivitas" class="form-label">Aktivitas</label>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <input type="text" class="form-control" name="aktivitas" id="aktivitas" value="{{$activity->description ?? '-'}}" disabled style="background-color: #f8f9fa;">
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                <label for="nama" class="form-label">IP Address</label>
            </div>
            <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <input type="text" class="form-control" name="ipaddress" value="{{$activity->properties['ip']}}" disabled style="background-color: #f8f9fa;">
            </div>

            @if (isset($activity->properties['data']))
                @php
                    $data = json_decode($activity->properties['data'], true);
                @endphp

                @if (is_array($data))
                    <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                        <label for="nama" class="form-label">Data Perubahan Terakhir</label>
                    </div>
                    <div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                        <textarea class="form-control" name="ipaddress" rows="5" disabled style="background-color: #f8f9fa;">@foreach($data as $key => $value){{ $key }}:@if(!is_array($value)){{ $value ?? '-' }}@else {{count($value)}} @endif @if(!$loop->last),@endif&#13;&#10;@endforeach</textarea>
                        <div class="validation-nama text-danger"></div>
                    </div>
                @endif
            @endif

        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary waves-effect text-left" data-bs-dismiss="modal" aria-label="Close">
            <i class="fa fa-times"></i> &nbsp; Tutup
        </button>
    </div>
</form>

<script>
    $(document).ready(function() {

    });
</script>
