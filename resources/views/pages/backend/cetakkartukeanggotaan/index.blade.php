@extends('layouts.backend')
@section('title','Cetak Kartu Keanggotaan')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18"></h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Umum</a></li>
                        <li class="breadcrumb-item active">Cetak Kartu Keanggotaan</li>
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
                                <h4 class="card-title">Cetak Kartu Anggota Perpustakaan</h4>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-5 col-md-12">
                                    <label for="nomor_anggota" class="">Filter Status Anggota <span class="text-danger">*</span></label>
                                    <select class="form-select" name="is_active" id="is_active">
                                        <option value="" readonly>-- Pilih Status --</option>
                                        <option value="1">Aktif</option>
                                        <option value="0">Tidak Aktif</option>
                                        <option value="">Aktif & Tidak Aktif</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="table_anggota_rellarphp" class="table table-bordered dt-responsive  nowrap w-100">
                        <thead>
                            <tr>
                                <th  class="w-1">No</th>
                                <th  class="w-5">Nomor Anggota</th>
                                <th  class="w-5">Nama</th>
                                <th  class="w-5">Tempat, Tanggal Lahir</th>
                                <th  class="w-5">Jenis Kelamin</th>
                                <th  class="w-5">Nomor Identitas</th>
                                <th  class="w-5">Pekerjaan</th>
                                <th  class="w-5">Nomor Telepon</th>
                                <th  class="w-5">Email</th>
                                <th  class="w-5">Alamat</th>
                                <th  class="w-5">Tanggal Masuk</th>
                                <th  class="w-5">Status</th>
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
            var table = $("#table_anggota_rellarphp").DataTable({
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
                    url: "{{route('cetakkartukeanggotaan.datatable')}}",
                    type: 'GET',
                },
                initComplete: function () {
                    $("#is_active").change(function() {
                        var is_active = $('#is_active').val();
                        table.ajax.url("{{ route('cetakkartukeanggotaan.datatable') }}?is_active=" + is_active ).load();
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
                    {
                        data: null,
                        className: 'text-start align-top',
                        render: function(data, type, row){
                            return `
                                    <button type="button" class="btn btn-sm btn-outline-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modalCenterLarge" onclick="show_card('Kartu Anggota Perpustakaan','${data.id}')">
                                        <i class='fa fa-eye'></i>
                                    </button>
                            `;
                        }
                    },
                ]
            });
        });
        //OPEN MODAL EDIT DATA USER
            function show_card(judul, param){
                $("#modalCenterLargeLabel").html(judul)
                $("#modalCenterLargeContent").html(`<div class="d-flex justify-content-center">
                                                <div class="spinner-border text-primary m-1" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </div>`);
                var act = "{{ route('cetakkartukeanggotaan.modal.detail', ['param' => ':param']) }}";
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
    </script>
@endsection
