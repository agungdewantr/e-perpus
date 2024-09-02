<!DOCTYPE html>
<html lang="en">

<head>
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <div class="text-center">
        <img src="{{ public_path('web_assets/images/Logo-toli-2.png') }}" height="50" alt="Logo Kiri" style="margin-left: 25px">
        <img src="{{ public_path('web_assets/images/logo-dark.png') }}" height="50" alt="Logo Kanan" style="margin-left: 50px;">
    </div>
    <hr style="border: 1px solid #000000; flex-grow: 1; margin-top: 25px; margin-bottom: 25px;">
    <div>
        @if ($laporan == 'Laporan Buku')
            <h4 class="text-center mb-3">Laporan Buku</h4>
            <table class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th style="font-size: 12px; width:2%" class="text-center">No</th>
                        <th style="font-size: 12px; width:10%" class="text-center">Kategori</th>
                        <th style="font-size: 12px; width:10%" class="text-center">Rak</th>
                        <th style="font-size: 12px; width:20%" class="text-center">Judul Buku</th>
                        <th style="font-size: 12px; width:20%" class="text-center">Penulis</th>
                        <th style="font-size: 12px; width:10%" class="text-center">Penerbit</th>
                        <th style="font-size: 12px; width:10%" class="text-center">ISBN</th>
                        <th style="font-size: 12px; width:10%" class="text-center">Tanggal Terbit</th>
                        <th style="font-size: 12px; width:6%" class="text-center">Jenis</th>
                        <th style="font-size: 12px; width:2%" class="text-center">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td class="" style="font-size: 12px">{{ $loop->iteration }}</td>
                            <td class="" style="font-size: 12px">{{ $item->kode_buku }}</td>
                            <td class="" style="font-size: 12px">{{ $item->rak->kode ?? '-' }}</td>
                            <td class="" style="font-size: 12px">{{ $item->judul }}</td>
                            <td class="" style="font-size: 12px">{{ $item->penulises->pluck('nama')->join(', ') }}
                            </td>
                            <td class="" style="font-size: 12px">{{ $item->penerbit->namaPenerbit }}</td>
                            <td class="" style="font-size: 12px">{{ $item->isbn }}</td>
                            <td class="text-nowrap " style="font-size: 12px">
                                {{ \Carbon\Carbon::parse($item->tgl_terbit)->isoFormat('DD/MM/YYYY') }}</td>
                            <td class="" style="font-size: 12px">{{ $item->jenis }}</td>
                            <td class="" style="font-size: 12px">{{ $item->jumlah }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @elseif($laporan == 'Laporan Peminjaman')
            <h4 class="text-center mb-3">Laporan Peminjaman</h4>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="font-size: 12px" class="text-center align-middle">No</th>
                        <th style="font-size: 12px" class="text-center align-middle">Nomor Anggota</th>
                        <th style="font-size: 12px; width: 15%" class="text-center align-middle">Nama</th>
                        <th style="font-size: 12px" class="text-center align-middle">Kode Buku</th>
                        <th style="font-size: 12px; width: 25%" class="text-center align-middle">Judul</th>
                        <th style="font-size: 12px" class="text-center align-middle">Tanggal Peminjaman</th>
                        <th style="font-size: 12px" class="text-center align-middle">Tanggal Batas Kembali</th>
                        <th style="font-size: 12px" class="text-center align-middle">Tanggal Pengembalian</th>
                        <th style="font-size: 12px" class="text-center align-middle">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td class="" style="font-size: 12px">{{ $loop->iteration }}</td>
                            <td class="text-nowrap " style="font-size: 12px">{{ $item->profilAnggota->nomor_anggota }}</td>
                            <td class="" style="font-size: 12px">{{ $item->profilAnggota->nama_lengkap }}</td>
                            <td class="text-nowrap " style="font-size: 12px">{{ $item->kode_buku }}</td>
                            <td class="" style="font-size: 12px">{{ $item->itemBuku->buku->judul }}</td>
                            <td class="text-nowrap " style="font-size: 12px">
                                {{ $item->tanggal_pengambilan_pinjaman ? \Carbon\Carbon::parse($item->tanggal_pengambilan_pinjaman)->isoFormat('DD MMMM YYYY') : '-' }}
                            </td>
                            <td class="text-nowrap " style="font-size: 12px">
                                @if ($item->tanggal_batas_pinjaman)
                                    @if ($item->is_persetujuan_permohoman_perpanjangan == true)
                                        {{ \Carbon\Carbon::parse($item->tanggal_batas_pinjaman_perpanjangan)->isoFormat('DD MMMM YYYY') }}
                                    @else
                                        {{ \Carbon\Carbon::parse($item->tanggal_batas_pinjaman)->isoFormat('DD MMMM YYYY') }}
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-nowrap " style="font-size: 12px">
                                {{ $item->tanggal_pengembalian_pinjaman ? \Carbon\Carbon::parse($item->tanggal_pengembalian_pinjaman)->isoFormat('DD MMMM YYYY') : '-' }}
                            </td>
                            <td class="text-nowrap " style="font-size: 12px">
                                {{ $item->status }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @elseif($laporan == 'Laporan Pengunjung')
            <h4 class="text-center mb-3">Laporan Pengunjung</h4>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="font-size: 12px; width: 5%" class="text-center">No</th>
                        <th style="font-size: 12px; width: 10%" class="text-center">Tanggal</th>
                        <th style="font-size: 12px; width: 10%" class="text-center">Nomor Anggota</th>
                        <th style="font-size: 12px; width: 20%" class="text-center">Nama</th>
                        <th style="font-size: 12px; width: 15%" class="text-center">Jenis Kelamin</th>
                        <th style="font-size: 12px; width: 40%" class="text-center">Keperluan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td class="" style="font-size: 12px">{{ $loop->iteration }}</td>
                            <td class="text-nowrap " style="font-size: 12px">
                                {{ \Carbon\Carbon::parse($item->tanggal_kunjungan)->isoFormat('DD MMMM YYYY') }}
                            </td>
                            <td class="" style="font-size: 12px">{{ $item->profilAnggota->nomor_anggota ?? '-' }}
                            </td>
                            <td class="text-nowrap " style="font-size: 12px">
                                {{ $item->profilAnggota->nama_lengkap ?? $item->nama_lengkap }}</td>
                            <td class="" style="font-size: 12px">{{ $item->jenis_kelamin }}</td>
                            <td class="" style="font-size: 12px">{{ $item->keperluan }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @elseif($laporan == 'Laporan Buku Belum Kembali')
            <h4 class="text-center mb-3">Laporan Buku Belum Kembali</h4>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="font-size: 12px; width; 5%" class="text-center align-middle">No</th>
                        <th style="font-size: 12px; width; 10%" class="text-center align-middle">Nomor Anggota</th>
                        <th style="font-size: 12px; width: 25%" class="text-center align-middle">Nama</th>
                        <th style="font-size: 12px; width: 10%" class="text-center align-middle">Kode Buku</th>
                        <th style="font-size: 12px; width: 30%" class="text-center align-middle">Judul</th>
                        <th style="font-size: 12px; width: 10%" class="text-center align-middle">Tanggal Peminjaman</th>
                        <th style="font-size: 12px; width: 10%" class="text-center align-middle">Tanggal Batas Pengembalian</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td class="" style="font-size: 12px">{{ $loop->iteration }}</td>
                            <td class="text-nowrap " style="font-size: 12px">{{ $item->profilAnggota->nomor_anggota }}
                            </td>
                            <td class="" style="font-size: 12px">{{ $item->profilAnggota->nama_lengkap }}</td>
                            <td class="text-nowrap " style="font-size: 12px">{{ $item->kode_buku }}</td>
                            <td class="" style="font-size: 12px">{{ $item->itemBuku->buku->judul }}</td>
                            <td class="text-nowrap " style="font-size: 12px">
                                {{ \Carbon\Carbon::parse($item->tanggal_pengambilan_pinjaman)->isoFormat('DD MMMM YYYY') }}</td>
                            <td class="text-nowrap " style="font-size: 12px">
                                {{ \Carbon\Carbon::parse($item->tanggal_batas_pinjaman)->isoFormat('DD MMMM YYYY') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @elseif ($laporan == 'Laporan Buku Rusak')
            <h4 class="text-center mb-3">Laporan Buku Rusak</h4>
            <table class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th style="font-size: 12px; width:2%" class="text-center">No</th>
                        <th style="font-size: 12px; width:10%" class="text-center">Kode Buku</th>
                        <th style="font-size: 12px; width:10%" class="text-center">Rak</th>
                        <th style="font-size: 12px; width:20%" class="text-center">Judul Buku</th>
                        <th style="font-size: 12px; width:20%" class="text-center">Penulis</th>
                        <th style="font-size: 12px; width:10%" class="text-center">Penerbit</th>
                        <th style="font-size: 12px; width:10%" class="text-center">ISBN</th>
                        <th style="font-size: 12px; width:10%" class="text-center">Tanggal Terbit</th>
                        <th style="font-size: 12px; width:6%" class="text-center">Jenis</th>
                        <th style="font-size: 12px; width:2%" class="text-center">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td class="" style="font-size: 12px">{{ $loop->iteration }}</td>
                            <td class="" style="font-size: 12px">{{ $item->kode_buku }}</td>
                            <td class="" style="font-size: 12px">{{ $item->rak->kode ?? '-' }}</td>
                            <td class="" style="font-size: 12px">{{ $item->judul }}</td>
                            <td class="" style="font-size: 12px">{{ $item->penulises->pluck('nama')->join(', ') }}
                            </td>
                            <td class="" style="font-size: 12px">{{ $item->penerbit->namaPenerbit }}</td>
                            <td class="" style="font-size: 12px">{{ $item->isbn }}</td>
                            <td class="text-nowrap " style="font-size: 12px">
                                {{ \Carbon\Carbon::parse($item->tgl_terbit)->isoFormat('DD/MM/YYYY') }}</td>
                            <td class="" style="font-size: 12px">{{ $item->jenis }}</td>
                            <td class="" style="font-size: 12px">{{ $item->jumlah }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @elseif ($laporan == 'Laporan Buku Telah Dibaca')
            <h4 class="text-center mb-3">Laporan Buku Telah Dibaca</h4>
            <table class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th style="font-size: 12px; width:2%" class="text-center">No</th>
                        <th style="font-size: 12px; width:10%" class="text-center">Tanggal</th>
                        <th style="font-size: 12px; width:35%" class="text-center">Buku</th>
                        <th style="font-size: 12px; width:5%" class="text-center">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td class="" style="font-size: 12px">{{ $loop->iteration }}</td>
                            <td class="text-nowrap " style="font-size: 12px">
                                {{ \Carbon\Carbon::parse($item->tanggal)->isoFormat('DD/MM/YYYY') }}</td>
                            <td class="" style="font-size: 12px">{{ $item->buku->judul }}</td>
                            <td class="" style="font-size: 12px">{{ $item->jumlah }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</body>

</html>
