@extends('layouts.backend')
@section('title','Manajemen Buku')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18"></h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Umum</a></li>
                        <li class="breadcrumb-item active">Manajemen Buku</li>
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
                                <h4 class="card-title">Manajemen Buku</h4>
                                @can('can_create', [request()])
                                    <button type="button" class="btn btn-sm btn-outline-success waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modalCenterExtraLarge" onclick="show_tambah('Tambah Buku')">
                                        <i class='fa fa-plus'></i> Tambah
                                    </button>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="table_manajemen_buku_rellarphp" class="table table-bordered dt-responsive  nowrap w-100">
                        <thead>
                            <tr>
                                <th class="w-1">No</th>
                                <th class="w-5">Kode Buku</th>
                                <th class="w-5">Rak</th>
                                <th class="w-5">Gambar</th>
                                <th class="w-5">Judul Buku</th>
                                <th class="w-5">Penulis</th>
                                <th class="w-5">Penerbit</th>
                                <th class="w-5">ISBN</th>
                                <th class="w-5">Tanggal Terbit</th>
                                <th class="w-5">Jenis</th>
                                <th class="w-5">Jumlah</th>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // $('#kategori_id').select2();
            datatable();
        });
        //OPEN DATATABLE
            function datatable() {
                var table = $("#table_manajemen_buku_rellarphp").DataTable({
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
                        url: "{{route('manajemenbuku.datatable')}}",
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
                        data: 'kode_buku',
                        className: 'text-start align-top'
                    },
                    {
                        data: 'rak',
                        className: 'text-start align-top'
                    },
                    {
                        data: null,
                        className: 'text-start align-top',
                        render: function(data, type, row) {
                            if(data.cover){
                                return `<img src="{{ asset('storage/${data.cover.file_path}')}}" onerror="this.onerror=null; this.src='{{ asset('web_assets/images/books/grid/not-found-book.png')}}'" alt="" width="40px;">`;
                            }
                            else{
                                return `<img src="{{ asset('web_assets/images/books/grid/not-found-book.png')}}" alt="Cover" style="width: 40px;">`;
                            }
                        }
                    },
                    {
                        data: 'judul',
                        className: 'text-start align-top'
                    },
                    {
                        data: 'penulies',
                        className: 'text-start align-top',
                    },
                    {
                        data: 'penerbit.namaPenerbit',
                        className: 'text-start align-top',
                        render: function(data, type, row) {
                                return data ? data : '-';
                        }
                    },
                    {
                        data: 'isbn',
                        className: 'text-start align-top',
                        render: function(data, type, row) {
                                return data ? data : '-';
                        }
                    },
                    {
                        data: 'tanggal_terbit',
                        className: 'text-start align-top'
                    },
                    {
                        data: 'jenis',
                        className: 'text-start align-top'
                    },
                    {
                        data: 'jumlah',
                        className: 'text-start align-top'
                    },
                    {
                        data: null,
                        className: 'text-start align-top',
                        render: function(data, type, row){
                            return `
                            <div class="d-flex flex-column">
                                <div class="">
                                    <span class="badge badge-center rounded-pill bg-success mb-1">Aktif : ${data.jumlah_aktif} Buku</span>
                                </div>
                                <div class="">
                                    <span class="badge badge-center rounded-pill bg-danger mb-1">Tidak Aktif : Buku ${data.jumlah_tidak_aktif}</span>
                                </div>
                            </div>`
                        }
                    },
                    @can('can_update', [request()])
                    {
                        data: null,
                        className: 'text-start align-top',
                        render: function(data, type, row){
                            return `
                                    <button type="button" class="btn btn-sm btn-outline-warning waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modalCenterExtraLarge" onclick="show_edit('Edit Buku','${data.id}')">
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
                var act = "{{ route('manajemenbuku.modal.create') }}";
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
                var act = "{{ route('manajemenbuku.modal.edit', ['param' => ':param']) }}";
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
