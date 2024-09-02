@extends('layouts.backend')
@section('title','Menu')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18"></h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Management</a></li>
                        <li class="breadcrumb-item active">Menu</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12">
                            <div></div>
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="card-title">Daftar Menu</h4>
                                @can('can_create', [request()])
                                    <button type="button" class="btn btn-sm btn-outline-success waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modalCenterLarge" onclick="show_tambah('Tambah Data Menu')">Tambah</button>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="tableMenu" class="table table-bordered dt-responsive  nowrap w-100">
                        <thead>
                            <tr>
                                <th  class="w-5">No</th>
                                <th  class="w-10">Nama</th>
                                <th  class="w-20">Route</th>
                                <th  class="w-30">URL</th>
                                <th  class="w-10">Icon</th>
                                <th  class="w-10">Tipe Menu</th>
                                <th  class="w-10">Status</th>
                                @if(auth()->user()->can('can_update', [request()]) || auth()->user()->can('can_delete', [request()]))
                                    <th  class="w-5">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($menus as $menu)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$menu->nama ??''}}</td>
                                    <td>{{$menu->route ??''}}</td>
                                    <td>{{$menu->url ??''}}</td>
                                    <td><i class="fa fa-{{$menu->icon}}"></i> {{$menu->icon}}</td>
                                    <td>{{$menu->tipe_menu ??''}}</td>
                                    <td>
                                        @if ($menu->is_active == true)
                                        <i class='fa fa-circle text-success'></i> Aktif
                                        @elseif ($menu->is_active == false)
                                            <i class='fa fa-circle text-danger'></i> Tidak Aktif
                                        @endif
                                    </td>
                                    @if(auth()->user()->can('can_update', [request()]) || auth()->user()->can('can_delete', [request()]))
                                        <td>
                                            @can('can_update', [request()])
                                                <button type="button" class="btn btn-sm btn-outline-warning waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modalCenterLarge" onclick="show_edit('Edit Menu',{{($menu->id)}})">
                                                    <i class='fa fa-pen'></i>
                                                </button>
                                            @endcan
                                            @can('can_delete', [request()])
                                                <button type='button' class='btn btn-sm btn-outline-danger waves-effect waves-light' title='Hapus' onClick="data_hapus({{$menu->id}},'{{$menu->nama}}')">
                                                    <i class='fa fa-trash'></i>
                                                </button>
                                            @endcan
                                        </td>
                                    @endcan
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection
@section('scriptbawah')
    <script>
        $(document).ready(function() {
            var table = $("#tableMenu").DataTable();
        });
        //OPEN MODAL TAMBAH DATA MENU
            function show_tambah(judul){
                $("#modalCenterLargeLabel").html(judul)
                $("#modalCenterLargeContent").html(`<div class="d-flex justify-content-center">
                                                <div class="spinner-border text-primary m-1" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </div>`);
                var act = "{{ route('menu.modal.create')}}";
                $.ajax({
                    url: act,
                    success: function(data){
                        $("#modalCenterLargeContent").html(data);
                    },
                    error: function(xhr, status, error) {
                        $("#modalCenterLargeContent").html(`<div class="modal-body d-flex justify-content-center align-items-center">
                                                                <img src="{{asset('admin_assets/images/error-img.png')}}" width="80%"class="text-center">
                                                            </div>`
                                                        );
                    }
                });
            }
        //CLOSE MODAL TAMBAH DATA MENU
        //OPEN MODAL EDIT DATA USER
            function show_edit(judul, param){
                $("#modalCenterLargeLabel").html(judul)
                $("#modalCenterLargeContent").html(`<div class="d-flex justify-content-center">
                                                <div class="spinner-border text-primary m-1" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </div>`);
                var act = "{{ route('menu.modal.edit', ['param' => ':param']) }}";
                    act = act.replace(':param', param);
                $.ajax({
                    url: act,
                    success: function(data){
                        $("#modalCenterLargeContent").html(data);
                    },
                    error: function(xhr, status, error) {
                        $("#modalCenterLargeContent").html(`<div class="modal-body d-flex justify-content-center align-items-center">
                                                                <img src="{{asset('admin_assets/images/error-img.png')}}" width="80%"class="text-center">
                                                            </div>`
                                                        );
                    }
                });
            }
        //CLOSE MODAL EDIT DATA USER
        //OPEN PROSES DELETE
            function data_hapus(id, menu) {
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: `Menu ${menu} akan dihapus termasuk data permission!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-outline-danger ms-1'
                    },
                    buttonsStyling: false
                }).then(function(result) {
                    if (result.value == true) {
                        var act = "{{ route('menu.delete', [':param']) }}";
                            act = act.replace(':param', id);
                        $.ajax({
                            url: act,
                            data:   {
                                _token: '{{ csrf_token() }}',
                            },
                            type: 'delete',
                            success: function(data) {
                                Swal.fire({
                                    icon: 'success',
                                    title: data.title,
                                    text: data.message,
                                    customClass: {
                                        confirmButton: 'btn btn-success'
                                    }
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.reload();
                                    }
                                });
                            },
                            error: function(xhr, status, error) {
                                var response = xhr.responseJSON;
                                Swal.fire({
                                    icon: 'error',
                                    title: response.title,
                                    title: response.message,
                                    text: '',
                                    customClass: {
                                        confirmButton: 'btn btn-danger'
                                    }
                                });
                            }
                        });
                    }
                });
            }
        //CLOSE PROSES DELETE
    </script>
@endsection
