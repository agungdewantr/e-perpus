@extends('layouts.backend')
@section('title','Pengambilan Buku')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18"></h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Umum</a></li>
                        <li class="breadcrumb-item active">Pengambilan Buku</li>
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
                                <h4 class="card-title">Pengambilan Buku</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="table_pengambilan_rellarphp" class="table table-bordered dt-responsive  nowrap w-100">
                        <thead>
                            <tr>
                                <th class="w-1">No</th>
                                <th class="w-5">Nomor Anggota</th>
                                <th class="w-5">Nama</th>
                                <th class="w-5">Kode Buku</th>
                                <th class="w-5">Judul Buku</th>
                                <th class="w-5">Tanggal Pengajuan Peminjaman</th>
                                <th class="w-5">Konfirmasi</th>
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
                var table = $("#table_pengambilan_rellarphp").DataTable({
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
                        url: "{{route('pengambilanbuku.datatable')}}",
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
                            data: 'tanggal_pengajuan_peminjaman',
                            className: 'text-start align-top'
                        },
                        @can('can_update', [request()])
                            {
                                data: null,
                                className: 'text-start align-top',
                                render: function(data, type, row){
                                    if(data.status == 'Lewat Batas Waktu Pengambilan')
                                    {
                                        return `
                                            <div class="d-flex">
                                                <button class="btn btn-sm btn-outline-danger rounded-circle mx-1 btn-block" title="Lewat Batas Waktu Pengambilan" disabled>
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        `;
                                    }
                                    else{
                                        return `
                                            <div class="d-flex">
                                                <a type='button' class="btn btn-sm btn-outline-success rounded-circle mx-1 btn-block" title="Setuju" onclick="button_setuju_pengambilan('${data.id}','${data.judul}','${data.nama_lengkap}')">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            </div>
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
        //OPEN TERIMA PENGAJUAN PINJAMAN
            function button_setuju_pengambilan(id,judul ,nama){
                event.preventDefault();
                Swal.fire({
                    title: 'Apakah Anda Yakin ?',
                    html: `
                            Mengkonfirmasi pengambilan peminjaman dengan judul buku <b>${judul}</b><br>oleh <b>${nama}</b>.
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
                        var url = "{{ route('pengambilanbuku.setuju', ':id') }}";
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
        //CLOSE TERIMA PENGAJUAN PINJAMAN
    </script>
@endsection
