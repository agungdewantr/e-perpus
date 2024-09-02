@extends('layouts.backend')
@section('title','Daftar Keanggotaan')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18"></h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Umum</a></li>
                        <li class="breadcrumb-item active">Daftar Keanggotaan</li>
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
                                <h4 class="card-title">Daftar Anggota</h4>
                                @can('can_create', [request()])
                                    <button type="button" class="btn btn-sm btn-outline-success waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modalCenterExtraLarge" onclick="show_tambah('Tambah Daftar Anggota')">
                                        <i class='fa fa-plus'></i> Tambah
                                    </button>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="table_daftar_keanggotaan_rellarphp" class="table table-bordered dt-responsive  nowrap w-100">
                        <thead>
                            <tr>
                                <th class="w-1">No</th>
                                <th class="w-5">Nomor Anggota</th>
                                <th class="w-5">Nama</th>
                                <th class="w-5">Tempat, Tanggal Lahir</th>
                                <th class="w-5">Jenis Kelamin</th>
                                <th class="w-5">Nomor Identitas</th>
                                <th class="w-5">Pekerjaan</th>
                                <th class="w-5">Nomor Telepon</th>
                                <th class="w-5">Email</th>
                                <th class="w-5">Alamat</th>
                                <th class="w-5">Tanggal Masuk</th>
                                <th class="w-5">Status</th>
                                @can('can_update', [request()])
                                    <th class="w-1">Aksi</th>
                                @endcan
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
                var table = $("#table_daftar_keanggotaan_rellarphp").DataTable({
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
                        url: "{{route('keanggotaan.datatable')}}",
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
                        data: 'nomor_anggota',
                        className: 'text-start align-top'
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
                        render: function(data, type, row){
                            if(data.is_active == 1){
                                return '<span class="badge badge-center rounded-pill bg-success mb-1">Aktif</span>'
                            }
                            else{
                                return '<span class="badge badge-center rounded-pill bg-danger mb-1">Tidak Aktif</span>'
                            }
                        }
                    },
                    @can('can_update', [request()])
                    {
                        data: null,
                        className: 'text-start align-top',
                        render: function(data, type, row){
                            return `
                                    <button type="button" class="btn btn-sm btn-outline-warning waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modalCenterExtraLarge" onclick="show_edit('Edit Anggota','${data.id}')">
                                        <i class='fa fa-edit'></i>
                                    </button>
                            `;
                        }
                    },
                    @endcan
                ]
                });
                return table;
            }

        //CLOSE DATATABLE
        //OPEN MODAL TAMBAH DATA USER ANGGOTA
            function show_tambah(judul){
                $("#modalCenterExtraLargeLabel").html(judul)
                $("#modalCenterExtraLargeContent").html(`<div class="d-flex justify-content-center">
                                                <div class="spinner-border text-primary m-1" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </div>`);
                var act = "{{ route('keanggotaan.modal.create') }}";
                $.ajax({
                    url: act,
                    success: function(data){
                        $("#modalCenterExtraLargeContent").html(data);
                    },
                    error: function(xhr, status, error) {
                        $("#modalCenterExtraLargeContent").html(`<div class="modal-body d-flex justify-content-center align-items-center">
                                                                <img src="{{asset('admin_assets/images/error-img.png')}}" width="80%"class="text-center">
                                                            </div>`
                                                        );
                    }
                });
            }
        //CLOSE MODAL TAMBAH DATA USER ANGGOTA
        //OPEN MODAL EDIT DATA USER ANGGOTA
            function show_edit(judul, param){
                $("#modalCenterExtraLargeLabel").html(judul)
                $("#modalCenterExtraLargeContent").html(`<div class="d-flex justify-content-center">
                                                <div class="spinner-border text-primary m-1" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </div>`);
                var act = "{{ route('keanggotaan.modal.edit', ['param' => ':param']) }}";
                    act = act.replace(':param', param);
                $.ajax({
                    url: act,
                    success: function(data){
                        $("#modalCenterExtraLargeContent").html(data);
                    },
                    error: function(xhr, status, error) {
                        $("#modalCenterExtraLargeContent").html(`<div class="modal-body d-flex justify-content-center align-items-center">
                                                                <img src="{{asset('admin_assets/images/error-img.png')}}" width="80%"class="text-center">
                                                            </div>`
                                                        );
                    }
                });
            }
        //CLOSE MODAL EDIT DATA USER ANGGOTA
    </script>
@endsection
