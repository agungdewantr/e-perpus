@extends('layouts.backend')
@section('title','Perpanjangan Buku')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18"></h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Umum</a></li>
                        <li class="breadcrumb-item active">Perpanjangan Buku</li>
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
                                <h4 class="card-title">Perpanjangan Buku</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="table_perpanjangan_rellarphp" class="table table-bordered dt-responsive  nowrap w-100">
                        <thead>
                            <tr>
                                <th class="w-1">No</th>
                                <th class="w-5">Nomor Anggota</th>
                                <th class="w-5">Nama</th>
                                <th class="w-5">Kode Buku</th>
                                <th class="w-5">Judul Buku</th>
                                <th class="w-5">Tanggal Peminjaman</th>
                                <th class="w-5">Tanggal Batas Kembali</th>
                                <th class="w-5">Tanggal Batas Kembali Setelah Perpanjangan</th>
                                <th class="w-5">Setuju/Tidak</th>
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
                var table = $("#table_perpanjangan_rellarphp").DataTable({
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
                        url: "{{route('perpanjanganbuku.datatable')}}",
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
                            data: 'tanggal_batas_kembali_setelah_perpanjangan',
                            className: 'text-start align-top'
                        },
                        @can('can_update', [request()])
                            {
                                data: null,
                                className: 'text-start align-top',
                                render: function(data, type, row){
                                    return `
                                            <div class="d-flex">
                                                <a type='button' class="btn btn-sm btn-outline-success rounded-circle mx-1 btn-block" title="Setuju" onclick="button_setuju('${data.id}','${data.judul}','${data.nama_lengkap}')">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                                <a type='button' class="btn btn-sm btn-outline-danger rounded-circle mx-1 btn-block" title="Tolak" onclick="button_tolak('${data.id}','${data.judul}','${data.nama_lengkap}')">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </div>
                                    `;
                                }
                            },
                        @endcan
                    ]
                });
                return table;
            }

        //CLOSE DATATABLE
        //OPEN TOLAK VERIFIED
            function button_setuju(id, judul, nama){
                event.preventDefault();
                Swal.fire({
                    title: 'Apakah Anda Yakin ?',
                    html: `
                            Menyetujui perpanjangan pinjaman dengan judul buku <b>${judul}</b><br>oleh <b>${nama}</b>.
                            <br><br>
                            Batas pengembalian buku pada tanggal <br><b class="text-danger">{{ now()->addDay(7)->format('d F Y') }}</b>.
                        `,
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
                        var url = "{{ route('perpanjanganbuku.setuju', ':id') }}";
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
            function button_tolak(id, judul ,nama){
                event.preventDefault();
                Swal.fire({
                    title: 'Apakah Anda Yakin ?',
                    html: `
                            Menolak perpanjangan pinjaman dengan judul buku <b>${judul}</b><br>oleh <b>${nama}</b>.
                        `,
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
                        var url = "{{ route('perpanjanganbuku.tolak', ':id') }}";
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
