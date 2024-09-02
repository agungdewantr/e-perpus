@extends('layouts.backend')
@section('title', 'Prosedur')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18"></h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Master Data</a></li>
                        <li class="breadcrumb-item active">Prosedur</li>
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
                                <h4 class="card-title">Daftar Prosedur</h4>
                                @can('can_create', [request()])
                                    <button type="button" class="btn btn-sm btn-outline-success waves-effect waves-light"
                                        data-bs-toggle="modal" data-bs-target="#modalCenterLarge"
                                        onclick="show_tambah('Tambah Prosedur')">Tambah</button>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="table_rak_rellarphp" class="table table-bordered dt-responsive  nowrap w-100 text-start">
                        <thead>
                            <tr>
                                <th class="w-5">No</th>
                                <th class="w-80">Judul</th>
                                <th class="w-80">File Prosedur</th>
                                <th class="w-10">Status</th>
                                @if (auth()->user()->can('can_update', [request()]) ||
                                        auth()->user()->can('can_delete', [request()]))
                                    <th class="w-5">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
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
            var table = $("#table_rak_rellarphp").DataTable({
                fixedHeader: true,
                responsive: false,
                scrollX: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('prosedur.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'judul',
                        name: 'judul'
                    },
                    {
                        data: 'dokumen',
                        name: 'dokumen'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    @if (auth()->user()->can('can_update', [request()]) ||
                            auth()->user()->can('can_delete', [request()]))
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    @endif
                ]
            });
        });
        //OPEN MODAL TAMBAH DATA RAK
        function show_tambah(judul) {
            $("#modalCenterLargeLabel").html(judul)
            $("#modalCenterLargeContent").html(`<div class="d-flex justify-content-center">
                                                        <div class="spinner-border" role="status">
                                                            <span class="sr-only">Loading...</span>
                                                        </div>
                                                    </div>`);
            var act = "{{ route('prosedur.create') }}";
            $.ajax({
                url: act,
                success: function(data) {
                    $("#modalCenterLargeContent").html(data);
                },
                error: function(xhr, status, error) {
                    $("#modalCenterLargeContent").html(`<div class="modal-body d-flex justify-content-center align-items-center">
                                                                <img src="{{ asset('admin_assets/images/error-img.png') }}" width="80%"class="text-center">
                                                            </div>`);
                }
            });
        }
        //CLOSE MODAL TAMBAH DATA RAK
        //OPEN MODAL EDIT DATA RAK
        function show_edit(judul, param) {
            $("#modalCenterLargeLabel").html(judul)
            $("#modalCenterLargeContent").html(`<div class="d-flex justify-content-center">
                                                <div class="spinner-border text-primary m-1" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </div>`);
            var act = "{{ route('prosedur.edit', ['prosedur' => ':param']) }}";
            act = act.replace(':param', param);
            $.ajax({
                url: act,
                success: function(data) {
                    $("#modalCenterLargeContent").html(data);
                },
                error: function(xhr, status, error) {
                    $("#modalCenterLargeContent").html(`<div class="modal-body d-flex justify-content-center align-items-center">
                                                                <img src="{{ asset('admin_assets/images/error-img.png') }}" width="80%"class="text-center">
                                                            </div>`);
                }
            });
        }
        //CLOSE MODAL EDIT DATA RAK
    </script>
@endsection
