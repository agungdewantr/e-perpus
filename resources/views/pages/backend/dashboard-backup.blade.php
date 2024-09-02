@extends('layouts.backend')
@section('title','Dashboard')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Dashboard</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    {{-- OPEN ROW STATISTIK, GRAFIK, BUKU TERLARIS --}}
        <div class="row">
            <div class="col-xl-8 col-md-12">
                {{-- OPEN STATISTIK --}}
                    <div class="shadow-lg card bg-body-tertiary">
                        <div class="card-body">
                            <h5 class="mb-4">
                                Statistik
                            </h5>
                            {{-- CARDS --}}
                            <div class="row">
                                <div class="col-xl-4 col-md-12">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6>Buku</h6>
                                            <h4><span class="counter-value" data-target="1080">0</span></h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-12">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6>Total Anggota</h6>
                                            <h4><span class="counter-value" data-target="101">0</span></h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-md-12">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6>Kunjungan/Bulan</h6>
                                            <h4><span class="counter-value" data-target="1080">0</span></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- ANGGOTA SERING MEMINJAM --}}
                            <span class="">
                                Anggota yang sering melakukan <strong>peminjaman</strong>.
                            </span>
                            <div class="row mt-3">
                                <div class="col-lg-3 col-md-6 col-xs-12 text-center">
                                    <div>
                                        <img src="admin_assets/images/users/avatar-8.jpg" class="rounded-circle avatar-xl" alt="">
                                    </div>
                                    <span>
                                        Febri
                                    </span>
                                </div>
                                <div class="col-lg-3 col-md-6 col-xs-12 text-center">
                                    <div>
                                        <img src="admin_assets/images/users/avatar-6.jpg" class="rounded-circle avatar-xl" alt="">
                                    </div>
                                    <span>
                                        Febri
                                    </span>
                                </div>
                                <div class="col-lg-3 col-md-6 col-xs-12 text-center">
                                    <div>
                                        <img src="admin_assets/images/users/avatar-4.jpg" class="rounded-circle avatar-xl" alt="">
                                    </div>
                                    <span>
                                        Febri
                                    </span>
                                </div>
                                <div class="col-lg-3 col-md-6 col-xs-12 text-center">
                                    <div>
                                        <img src="admin_assets/images/users/avatar-10.jpg" class="rounded-circle avatar-xl" alt="">
                                    </div>
                                    <span>
                                        Febri
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                {{-- CLOSE STATISTIK --}}
                {{-- OPEN GRAFIK --}}
                    <div class="shadow-lg card bg-body-tertiary">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h5>
                                    Grafik
                                </h5>
                                <div class="btn-group">
                                    <button class="btn btn-soft-link waves-effect btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                       <i class="fas fa-filter"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item filterGrafik" data-target="kunjunganPerpustakaan">Kunjungan Perpusatakaan</a>
                                        <a class="dropdown-item filterGrafik" data-target="anggotaPerpustakaan">Anggota Perpustakaan</a>
                                        <a class="dropdown-item filterGrafik" data-target="koleksiBuku">Koleksi Buku</a>
                                    </div>
                                </div>
                            </div>
                            <div id="grafikData">
                                <div id="lineChart" class="e-charts"></div>
                                <div id="barChart" class="e-charts"></div>
                                <div id="pieChart" class="e-charts"></div>
                            </div>
                        </div>
                    </div>
                {{-- CLOSE GRAFIK --}}
            </div><!-- end col -->
            <div class="col-xl-4 col-md-12">
                {{-- OPEN BUKU TERLARIS --}}
                    <div class="shadow-lg card bg-body-tertiary">
                        <div class="card-body px-0">
                                {{-- <h5 class="mb-4">Buku Terlaris</h5>
                                <div class="mb-2">
                                    <div class="row">
                                        <div class="col-4">
                                            <img src="admin_assets/images/buku-1.png" class="img-fluid" alt="Gambar Buku">
                                        </div>
                                        <div class="col-8">
                                            <strong>Ada Apa Dengan Cinta</strong><br>
                                            <span>DRAMA</span><br>
                                            <span>Buku Fisik</span><br>
                                            <span>Jumlah Pinjaman : 100x</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="row">
                                        <div class="col-4">
                                            <img src="admin_assets/images/buku-2.png" class="img-fluid" alt="Gambar Buku">
                                        </div>
                                        <div class="col-8">
                                            <strong>Ada Apa Dengan Cinta</strong><br>
                                            <span>DRAMA</span><br>
                                            <span>Buku Fisik</span><br>
                                            <span>Jumlah Pinjaman : 50x</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="row">
                                        <div class="col-4">
                                            <img src="admin_assets/images/buku-3.png" class="img-fluid" alt="Gambar Buku">
                                        </div>
                                        <div class="col-8">
                                            <div class="text-truncate" style="max-height: 3.6em;">
                                                <strong>Ada Apa Dengan Cinta Kita Selama Ini : Edisi Spesial Bulan Ramadhan</strong><br>
                                            </div>
                                            <span>DRAMA</span><br>
                                            <span>Buku Fisik</span><br>
                                            <span>Jumlah Pinjaman : 90x</span>
                                        </div>
                                    </div>
                                </div> --}}
                                <h5 class="mb-4 px-3">Buku Terlaris</h5>

                                <div class="table-responsive" data-simplebar style="max-height: 708px;">
                                    <table class="table align-middle table-borderless">
                                        <tbody>
                                            <tr>
                                                <td style="width: 150px;">
                                                    <img src="admin_assets/images/buku-1.png" class="img-fluid" alt="Gambar Buku">
                                                </td>
                                                <td>
                                                    <div>
                                                        <strong>Ada Apa Dengan Cinta</strong><br>
                                                        <span>DRAMA</span><br>
                                                        <span>Buku Fisik</span><br>
                                                        <span>Jumlah Pinjaman : 100x</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 150px;">
                                                    <img src="admin_assets/images/buku-2.png" class="img-fluid" alt="Gambar Buku">
                                                </td>
                                                <td>
                                                    <div>
                                                        <strong>Ada Apa Dengan Cinta</strong><br>
                                                        <span>DRAMA</span><br>
                                                        <span>Buku Fisik</span><br>
                                                        <span>Jumlah Pinjaman : 50x</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 150px;">
                                                    <img src="admin_assets/images/buku-3.png" class="img-fluid" alt="Gambar Buku">
                                                </td>
                                                <td>
                                                    <div>
                                                        <strong>Ada Apa Dengan Cinta Kita Selama Ini : Edisi Spesial Bulan Ramadhan</strong><br>
                                                        <span>DRAMA</span><br>
                                                        <span>Buku Fisik</span><br>
                                                        <span>Jumlah Pinjaman : 90x</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                        </div>
                    </div>
                {{-- CLOSE BUKU TERLARIS --}}
            </div><!-- end col -->
        </div>
    {{-- CLOSE ROW STATISTIK, ROW, BUKU TERLARIS --}}
@endsection
@section('vendorscripts')
    <!-- echarts js -->
    <script src="{{ asset('/') }}admin_assets/libs/echarts/echarts.min.js"></script>
    <!-- echarts init -->
    <script src="{{ asset('/') }}admin_assets/js/pages/echarts.init.js"></script>
    @include("pages.backend.scriptdashboard")
@endsection
@section('scriptbawah')
<script>
    $(document).ready(function () {
    });
</script>
@endsection

