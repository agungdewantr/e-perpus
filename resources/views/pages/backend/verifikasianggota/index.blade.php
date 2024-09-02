@extends('layouts.backend')
@section('title','Verifikasi Anggota')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18"></h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Umum</a></li>
                        <li class="breadcrumb-item active">Verifikasi Anggota</li>
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
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="card-title">Verifikasi Anggota</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="table_verifikasi_anggota_rellarphp" class="table table-bordered dt-responsive  nowrap w-100">
                        <thead>
                            <tr>
                                <th  class="w-1">No</th>
                                <th  class="w-5">Nama</th>
                                <th  class="w-5">Tempat, Tanggal Lahir</th>
                                <th  class="w-5">Jenis Kelamin</th>
                                <th  class="w-5">Nomor Identitas</th>
                                <th  class="w-5">Pekerjaan</th>
                                <th  class="w-5">Nomor Telepon</th>
                                <th  class="w-5">Email</th>
                                <th  class="w-5">Alamat</th>
                                <th  class="w-5">Tanggal Masuk</th>
                                <th  class="w-1">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection
@section('scriptbawah')
    <script>
        $(document).ready(function() {
            datatable();
        });
        //OPEN DATATABLE
            function datatable() {
                var table = $("#table_verifikasi_anggota_rellarphp").DataTable({
                    paging: true,
                    destroy: true,
                    info: true,
                    searching: true,
                    autoWidth: false,
                    processing: true,
                    serverSide: true,
                    "ordering": false,
                    responsive: false,
                    scrollX: true,
                    ajax: {
                        url: "{{route('verifikasianggota.datatable')}}",
                        type: 'GET',
                    },
                    columns: [
                        {
                            "data": null,
                            className: 'text-start align-top',
                            "sortable": false,
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1;
                            }
                        },
                        {
                            data: 'nama_lengkap',
                            className: 'text-start align-top'
                        },
                        {
                            data: 'ttl',
                            className: 'text-start align-top'
                        },
                        {
                            data: 'jenis_kelamin',
                            className: 'text-start align-top'
                        },
                        {
                            data: 'nomor_identitas',
                            className: 'text-start align-top',
                            render: function(data, type, row) {
                                return data ? data : '-';
                            }
                        },
                        {
                            data: 'pekerjaan',
                            className: 'text-start align-top',
                            render: function(data, type, row) {
                                return data ? data : '-';
                            }
                        },
                        {
                            data: 'nomor_telepon',
                            className: 'text-start align-top',
                            render: function(data, type, row) {
                                return data ? data : '-';
                            }
                        },
                        {
                            data: 'email',
                            className: 'text-start align-top',
                            render: function(data, type, row) {
                                return data ? data : '-';
                            }
                        },
                        {
                            data: 'alamat',
                            className: 'text-start align-top'
                        },
                        {
                            data: 'created_at',
                            className: 'text-start align-top'
                        },
                        {
                            data: null,
                            className: 'text-start align-top',
                            render: function(data, type, row) {
                                return `
                                        <div class="d-flex">
                                                <a type='button' class="btn btn-sm btn-outline-success rounded-circle mx-1 btn-block" title="Setuju" onclick="button_setuju_verified('${data.id}','${data.nama_lengkap}')">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                                <a type='button' class="btn btn-sm btn-outline-danger rounded-circle mx-1 btn-block" title="Tolak" onclick="button_tolak_verified('${data.id}','${data.nama_lengkap}')">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                        </div>
                                        `
                            }
                        }
                    ]
                });
                return table;
            }

        //CLOSE DATATABLE
        //OPEN TOLAK VERIFIED
            function button_setuju_verified(id, nama){
                event.preventDefault();
                Swal.fire({
                    title: 'Apakah Anda Yakin ?',
                    text: `Menyetujui verifikasi anggota dengan nama lengkap ${nama}.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Setuju!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-outline-secondary ms-1'
                    },
                    buttonsStyling: false
                }).then(function(result) {
                    if (result.value == true) {
                        var url = "{{ route('verifikasianggota.setujuverifikasi', ':id') }}";
                                    url = url.replace(':id', id);
                            $.ajax({
                                type: "GET",
                                url: url,
                                data:
                                {
                                    _token: '{{ csrf_token() }}',
                                },
                                success: function(data) {
                                    datatable();
                                    Swal.fire({
                                        icon: data.status,
                                        title: data.title,
                                        text: data.message,
                                        customClass: {
                                            confirmButton: 'btn btn-success'
                                        }
                                    });
                                },
                                error: function(data) {
                                    Swal.fire({
                                        icon: data.responseJSON.status,
                                        title: data.responseJSON.title,
                                        text: data.responseJSON.message,
                                        customClass: {
                                            confirmButton: 'btn btn-danger'
                                        }
                                    });
                                }
                            });
                    }
                });
            }
        //CLOSE TOLAK VERIFIED
        //OPEN TOLAK VERIFIED
            function button_tolak_verified(id, nama){
                event.preventDefault();
                Swal.fire({
                    title: 'Apakah Anda Yakin ?',
                    text: `Menolak verifikasi anggota dengan nama lengkap ${nama}.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Tolak!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-outline-secondary ms-1'
                    },
                    buttonsStyling: false
                }).then(function(result) {
                    if (result.value == true) {
                        var url = "{{ route('verifikasianggota.tolakverifikasi', ':id') }}";
                                    url = url.replace(':id', id);
                        $.ajax({
                            type: "GET",
                            url: url,
                            data:
                            {
                                _token: '{{ csrf_token() }}',
                            },
                            success: function(data) {
                                datatable();
                                Swal.fire({
                                    icon: data.status,
                                    title: data.title,
                                    text: data.message,
                                    customClass: {
                                        confirmButton: 'btn btn-success'
                                    }
                                });
                            },
                            error: function(data) {
                                Swal.fire({
                                    icon: data.responseJSON.status,
                                    title: data.responseJSON.title,
                                    text: data.responseJSON.message,
                                    customClass: {
                                        confirmButton: 'btn btn-danger'
                                    }
                                });
                            }
                        });
                    }
                });
            }
        //CLOSE TOLAK VERIFIED
    </script>
@endsection
