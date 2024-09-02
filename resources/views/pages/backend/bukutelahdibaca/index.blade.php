@extends('layouts.backend')
@section('title','Buku Telah Dibaca')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18"></h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Umum</a></li>
                        <li class="breadcrumb-item active">Buku Telah Dibaca</li>
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
                                <h4 class="card-title">Buku Telah Dibaca</h4>
                                <div class="d-flex">
                                    <button type="button" class="btn btn-sm btn-info waves-effect waves-light mx-1" data-bs-toggle="modal" data-bs-target="#modalCenterStandart" onclick="show_import('Tambah Buku Telah Dibaca')">
                                        <i class='fa fa-upload'></i> Import
                                    </button>
                                    @can('can_create', [request()])
                                        <button type="button" class="btn btn-sm btn-outline-success waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modalCenterExtraLarge" onclick="show_tambah('Tambah Buku Telah Dibaca')">
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
                    <table id="table_buku_telah_dipinjam_rellarphp" class="table table-bordered dt-responsive  nowrap w-100">
                        <thead>
                            <tr>
                                <th  class="w-10">No</th>
                                <th  class="w-10">Tanggal</th>
                                <th  class="w-20">Buku</th>
                                <th  class="w-20">Jumlah</th>
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
                var table = $("#table_buku_telah_dipinjam_rellarphp").DataTable({
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
                        url: "{{route('bukutelahdibaca.datatable')}}",
                        type: 'GET',
                    },
                    initComplete: function () {
                        $("#filter_bulan").change(function() {
                            var filter_bulan = $('#filter_bulan').val();
                            table.ajax.url("{{ route('bukutelahdibaca.datatable') }}?filter_bulan=" + filter_bulan ).load();
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
                            data: 'tanggal',
                            className: 'text-start align-top'
                        },
                        {
                            data: 'buku',
                            className: 'text-start align-top',
                            render: function(data, type, row) {
                                return data ? data : '-';
                            }
                        },
                        {
                            data: 'jumlah',
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
                                            <button type="button" class="btn btn-sm btn-outline-warning waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modalCenterExtraLarge" onclick="show_edit('Edit Buku Telah Dibaca','${data.id}')">
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
        //OPEN MODAL IMPORT DATA BUKU TELAH DIBACA
            function show_import(judul){
                $("#modalCenterStandartLabel").html(judul)
                $("#modalCenterStandartContent").html(`<div class="d-flex justify-content-center">
                                                            <div class="spinner-border text-primary m-1" role="status">
                                                                <span class="sr-only">Loading...</span>
                                                            </div>
                                                        </div>`);
                var act = "{{ route('bukutelahdibaca.modal.import') }}";
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
        //CLOSE MODAL IMPORT DATA BUKU TELAH DIBACA
        //OPEN MODAL TAMBAH DATA BUKU TELAH DIBACA
            function show_tambah(judul){
                $("#modalCenterExtraLargeLabel").html(judul)
                $("#modalCenterExtraLargeContent").html(`<div class="d-flex justify-content-center">
                                                <div class="spinner-border text-primary m-1" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </div>`);
                var act = "{{ route('bukutelahdibaca.modal.create') }}";
                $.ajax({
                    url: act,
                    success: function(data){
                        $("#modalCenterExtraLargeContent").html(data);
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr, status, error)
                        $("#modalCenterExtraLargeContent").html(`<div class="modal-body d-flex justify-content-center align-items-center">
                                                                <img src="{{asset('admin_assets/images/error-img.png')}}" width="80%"class="text-center">
                                                            </div>`
                                                        );
                    }
                });
            }
        //CLOSE MODAL TAMBAH DATA BUKU TELAH DIBACA
        //OPEN MODAL EDIT DATA BUKU TELAH DIBACA
            function show_edit(judul, param){
                console.log(param)
                $("#modalCenterExtraLargeLabel").html(judul)
                $("#modalCenterExtraLargeContent").html(`<div class="d-flex justify-content-center">
                                                <div class="spinner-border text-primary m-1" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </div>`);
                var act = "{{ route('bukutelahdibaca.modal.edit', ['param' => ':param']) }}";
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
        //CLOSE MODAL EDIT DATA BUKU TELAH DIBACA
    </script>
@endsection
