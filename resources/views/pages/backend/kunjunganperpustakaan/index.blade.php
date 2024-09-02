@extends('layouts.backend')
@section('title','Kunjungan Perpustakaan')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18"></h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Umum</a></li>
                        <li class="breadcrumb-item active">Kunjungan Perpustakaan</li>
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
                                <h4 class="card-title">Kunjungan Perpustakaan</h4>
                                <div class="d-flex">
                                    <button type="button" class="btn btn-sm btn-info waves-effect waves-light mx-1" data-bs-toggle="modal" data-bs-target="#modalCenterStandart" onclick="show_import('Tambah Kunjungan Tamu')">
                                        <i class='fa fa-upload'></i> Import
                                    </button>
                                    @can('can_create', [request()])
                                        <button type="button" class="btn btn-sm btn-outline-success waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modalCenterLarge" onclick="show_tambah('Tambah Kunjungan Tamu')">
                                            <i class='fa fa-plus'></i> Tambah
                                        </button>
                                    @endcan
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-5 col-md-12">
                                    <label for="filter_bulan" class="">Filter Bulan <span class="text-danger">*</span></label>
                                    <select class="form-control" name="filter_bulan" id="filter_bulan">
                                        <option value="" readonly>-- Pilih Bulan --</option>
                                        @foreach(\Carbon\CarbonPeriod::create('2023-01-01', '1 month', now()) as $date)
                                            <option value="{{ $date->format('Y-m') }}">
                                                {{ $date->format('F Y') }}
                                            </option>
                                        @endforeach
                                        <option value="" readonly>Semua Bulan</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="table_kunjungan_tamu_rellarphp" class="table table-bordered dt-responsive  nowrap w-100">
                        <thead>
                            <tr>
                                <th  class="w-10">No</th>
                                <th  class="w-10">Tanggal</th>
                                <th  class="w-20">Nomor Anggota</th>
                                <th  class="w-20">Nama</th>
                                <th  class="w-10">Jenis Kelamin</th>
                                <th  class="w-20">Keperluan</th>
                                @can('can_update', [request()])
                                    <th  class="w-10">Aksi</th>
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
                var table = $("#table_kunjungan_tamu_rellarphp").DataTable({
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
                        url: "{{route('kunjunganperpustakaan.datatable')}}",
                        type: 'GET',
                    },
                    initComplete: function () {
                        $("#filter_bulan").change(function() {
                            var filter_bulan = $('#filter_bulan').val();
                            table.ajax.url("{{ route('kunjunganperpustakaan.datatable') }}?filter_bulan=" + filter_bulan ).load();
                        });
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
                            data: 'tanggal_kunjungan',
                            className: 'text-start align-top'
                        },
                        {
                            data: 'nomor_anggota',
                            className: 'text-start align-top',
                            render: function(data, type, row) {
                                return data ? data : '-';
                            }
                        },
                        {
                            data: 'nama_lengkap',
                            className: 'text-start align-top',
                            render: function(data, type, row) {
                                return data ? data : '-';
                            }
                        },
                        {
                            data: 'jenis_kelamin',
                            className: 'text-start align-top',
                            render: function(data, type, row) {
                                return data ? data : '-';
                            }
                        },
                        {
                            data: 'keperluan',
                            className: 'text-start align-top',
                            render: function(data, type, row) {
                                return data ? data : '-';
                            }
                        },
                        @can('can_update', [request()])
                        {
                            data: null,
                            className: 'text-start align-top',
                            render: function(data, type, row) {
                                return `
                                            <button type="button" class="btn btn-sm btn-outline-warning waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modalCenterLarge" onclick="show_edit('Edit Kunjungan Perpustakaan','${data.id}')">
                                                <i class='fa fa-edit'></i>
                                            </button>
                                        `
                            }
                        }
                        @endcan
                    ]
                });
                return table;
            }

        //CLOSE DATATABLE
        //OPEN MODAL IMPORT DATA KUNJUNGAN PERPUSTAKAAN
            function show_import(judul){
                $("#modalCenterStandartLabel").html(judul)
                $("#modalCenterStandartContent").html(`<div class="d-flex justify-content-center">
                                                            <div class="spinner-border text-primary m-1" role="status">
                                                                <span class="sr-only">Loading...</span>
                                                            </div>
                                                        </div>`);
                var act = "{{ route('kunjunganperpustakaan.modal.import') }}";
                $.ajax({
                    url: act,
                    success: function(data){
                        $("#modalCenterStandartContent").html(data);
                    },
                    error: function(xhr, status, error) {
                        $("#modalCenterStandartContent").html(`<div class="modal-body d-flex justify-content-center align-items-center">
                                                                <img src="{{asset('admin_assets/images/error-img.png')}}" width="80%"class="text-center">
                                                            </div>`
                                                        );
                    }
                });
            }
        //CLOSE MODAL IMPORT DATA KUNJUNGAN PERPUSTAKAAN
        //OPEN MODAL TAMBAH DATA KUNJUNGAN PERPUSTAKAAN
            function show_tambah(judul){
                $("#modalCenterLargeLabel").html(judul)
                $("#modalCenterLargeContent").html(`<div class="d-flex justify-content-center">
                                                <div class="spinner-border text-primary m-1" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </div>`);
                var act = "{{ route('kunjunganperpustakaan.modal.create') }}";
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
        //CLOSE MODAL TAMBAH DATA KUNJUNGAN PERPUSTAKAAN
        //OPEN MODAL EDIT DATA KUNJUNGAN PERPUSTAKAAN
            function show_edit(judul, param){
                $("#modalCenterLargeLabel").html(judul)
                $("#modalCenterLargeContent").html(`<div class="d-flex justify-content-center">
                                                <div class="spinner-border text-primary m-1" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </div>`);
                var act = "{{ route('kunjunganperpustakaan.modal.edit', ['param' => ':param']) }}";
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
        //CLOSE MODAL EDIT DATA KUNJUNGAN PERPUSTAKAAN
    </script>
@endsection
