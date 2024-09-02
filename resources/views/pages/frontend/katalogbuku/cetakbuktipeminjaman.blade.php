<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: -22px;
                padding: 0;
                justify-content: center;
            }

            .card {
                justify-content: center;
                width: 450px;
                /* margin: 50px auto; */
                background-color: #fff;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            .card-body {
                justify-content: center;
                padding: 20px;
            }

            h6 {
                text-align: center;
                font-size: 18px;
                margin-bottom: 15px;
            }

            .row {
                margin-right: -15px;
                margin-left: -15px;
                padding: 5px;
            }

            .col-12 {
                width: 100%;
                padding-right: 15px;
                padding-left: 15px;
                box-sizing: border-box;
            }

            .flex-container {
                display: flex !important;
    justify-content: space-between !important;
            }

            span {
                margin: 0;
            }

            .fw-bold {
                font-weight: bold;
            }

            hr {
                margin: 15px 0;
                border: 0;
                border-top: 1px solid #ccc;
            }

            small {
                display: block;
                font-size: 12px;
                color: #6c757d;
                margin-top: 20px;
            }

            .text-left{
                text-align: left;
            }

            .text-right{
                text-align: right;
            }
            .table{
                margin-top: 10px;
                border : 1px solid #000;
                border-collapse: collapse;
                width: 100%;
                font-size: 10pt;
            }

            .table th, .table td {
                border: 1px solid #000; /* Border 1px solid untuk sel (cell) */
                padding: 8px; /* Memberikan ruang di dalam sel (opsional) */
            }

            .table tr {
                border-bottom: 1px solid #000; /* Border 1px solid untuk baris (row) */
            }

            .bukti-peminjaman {
                border-top: 1px dashed #000; /* Garis putus-putus di bagian atas */
                border-bottom: 1px dashed #000; /* Garis putus-putus di bagian bawah */
                padding: 10px; /* Memberikan ruang di dalam elemen (opsional) */
            }
        </style>
</head>
<body>

<div class="card">
    <div class="card-body">
        <table width="100%">
            <tr>
                <td width="15%"><img src="{{ public_path('logo/logo_kabupaten_tolitoli.png') }}" width="100%" alt=""></td>
                <td  width="85%" ><h3 style="padding-left:10px">DINAS PERPUSTAKAAN DAN ARSIP
                    KABUPATEN TOLITOLI
                    </h3></td>
            </tr>
        </table>
        <p style="text-align:center">Jl. Magamu Nomor 78 Kabupaten Toli-Toli, <br>
            Sulawesi Tengah 94514</p>
        <h6 class="bukti-peminjaman">BUKTI PEMINJAMAN BUKU</h6>
        <div class="row">
            <h5 style="margin-bottom:-20px;">Nomor Anggota <span style="float:right;">{{$peminjaman[0]->profilAnggota->nomor_anggota}}</span></h5>
            <h5 style="margin-bottom:-20px;">Nama <span style="float:right">{{$peminjaman[0]->profilAnggota->nama_lengkap}}</span></h5>
            <h5 style="margin-bottom:-20px;">Total Buku <span style="float:right">{{$peminjaman->count()}}</span></h5>
            <h5 style="margin-bottom: 0;">Rincian Buku</h5>
            <table class="table">
                <thead>
                    <tr>
                        <th style="text-align: center">No</th>
                        <th>Judul Buku</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($peminjaman as $item)
                    <tr>
                        <td style="text-align: center">{{$loop->iteration}}</td>
                        <td>{{$item->itemBuku->buku->judul}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
                <hr border="1">
                @if($peminjaman[0]->tanggal_batas_pinjaman)
                <h5 style="margin-bottom:-20px;">Tanggal Peminjaman <span style="float:right;">{{\Carbon\Carbon::parse(@$peminjaman[0]->tanggal_pengambilan_pinjaman)->format('d F Y')}}</span></h5>
                <h5 style="margin-bottom:-20px;">Tanggal Batas Kembali <span style="float:right;">{{\Carbon\Carbon::parse(@$peminjaman[0]->tanggal_batas_pinjaman)->format('d F Y')}}</span></h5>
                @else
                <h5 style="margin-bottom:-20px;">Tanggal Maksimal Pengambilan <span style="float:right;">{{\Carbon\Carbon::parse(@$peminjaman[0]->tanggal_pengajuan_pinjaman)->addDay(2)->format('d F Y')}}</span></h5>
                @endif
                <br>
                <small>*Batas waktu peminjam hanya 1 minggu</small>
        </div>
    </div>
</div>

</body>
</html>
