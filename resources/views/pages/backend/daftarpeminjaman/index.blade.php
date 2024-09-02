@extends('layouts.backend')
@section('title','Daftar Peminjaman')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18"></h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Umum</a></li>
                        <li class="breadcrumb-item active">Daftar Peminjaman</li>
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
                                <h4 class="card-title">Daftar Peminjaman</h4>
                                @can('can_create', [request()])
                                    <button type="button" class="btn btn-sm btn-outline-success waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modalCenterLarge" onclick="show_tambah('Tambah Daftar Peminjaman')"><i class="fas fa-plus"></i> Tambah</button>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="table_daftar_peminjaman_rellarphp" class="table table-bordered dt-responsive  nowrap w-100">
                        <thead>
                            <tr>
                                <th class="w-1">No</th>
                                <th class="w-5">Nomor Anggota</th>
                                <th class="w-5">Nama</th>
                                <th class="w-5">Kode Buku</th>
                                <th class="w-5">Judul Buku</th>
                                <th class="w-5">Tanggal Peminjaman</th>
                                <th class="w-5">Tanggal Batas Kembali</th>
                                <th class="w-5">Tanggal Pengembalian</th>
                                <th class="w-5">Status</th>
                                <th class="w-5">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection
@section('scriptbawah')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            datatable();
        });
        //OPEN DATATABLE
            function datatable() {
                var table = $("#table_daftar_peminjaman_rellarphp").DataTable({
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
                        url: "{{route('daftarpeminjaman.datatable')}}",
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
                            data: 'kode_buku',
                            className: 'text-start align-top'
                        },
                        {
                            data: 'judul',
                            className: 'text-start align-top'
                        },
                        {
                            data: 'tanggal_peminjaman',
                            className: 'text-start align-top'
                        },
                        {
                            data: 'tanggal_batas_kembali',
                            className: 'text-start align-top'
                        },
                        {
                            data: 'tanggal_pengembalian',
                            className: 'text-start align-top'
                        },
                        {
                            data: null,
                            className: 'text-start align-top',
                            render: function(data, type, row){
                                if(data.status == 'Belum Diambil'){
                                    return `<span class="badge badge-center rounded-pill bg-warning mb-1">${data.status}</span>`;
                                }
                                else if(data.status == 'Sedang Dipinjam'){
                                    return `<span class="badge badge-center rounded-pill bg-info mb-1">${data.status}</span>`;
                                }
                                else if(data.status == 'Belum Kembali' || data.status == 'Lewat Batas Waktu Pengambilan' ){
                                    return `<span class="badge badge-center rounded-pill bg-danger mb-1">${data.status}</span>`;
                                }
                                else if(data.status == 'Sudah Kembali'){
                                    return `<span class="badge badge-center rounded-pill bg-success mb-1">${data.status}</span>`;
                                }
                            }
                        },
                        @can('can_update', [request()])
                            {
                                data: null,
                                className: 'text-start align-top',
                                render: function(data, type, row){
                                    if(data.status == 'Sudah Kembali'){
                                        return `<button type="button" class="btn btn-sm btn-outline-info waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modalCenterLarge" onclick="show_detail('Detail Buku','${data.id}')">
                                                    <i class='fa fa-eye'></i>
                                                </button>`;
                                    }
                                    else{
                                        return `
                                                <button type="button" class="btn btn-sm btn-outline-warning waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modalCenterLarge" onclick="show_edit('Edit Buku','${data.id}')">
                                                    <i class='fa fa-edit'></i>
                                                </button>
                                        `;
                                    }
                                }
                            },
                        @endcan
                    ]
                });
                return table;
            }

        //CLOSE DATATABLE
        //OPEN MODAL TAMBAH DATA PEMINJAMAN
            function show_tambah(judul){
                $("#modalCenterLargeLabel").html(judul)
                $("#modalCenterLargeContent").html(`<div class="d-flex justify-content-center">
                                                <div class="spinner-border text-primary m-1" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </div>`);
                var act = "{{ route('daftarpeminjaman.modal.create') }}";
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
        //CLOSE MODAL TAMBAH DATA PEMINJAMAN
        //OPEN MODAL EDIT DATA PEMINJAMAN
            function show_edit(judul, param){
                $("#modalCenterLargeLabel").html(judul)
                $("#modalCenterLargeContent").html(`<div class="d-flex justify-content-center">
                                                <div class="spinner-border text-primary m-1" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </div>`);
                var act = "{{ route('daftarpeminjaman.modal.edit', ['param' => ':param']) }}";
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
        //CLOSE MODAL EDIT DATA PEMINJAMAN
        //OPEN MODAL DETAIL DATA PEMINJAMAN
            function show_detail(judul, param){
                $("#modalCenterLargeLabel").html(judul)
                $("#modalCenterLargeContent").html(`<div class="d-flex justify-content-center">
                                                <div class="spinner-border text-primary m-1" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </div>`);
                var act = "{{ route('daftarpeminjaman.modal.detail', ['param' => ':param']) }}";
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
        //CLOSE MODAL DETAIL DATA PEMINJAMAN
    </script>
@endsection
