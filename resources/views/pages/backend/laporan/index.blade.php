@extends('layouts.backend')
@section('title', 'Laporan Buku Belum Kembali')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18"></h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Master Data</a></li>
                        <li class="breadcrumb-item active">Laporan</li>
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
                                <h4 class="card-title">Daftar Laporan</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="" method="GET" class="row g-3 mb-3" id="formFilter">
                        <div class="col-md-4 col-12">
                            <select name="jenis_laporan" id="jenis_laporan" class="form-select">
                                <option value="Laporan Buku">Laporan Buku</option>
                                <option value="Laporan Peminjaman">Laporan Peminjaman</option>
                                <option value="Laporan Pengunjung">Laporan Pengunjung</option>
                                <option value="Laporan Buku Belum Kembali">Laporan Buku Belum Kembali</option>
                                <option value="Laporan Buku Rusak">Laporan Buku Rusak</option>
                                <option value="Laporan Buku Telah Dibaca">Laporan Buku Telah Dibaca</option>
                            </select>
                        </div>
                        <div class="col-md-8 col-12"></div>
                        <div class="col-md-4 col-12">
                            <select name="bulan" id="bulan" class="form-select" disabled>
                                <option value="">-- Pilih Bulan --</option>
                                <option value="01">Januari</option>
                                <option value="02">Februari</option>
                                <option value="03">Maret</option>
                                <option value="04">April</option>
                                <option value="05">Mei</option>
                                <option value="06">Juni</option>
                                <option value="07">Juli</option>
                                <option value="08">Agustus</option>
                                <option value="09">September</option>
                                <option value="10">Oktober</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-12">
                            <select name="tahun" id="tahun" class="form-select" disabled>
                                <option value="">-- Pilih Tahun --</option>
                                @for ($i = 2019; $i <= (int) date('Y'); $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4 col-12">
                            <button type="submit" class="btn btn-primary">Cari</button>
                        </div>
                    </form>
                    <form action="{{ route('laporan.export') }}" method="POST" class="d-flex justify-content-end mb-3"
                        id="formExport">
                        @csrf
                        <input type="hidden" name="laporan" id="laporanExport">
                        <input type="hidden" name="tahun" id="tahunExport">
                        <input type="hidden" name="bulan" id="bulanExport">
                        <button class="btn btn-primary" type="submit"><i class="far fa-file-pdf"></i>&nbsp; Unduh PDF</button>
                    </form>
                    <div id="bukuDiv">
                        <table id="buku" class="table table-bordered dt-responsive nowrap w-100 text-start">
                            <thead>
                                <tr>
                                    <th class="w-5">No</th>
                                    <th class="w-80">Kode Buku</th>
                                    <th class="w-80">Rak</th>
                                    <th class="w-80">Judul Buku</th>
                                    <th class="w-80">Penulis</th>
                                    <th class="w-80">Penerbit</th>
                                    <th class="w-80">ISBN</th>
                                    <th class="w-80">Tanggal Terbit</th>
                                    <th class="w-80">Jenis</th>
                                    <th class="w-80">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div id="peminjamanDiv" class="d-none table-responsive">
                        <table id="peminjaman" class="table table-bordered dt-responsive nowrap w-100 text-start">
                            <thead>
                                <tr>
                                    <th class="w-5">No</th>
                                    <th class="w-80">Nomor Anggota</th>
                                    <th class="w-80">Nama</th>
                                    <th class="w-80">Kode Buku</th>
                                    <th class="w-80">Judul Buku</th>
                                    <th class="w-80">Tanggal Peminjaman</th>
                                    <th class="w-80">Tanggal Batas Kembali</th>
                                    <th class="w-80">Tanggal Pengembalian</th>
                                    <th class="w-80">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div id="pengunjungDiv" class="d-none">
                        <table id="pengunjung" class="table table-bordered dt-responsive nowrap w-100 text-start">
                            <thead>
                                <tr>
                                    <th class="w-5">No</th>
                                    <th class="w-80">Tanggal</th>
                                    <th class="w-50">Nomor Anggota</th>
                                    <th class="w-80">Nama</th>
                                    <th class="w-80">Jenis Kelamin</th>
                                    <th class="w-80">Keperluan</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div id="buku-belum-kembaliDiv" class="d-none">
                        <table id="buku-belum-kembali" class="table table-bordered dt-responsive nowrap w-100 text-start">
                            <thead>
                                <tr>
                                    <th class="w-5">No</th>
                                    <th class="w-80">Nomor Anggota</th>
                                    <th class="w-80">Nama</th>
                                    <th class="w-80">Kode Buku</th>
                                    <th class="w-80">Judul</th>
                                    <th class="w-80">Tanggal Peminjaman</th>
                                    <th class="w-80">Tanggal Batas Peminjaman</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div id="buku-rusakDiv" class="d-none">
                        <table id="buku-rusak" class="table table-bordered dt-responsive nowrap w-100 text-start">
                            <thead>
                                <tr>
                                    <th class="w-5">No</th>
                                    <th class="w-80">Buku</th>
                                    <th class="w-80">Rak</th>
                                    <th class="w-80">Judul Buku</th>
                                    <th class="w-80">Penulis</th>
                                    <th class="w-80">Penerbit</th>
                                    <th class="w-80">ISBN</th>
                                    <th class="w-80">Tanggal Terbit</th>
                                    <th class="w-80">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div id="buku-telahDibacaDiv" class="d-none">
                        <table id="buku-telah-dibaca" class="table table-bordered dt-responsive nowrap w-100 text-start">
                            <thead>
                                <tr>
                                    <th class="w-5">No</th>
                                    <th class="w-80">Tanggal</th>
                                    <th class="w-80">Buku</th>
                                    <th class="w-80">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection
@section('scriptbawah')
    <script>
        var laporanBuku = null
        var laporanPengunjung = null
        var laporanPeminjaman = null
        var laporanBelumKembali = null
        var laporanBukuRusak = null
        var laporanBukuTelahDibaca = null
        $(document).ready(function() {
            $('#jenis_laporan').val('Laporan Buku');
            $('#bulan').val('');
            $('#bulan').prop('disabled', true);
            $('#tahun').prop('disabled', true);

            laporanBuku = $("#buku").DataTable({
                fixedHeader: true,
                responsive: false,
                scrollX: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('laporan.index') }}",
                    data: function(d) {
                        return $.extend({}, d, {
                            "jenis_laporan": $('#jenis_laporan').val(),
                            "bulan": $('#bulan').val(),
                            "tahun": $('#tahun').val()
                        });
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'kode',
                        name: 'kode'
                    },
                    {
                        data: 'rak',
                        name: 'rak'
                    },
                    {
                        data: 'judul',
                        name: 'judul'
                    },
                    {
                        data: 'penulis',
                        name: 'penulis'
                    },
                    {
                        data: 'penerbit',
                        name: 'penerbit'
                    },
                    {
                        data: 'isbn',
                        name: 'isbn'
                    },
                    {
                        data: 'tgl_terbit',
                        name: 'tgl_terbit'
                    },
                    {
                        data: 'jenis',
                        name: 'jenis'
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah'
                    },
                ],
            });
        });

        $("#formFilter").submit(function(e) {
            e.preventDefault()
            $("#bukuDiv").addClass('d-none')
            $("#peminjamanDiv").addClass('d-none')
            $("#pengunjungDiv").addClass('d-none')
            $("#buku-belum-kembaliDiv").addClass('d-none')

            if ($("#jenis_laporan").val() == 'Laporan Buku') {
                $("#bukuDiv").removeClass('d-none')
                $("#buku").DataTable().draw()
            }
            if ($("#jenis_laporan").val() == 'Laporan Peminjaman') {
                $("#peminjamanDiv").removeClass('d-none')
                if (laporanPeminjaman === null) {
                    laporanPeminjaman = $("#peminjaman").DataTable({
                        fixedHeader: true,
                        responsive: false,
                        processing: true,
                        serverSide: true,
                        scrollX: true,
                        ajax: {
                            url: "{{ route('laporan.index') }}",
                            data: function(d) {
                                return $.extend({}, d, {
                                    "jenis_laporan": $('#jenis_laporan').val(),
                                    "bulan": $('#bulan').val(),
                                    "tahun": $('#tahun').val()
                                });
                            }
                        },
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex'
                            },
                            {
                                data: 'nomor_anggota',
                                name: 'nomor_anggota'
                            },
                            {
                                data: 'nama',
                                name: 'nama'
                            },
                            {
                                data: 'kode_buku',
                                name: 'kode_buku'
                            },
                            {
                                data: 'judul',
                                name: 'judul'
                            },
                            {
                                data: 'tgl_peminjaman',
                                name: 'tgl_peminjaman'
                            },
                            {
                                data: 'tgl_batas_kembali',
                                name: 'tgl_batas_kembali'
                            },
                            {
                                data: 'tgl_pengembalian',
                                name: 'tgl_pengembalian'
                            },
                            {
                                data: 'status',
                                name: 'status'
                            },
                        ],
                    });
                } else {
                    $("#peminjaman").DataTable().draw()
                }
            }
            if ($("#jenis_laporan").val() == 'Laporan Pengunjung') {
                $("#pengunjungDiv").removeClass('d-none')
                if (laporanPengunjung === null) {
                    laporanPengunjung = $("#pengunjung").DataTable({
                        fixedHeader: true,
                        responsive: false,
                        scrollX: true,
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: "{{ route('laporan.index') }}",
                            data: function(d) {
                                return $.extend({}, d, {
                                    "jenis_laporan": $('#jenis_laporan').val(),
                                    "bulan": $('#bulan').val(),
                                    "tahun": $('#tahun').val()
                                });
                            }
                        },
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex'
                            },
                            {
                                data: 'tanggal_kunjungan',
                                name: 'tanggal_kunjungan'
                            },
                            {
                                data: 'nomor_anggota',
                                name: 'nomor_anggota'
                            },
                            {
                                data: 'nama',
                                name: 'nama'
                            },
                            {
                                data: 'jenis_kelamin',
                                name: 'jenis_kelamin'
                            },
                            {
                                data: 'keperluan',
                                name: 'keperluan'
                            },
                        ],
                    });
                } else {
                    $("#pengunjung").DataTable().draw()
                }
            }
            if ($("#jenis_laporan").val() == 'Laporan Buku Belum Kembali') {
                $("#buku-belum-kembaliDiv").removeClass('d-none')
                if (laporanBelumKembali === null) {
                    laporanBelumKembali = $("#buku-belum-kembali").DataTable({
                        fixedHeader: true,
                        responsive: false,
                        scrollX: true,
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: "{{ route('laporan.index') }}",
                            data: function(d) {
                                return $.extend({}, d, {
                                    "jenis_laporan": $('#jenis_laporan').val(),
                                    "bulan": $('#bulan').val(),
                                    "tahun": $('#tahun').val()
                                });
                            }
                        },
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex'
                            },
                            {
                                data: 'nomor_anggota',
                                name: 'nomor_anggota'
                            },
                            {
                                data: 'nama',
                                name: 'nama'
                            },
                            {
                                data: 'kode_buku',
                                name: 'kode_buku'
                            },
                            {
                                data: 'judul',
                                name: 'judul'
                            },
                            {
                                data: 'tanggal_pengambilan_pinjaman',
                                name: 'tanggal_pengambilan_pinjaman'
                            },
                            {
                                data: 'tanggal_batas_pinjaman',
                                name: 'tanggal_batas_pinjaman'
                            },
                        ],
                    });
                } else {
                    $("#buku-belum-kembali").DataTable().draw()
                }
            }
            if ($("#jenis_laporan").val() == 'Laporan Buku Rusak') {
                $("#buku-rusakDiv").removeClass('d-none')
                if (laporanBukuRusak === null) {
                    laporanBukuRusak = $("#buku-rusak").DataTable({
                        fixedHeader: true,
                        responsive: false,
                        scrollX: true,
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: "{{ route('laporan.index') }}",
                            data: function(d) {
                                return $.extend({}, d, {
                                    "jenis_laporan": $('#jenis_laporan').val(),
                                    "bulan": $('#bulan').val(),
                                    "tahun": $('#tahun').val()
                                });
                            }
                        },
                        columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'kode',
                        name: 'kode'
                    },
                    {
                        data: 'rak',
                        name: 'rak'
                    },
                    {
                        data: 'judul',
                        name: 'judul'
                    },
                    {
                        data: 'penulis',
                        name: 'penulis'
                    },
                    {
                        data: 'penerbit',
                        name: 'penerbit'
                    },
                    {
                        data: 'isbn',
                        name: 'isbn'
                    },
                    {
                        data: 'tgl_terbit',
                        name: 'tgl_terbit'
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah'
                    },
                        ],
                    });
                } else {
                    $("#buku-rusak").DataTable().draw()
                }
            }
            if ($("#jenis_laporan").val() == 'Laporan Buku Telah Dibaca') {
                $("#buku-telahDibacaDiv").removeClass('d-none')
                if (laporanBukuRusak === null) {
                    laporanBukuRusak = $("#buku-telah-dibaca").DataTable({
                        fixedHeader: true,
                        responsive: false,
                        scrollX: true,
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: "{{ route('laporan.index') }}",
                            data: function(d) {
                                return $.extend({}, d, {
                                    "jenis_laporan": $('#jenis_laporan').val(),
                                    "bulan": $('#bulan').val(),
                                    "tahun": $('#tahun').val()
                                });
                            }
                        },
                        columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'buku',
                        name: 'buku'
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah'
                    },
                        ],
                    });
                } else {
                    $("#buku-telah-dibaca").DataTable().draw()
                }
            }
        })

        $("#jenis_laporan").change(function() {
            if ($(this).val() == 'Laporan Buku') {
                $("#bulan").attr('disabled', true)
                $("#tahun").attr('disabled', true)
            } else {
                $("#bulan").attr('disabled', false)
                $("#tahun").attr('disabled', false)
            }
        })

        $("#formExport").submit(function(e) {
            e.preventDefault()
            $("#laporanExport").val($("#jenis_laporan").val())
            $("#bulanExport").val($("#bulan").val())
            $("#tahunExport").val($("#tahun").val())
            e.currentTarget.submit()
        })
    </script>
@endsection
