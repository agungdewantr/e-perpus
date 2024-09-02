@extends('layouts.backend')
@section('title','Petugas Admin')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18"></h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Master Data</a></li>
                        <li class="breadcrumb-item active">Petugas</li>
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
                                <h4 class="card-title">Daftar Petugas</h4>
                                @can('can_create', [request()])
                                    <button type="button" class="btn btn-sm btn-outline-success waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modalCenterLarge" onclick="show_tambah('Tambah Petugas')"><i class='fas fa-plus'></i> Tambah</button>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="table_petugas_rellarphp" class="table table-bordered dt-responsive  nowrap w-100">
                        <thead>
                            <tr>
                                <th  class="w-5">No</th>
                                <th  class="w-10">NIP</th>
                                <th  class="w-20">Nama</th>
                                <th  class="w-10">Nomor Telepon</th>
                                <th  class="w-10">Jadwal Shift</th>
                                <th  class="w-10">Username</th>
                                <th  class="w-20">Email</th>
                                <th  class="w-10">Status</th>
                                @if(auth()->user()->can('can_update', [request()]) || auth()->user()->can('can_delete', [request()]))
                                    <th  class="w-5">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($profilPetugases as $profilPetugas)
                            {{-- @php
                                dd($profilPetugas);
                            @endphp --}}
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$profilPetugas->nip ?? '-'}}</td>
                                    <td>{{$profilPetugas->nama_lengkap ?? '-'}}</td>
                                    <td>{{$profilPetugas->nomor_telepon ?? '-'}}</td>
                                    <td>{{$profilPetugas->jadwal_shift_mulai ?? '00.00'}} - {{$profilPetugas->jadwal_shift_selesai ?? '00.00'}}</td>
                                    <td>{{$profilPetugas->user->username ?? '-'}}</td>
                                    <td>{{$profilPetugas->user->email ?? '-'}}</td>
                                    <td>
                                        @if($profilPetugas->user->is_active == true)
                                            <span class="badge badge-center rounded-pill bg-success mb-1">Aktif</span>
                                        @else
                                            <span class="badge badge-center rounded-pill bg-danger mb-1">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    @if(auth()->user()->can('can_update', [request()]) || auth()->user()->can('can_delete', [request()]))
                                        <td>
                                            @can('can_update', [request()])
                                                <button type="button" class="btn btn-sm btn-outline-warning waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modalCenterLarge" onclick="show_edit('Edit Petugas','{{encrypt($profilPetugas->id)}}')">
                                                    <i class='fa fa-pen'></i>
                                                </button>
                                            @endcan
                                            {{-- @can('can_delete', [request()])
                                                <button type='button' class='btn btn-sm btn-outline-danger waves-effect waves-light' title='Hapus' onClick="data_hapus('{{encrypt($profilPetugas->id)}}','{{$profilPetugas->user->username}}')">
                                                    <i class='fa fa-trash'></i>
                                                </button>
                                            @endcan --}}
                                        </td>
                                    @endif
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
            var table = $("#table_petugas_rellarphp").DataTable({
                responsive: false,
                scrollX: true
            });
        });
        //OPEN MODAL TAMBAH DATA PETUGAS ADMIN
            function show_tambah(judul){
                $("#modalCenterLargeLabel").html(judul)
                $("#modalCenterLargeContent").html(`<div class="d-flex justify-content-center">
                                                <div class="spinner-border text-primary m-1" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </div>`);
                var act = "{{ route('petugasadmin.modal.create')}}";
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
        //CLOSE MODAL TAMBAH DATA PETUGAS ADMIN
        //OPEN MODAL EDIT DATA PETUGAS ADMIN
            function show_edit(judul, param){
                $("#modalCenterLargeLabel").html(judul)
                $("#modalCenterLargeContent").html(`<div class="d-flex justify-content-center">
                                                <div class="spinner-border text-primary m-1" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </div>`);
                var act = "{{ route('petugasadmin.modal.edit', ['param' => ':param']) }}";
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
        //CLOSE MODAL EDIT DATA PETUGAS ADMIN
        //OPEN PROSES DELETE
            function data_hapus(id, username) {
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: `Akun ${username} akan dihapus!`,
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
                        var act = "{{ route('petugasadmin.delete', [':param']) }}";
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
