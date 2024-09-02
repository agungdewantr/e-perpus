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

    {{-- OPEN ROW CARD RECAP, USERS, GRAFIK, BUKU TERLARIS --}}
        <div class="row">
            <div class="col-xl-8 col-md-12">
                {{-- OPEN CARD RECAP --}}
                    <div class="row">
                        <div class="col-xl-4 col-md-12">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    <h6><i class="mdi mdi-folder-multiple-outline"></i> Buku</h6>
                                    <h4><span class="counter-value" data-target="{{$totalBuku ?? '0'}}">0</span></h4>
                                    <div class="text-nowrap">
                                        @if($persenBuku ?? '0' > 0)
                                            <span class="badge badge-soft-success text-success">
                                                {{$persenBuku}}%
                                                <i class="fas fa-arrow-up"></i></span>
                                        @elseif($persenBuku ??'0' == 0)
                                            <span class="badge badge-soft-secondary text-secondary">
                                                {{$persenBuku ??'0'}}%
                                                <i class="fas fa-equals"></i></span>
                                        @elseif($persenBuku ??'0' < 0)
                                            <span class="badge badge-soft-success text-success">
                                                {{$persenBuku}}%
                                                <i class="fas fa-arrow-up"></i></span>
                                        @endif
                                        <span class="ms-1 text-muted font-size-13">Sejak bulan lalu</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-12">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    <h6><i class="fas fas fa-users"></i> Anggota</h6>
                                    <h4><span class="counter-value" data-target="{{$jumlahAnggota??'0'}}">0</span></h4>
                                    <div class="text-nowrap">
                                        @if($persenProfilAnggota ?? '0' > 0)
                                            <span class="badge badge-soft-success text-success">
                                                {{$persenProfilAnggota}}%
                                                <i class="fas fa-arrow-up"></i></span>
                                        @elseif($persenProfilAnggota ?? '0' == 0)
                                            <span class="badge badge-soft-secondary text-secondary">
                                                {{$persenProfilAnggota ?? '0'}}%
                                                <i class="fas fa-equals"></i></span>
                                        @elseif($persenProfilAnggota ??'0' < 0)
                                            <span class="badge badge-soft-success text-success">
                                                {{$persenProfilAnggota}}%
                                                <i class="fas fa-arrow-up"></i></span>
                                        @endif
                                        <span class="ms-1 text-muted font-size-13">Sejak bulan lalu</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-12">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    <h6><i class="fas fas fa-door-open"></i> Kunjungan</h6>
                                    <h4><span class="counter-value" data-target="{{$jumlahPerpustakaan ?? '0'}}">0</span></h4>
                                        <div class="text-nowrap">
                                            @if($persenKunjunganPerpustakaan ?? '0' > 0)
                                                <span class="badge badge-soft-success text-success">
                                                    {{$persenKunjunganPerpustakaan}}%
                                                    <i class="fas fa-arrow-up"></i></span>
                                            @elseif($persenKunjunganPerpustakaan ?? '0' == 0)
                                                <span class="badge badge-soft-secondary text-secondary">
                                                    {{$persenKunjunganPerpustakaan ?? '0'}}%
                                                    <i class="fas fa-equals"></i></span>
                                            @elseif($persenKunjunganPerpustakaan ??'0' < 0)
                                                <span class="badge badge-soft-success text-success">
                                                    {{$persenKunjunganPerpustakaan}}%
                                                    <i class="fas fa-arrow-up"></i></span>
                                            @endif
                                        <span class="ms-1 text-muted font-size-13">Sejak bulan lalu</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                {{-- CLOSE CARD RECAP --}}
                {{-- OPEN RECAP USERS --}}
                    <span class="">
                        Anggota yang sering melakukan <strong>peminjaman</strong>.
                    </span>
                    <div class="row mt-3">
                        @foreach ($pinjamanTerbanyaks as $pinjamanTerbanyak)
                            <div class="col-lg-3 col-md-6 col-xs-12 text-center">
                                <div>
                                    <img src="{{ $pinjamanTerbanyak->file_path ? Storage::url($pinjamanTerbanyak->file_path) : Storage::url('user/default.png') }}" class="rounded-circle avatar-xl" alt="">
                                </div>
                                <span>
                                    {{$pinjamanTerbanyak->nama_lengkap}}
                                </span>
                            </div>
                        @endforeach
                    </div>
                {{-- CLOSE RECAP USERS --}}
                {{-- OPEN GRAFIK ANGGOTA PERPUSTAKAAN --}}
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="shadow-lg card bg-body-tertiary">
                                <div class="card-body">
                                    <div id="barChart" class="e-charts"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                {{-- CLOSE GRAFIK ANGGOTA PERPUSTAKAAN --}}
                {{-- OPEN GRAFIK KUNJUNGAN PERPUSTAKAAN --}}
                    <div class="row">
                        <div class="col-12">
                            <div class="shadow-lg card bg-body-tertiary">
                                <div class="card-body">
                                    <div id="lineChart" class="e-charts"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                {{-- CLOSE GRAFIK KUNJUNGAN PERPUSTAKAAN --}}
            </div><!-- end col -->
            <div class="col-xl-4 col-md-12">
                {{-- OPEN GRAFIK KOLEKSI BUKU --}}
                    <div class="row">
                        <div class="col-12">
                            <div class="shadow-lg card bg-body-tertiary">
                                <div class="card-body">
                                    <div id="pieChart" class="e-charts" style="height: 400px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                {{-- CLOSE GRAFIK KOLEKSI BUKU --}}
                <div class="row ">
                    <div class="col-12">
                        {{-- OPEN BUKU TERLARIS --}}
                            <div class="shadow-lg card bg-body-tertiary">
                                <div class="card-body px-0">
                                        <h5 class="mb-4 px-3">Buku Terlaris</h5>
                                        <div class="table-responsive" data-simplebar style="max-height: 559px;">
                                            <table class="table align-middle table-borderless">
                                                <tbody>
                                                    @foreach ($bukuTerlaries as $bukuTerlaris )
                                                        <tr>
                                                            <td style="width: 150px;">
                                                                @if ($bukuTerlaris->file_path)
                                                                    <img src="{{Storage::url($bukuTerlaris->file_path) }}" class="img-fluid" alt="Gambar Buku">
                                                                @else
                                                                    <img src="{{asset('web_assets/images/books/grid/not-found-book.png') }}" class="img-fluid" alt="Gambar Buku">
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div>
                                                                    <strong>{{$bukuTerlaris->judul}}</strong><br>
                                                                    <span>{{$bukuTerlaris->nama}}</span><br>
                                                                    <span>{{$bukuTerlaris->jenis}}</span><br>
                                                                    <span>Jumlah Pinjaman : {{$bukuTerlaris->jumlahterpinjam}}x</span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                </div>
                            </div>
                        {{-- CLOSE BUKU TERLARIS --}}
                    </div>
                </div>
            </div><!-- end col -->
        </div>
    {{-- CLOSE ROW CARD RECAP, USERS, GRAFIK, BUKU TERLARIS --}}
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

