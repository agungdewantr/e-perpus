@extends('layouts.backend')
@section('title','Profil')
@section('scriptatas')
    <link rel="stylesheet" type="text/css" href="{{ asset('/') }}admin_assets/vendors/css/timeline/timeline.css">
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-9 col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm order-2 order-sm-1">
                            <div class="d-flex align-items-start mt-3 mt-sm-0">
                                <div class="flex-shrink-0">
                                    <img src="{{ $user->foto ? asset('storage/' . $user->foto->file_path) : Storage::url('user/default.png') }}" alt="" class="img-fluid avatar-lg me-3 rounded-circle" style="object-fit: cover;">
                                </div>
                                <div class="flex-grow-1">
                                    <div>
                                        <h5 class="font-size-16 mb-1">{{$user->username}}</h5>
                                        <p class="text-muted font-size-13">{{$user->role->nama}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-auto order-1 order-sm-2">
                            <div class="d-flex align-items-start justify-content-end gap-2">
                                <div>
                                    <div class="dropdown">
                                        <button class="btn btn-link font-size-16 shadow-none text-muted dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bx bx-dots-horizontal-rounded"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalCenterLarge" onclick="show_ubah_password('Ubah Password', {{$user->id}})"><i class="fas fa-key"></i> Ubah Password</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalCenterLarge" onclick="show_ubah_foto('Ubah Foto', {{$user->id}})"><i class="fas fa-user-circle"></i> Ubah Foto</a>
                                            </li>
                                            {{-- <li>
                                                <a class="dropdown-item" href="#"><i class="fas fa-user-edit"></i>Ubah Data Diri</a>
                                            </li> --}}
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->

            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between gap-2">
                        <h5 class="card-title mb-0">Informasi Profil</h5>
                        <button type="button" class="btn btn-sm btn-info waves-effect waves-light" id="buttonEdit" onclick="show_tambah('Tambah Rak', {{$user->id}})"><i class="fas fa-edit"></i> &nbsp; Ubah</button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="formEdit" class="d-none">
                        <form class="form" id="form_edit">
                            @csrf
                            <div class="pb-1">
                                <input type="hidden" class="form-control" name="id" id="id" value="{{ encrypt($user->id)}}">
                                <div class="row">
                                    <div class="col-xl-3">
                                        <div>
                                            <label for="nip" class="form-label"><b>Nomor Induk Pegawai</b></label>
                                        </div>
                                    </div>
                                    <div class="col-xl">
                                        <div class="text-muted">
                                            <input type="text" class="form-control" name="nip" id="nip" value="{{$user->profilPetugas->nip ?? null}}" placeholder="Masukkan Nomor Induk Pegawai">
                                            <small class="validation-nip text-danger"></small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="py-2">
                                <div class="row">
                                    <div class="col-xl-3">
                                        <div>
                                            <label for="nama_lengkap" class="form-label"><b>Nama Lengkap</b> <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-xl">
                                        <input type="text" class="form-control" name="nama_lengkap" id="nama_lengkap" value="{{$user->profilPetugas->nama_lengkap ?? null}}" placeholder="Masukkan Nama Lengkap" required>
                                        <small class="validation-nama_lengkap text-danger"></small>
                                    </div>
                                </div>
                            </div>

                            <div class="py-2">
                                <div class="row">
                                    <div class="col-xl-3">
                                        <div>
                                            <label for="nomor_telepon" class="form-label"><b>Nomor Telepon</b> <span class="text-danger">*</span></label>
                                        </div>
                                    </div>
                                    <div class="col-xl">
                                        <input type="text" class="form-control" name="nomor_telepon" id="nomor_telepon" value="{{$user->profilPetugas->nomor_telepon ?? null}}" placeholder="Masukkan Nomor Telepon" required>
                                        <small class="validation-nomor_telepon text-danger"></small>
                                    </div>
                                </div>
                            </div>
                            <div class="py-2">
                                <div class="row">
                                    <div class="col-xl-3">
                                        <div>
                                            <label for="jadwal_shift" class="form-label"><b>Jadwal Shift</b></label>
                                        </div>
                                    </div>
                                    <div class="col-xl">
                                        <div class="row">
                                            <div class="col-xl-6 col-md-12 mb-2">
                                                <input type="time" class="form-control" name="jadwal_shift_mulai" id="jadwal_shift_mulai" value="{{ \Carbon\Carbon::parse($user->profilPetugas->jadwal_shift_mulai ?? null)->format('H:i') }}">
                                                <small class="validation-jadwal_shift_mulai text-danger"></small>
                                            </div>
                                            <div class="col-xl-6 col-md-12">
                                                <input type="time" class="form-control" name="jadwal_shift_selesai" id="jadwal_shift_selesai" value="{{ \Carbon\Carbon::parse($user->profilPetugas->jadwal_shift_selesai ?? null)->format('H:i') }}">
                                                <small class="validation-jadwal_shift_selesai text-danger"></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-end">
                                <button type="button" class="btn btn-sm btn-secondary waves-effect text-left mx-2" id="buttonBatal">
                                    <i class="fa fa-times"></i> &nbsp; Batal
                                </button>
                                <button type="submit" class="btn btn-sm btn-success waves-effect text-left mx-2">
                                    <i class="fa fa-save"></i> &nbsp; Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                    <div id="contentEdit" class="">
                        <div>
                            <div class="pb-1">
                                <div class="row">
                                    <div class="col-xl-3">
                                        <div>
                                            <h5 class="font-size-15">Nomor Induk Pegawai :</h5>
                                        </div>
                                    </div>
                                    <div class="col-xl">
                                        <div class="text-muted">
                                            <p class="mb-0">
                                                {{$user->profilPetugas->nip ?? '-'}}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="py-2">
                                <div class="row">
                                    <div class="col-xl-3">
                                        <div>
                                            <h5 class="font-size-15">Nama Lengkap :</h5>
                                        </div>
                                    </div>
                                    <div class="col-xl">
                                        <div class="text-muted">
                                            <p class="mb-0">
                                                {{$user->profilPetugas->nama_lengkap ?? '-'}}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="py-2">
                                <div class="row">
                                    <div class="col-xl-3">
                                        <div>
                                            <h5 class="font-size-15">Nomor Telepon :</h5>
                                        </div>
                                    </div>
                                    <div class="col-xl">
                                        <div class="text-muted">
                                            <p class="mb-0">
                                                {{$user->profilPetugas->nomor_telepon ?? '-'}}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="py-2">
                                <div class="row">
                                    <div class="col-xl-3">
                                        <div>
                                            <h5 class="font-size-15">Jadwal Shift :</h5>
                                        </div>
                                    </div>
                                    <div class="col-xl">
                                        <div class="text-muted">
                                            <p class="mb-0">
                                                {{\Carbon\Carbon::parse($user->profilPetugas->jadwal_shift_mulai ?? null)->format('H:i')}} - {{\Carbon\Carbon::parse($user->profilPetugas->jadwal_shift_selesai ?? null)->format('H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->

        <div class="col-xl-3 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Aktivitas</h5>
                    {{-- <div class="d-flex flex-wrap gap-2 font-size-16"> --}}
                        <!-- Section: Timeline -->
                        <div class="timeline">
                            @forelse ($lastActivitys as $lastActivity)
                                <div class="tl-item">
                                    <div class="tl-dot {{ $lastActivity->description == 'Login' ? 'b-success' : ($lastActivity->description == 'Logout' ? 'b-danger' : 'b-warning') }}"></div>
                                    <div class="tl-content">
                                        <b>
                                            {{$lastActivity->description}}
                                        </b>
                                        <div class="tl-date text-muted">
                                            <div class="">
                                                {{$lastActivity->created_at->format('d F Y') }} - {{$lastActivity->created_at->format('h:i:s') }} WITA
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center">
                                    ~ Belum Ada Aktivitas ~
                                </div>
                            @endempty
                        </div>
                            </section>
                        <!-- Section: Timeline -->
                    {{-- </div> --}}
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>
@endsection
@section('scriptbawah')
    <script>
        $(document).ready(function() {

        });
        //OPEN MODAL EDIT PASSWORD USER
            function show_ubah_password(judul, param){
                $("#modalCenterLargeLabel").html(judul)
                $("#modalCenterLargeContent").html(`<div class="d-flex justify-content-center">
                                                <div class="spinner-border text-primary m-1" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </div>`);
                var act = "{{ route('profilParam.modal.editpassword', ['param' => ':param']) }}";
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
        //CLOSE MODAL EDIT PASSWORD USER
        //OPEN MODAL EDIT FOTO
            function show_ubah_foto(judul, param){
                $("#modalCenterLargeLabel").html(judul)
                $("#modalCenterLargeContent").html(`<div class="d-flex justify-content-center">
                                                <div class="spinner-border text-primary m-1" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </div>`);
                var act = "{{ route('profilParam.modal.editfoto', ['param' => ':param']) }}";
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
        //CLOSE MODAL EDIT FOTO
        //OPEN PROSES EDIT PROFIL
            function show_tambah(judul, param){
                $('#contentEdit').addClass('d-none');
                $('#buttonEdit').addClass('d-none');
                $('#formEdit').removeClass('d-none');
            };
        //CLOSE PROSES EDIT PROFIL
        //OPEN BATAL EDIT PROFIL
            $('#buttonBatal').on('click', function(){
                $('#contentEdit').removeClass('d-none');
                $('#buttonEdit').removeClass('d-none');
                $('#formEdit').addClass('d-none');
            });
        //CLOSE BATAL EDIT PROFIL
        //OPEN PROSES EDIT PROFIL
            $('#form_edit').on('submit', function(event){
                event.preventDefault();
                event.stopPropagation();

                idata = new FormData($('#form_edit')[0]);
                $.ajax({
                    type: "POST",
                    url: "{{ route('profilParam.updateprofil') }}",
                    data: idata,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function(data) {
                        $("#form_edit")[0].reset();
                        window.location.reload();

                        Swal.fire({
                            icon: 'success',
                            title: data.title,
                            text: data.message,
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        var response = xhr.responseJSON;
                        if(response.messageValidate['nip']){
                            $('.validation-nip').text(response.messageValidate['nip'][0]);
                        }
                        else{
                            $('.validation-nip').text('');
                        }
                        if(response.messageValidate['nama_lengkap']){
                            $('.validation-nama_lengkap').text(response.messageValidate['nama_lengkap'][0]);
                        }
                        else{
                            $('.validation-nama_lengkap').text('');
                        }
                        if(response.messageValidate['nomor_telepon']){
                            $('.validation-nomor_telepon').text(response.messageValidate['nomor_telepon'][0]);
                        }
                        else{
                            $('.validation-nomor_telepon').text('');
                        }
                        if(response.messageValidate['jadwal_shift_mulai']){
                            $('.validation-jadwal_shift_mulai').text(response.messageValidate['jadwal_shift_mulai'][0]);
                        }
                        else{
                            $('.validation-nama_lengkap').text('');
                        }
                        if(response.messageValidate['jadwal_shift_selesai']){
                            $('.validation-jadwal_shift_selesai').text(response.messageValidate['jadwal_shift_selesai'][0]);
                        }
                        else{
                            $('.validation-jadwal_shift_selesai').text('');
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
        //CLOSE PROSES EDIT PROFIL
    </script>
@endsection
