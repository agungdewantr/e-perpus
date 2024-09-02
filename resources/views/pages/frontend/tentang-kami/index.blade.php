@extends('layouts.frontend')
@section('title','Tentang Kami')
@section('frontend.scriptatas')
<style>

.header-overview h5 {
    font-size: 15pt;
    padding-left: 1.2em;
    position: relative;
    bottom: 0;
    color: #000;
    /* margin-top: 30px; */
}

.header-overview h5:before{
    background: #8a8a8a;
    border-radius: 8px;
    bottom: 0;
    content: "";
    left: 0;
    position: absolute;
    top: -20px;
    width: 8px;
    height: 60px;
}


</style>
@endsection
@section('frontend.content')
<div class="page-content">
    <!-- inner page banner -->
    <div class="dz-bnr-inr overlay-primary-light dz-bnr-inr-sm" style="background-image: url('{{ asset('web_assets/images/about/grid_image.png') }}');">
        <div class="container">
            <div class="dz-bnr-inr-entry">
                <h1>Tentang Kami</h1>
                <nav aria-label="breadcrumb" class="breadcrumb-row">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html"> Beranda</a></li>
                        <li class="breadcrumb-item">Tentang Kami</li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <div class="content-inner-2 pt-0">
        <div class="map-iframe">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.157984835835!2d120.81008147567982!3d1.0424359624853667!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x32712b31198156b5%3A0x17cd28bdafa5469b!2sDinas%20Perpustakaan%20Dan%20Arsip%20Kabupaten%20Tolitoli!5e0!3m2!1sid!2sus!4v1702022075428!5m2!1sid!2sus" width="100%" height="450" style="border:0; min-height: 100%; margin-bottom: -8px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

        </div>
    </div>

    <section class="contact-wraper1" style="background-image: url({{ asset('/') }}web_assets/images/tolitoli.jpg);overflow: hidden;">
        {{-- <div class="container"> --}}
            <div class="row align-items-center" style="overflow: hidden">
                <div class="col-lg-6 ps-4">
                    <div class="contact-info">
                        <div class="section-head text-white style-1">
                            <h3 class="title text-white">Dinas Perpustakaan dan Arsip Kabupaten Tolitoli</h3>
                        </div>
                        <ul class="no-margin">
                            <li class="icon-bx-wraper text-white left m-b30">
                                <div class="icon-md">
                                    <span class="icon-cell text-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                    </span>
                                </div>
                                <div class="icon-content">
                                    <h5 class=" dz-tilte text-white">Alamat</h5>
                                    <p>Jl. Magamu No.78 Kabupaten Toli-Toli, Sulawesi Tengah 94514</p>
                                </div>
                            </li>
                            <li class="icon-bx-wraper text-white left m-b30">
                                <div class="icon-md">
                                    <span class="icon-cell text-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                    </span>
                                </div>
                                <div class="icon-content">
                                    <h5 class="dz-tilte text-white">Email</h5>
                                    <p>Email : dispusaka@tolitolikab.go.id</p>
                                </div>
                            </li>
                            <li class="icon-bx-wraper text-white left m-b30">
                                <div class="icon-md">
                                    <span class="icon-cell text-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clock"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>                                        </span>
                                </div>
                                <div class="icon-content">
                                    <h5 class="dz-tilte text-white">Waktu Pelayanan</h5>
                                    <p>Senin - Sabtu 08.00 - 16.00</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="dz-media dz-img-effect zoom bg-success">
                        <img src="{{asset('/')}}web_assets/images/about/grid_image.png" style="object-fit: cover" alt="banner-media">
                    </div>
                </div>
            </div>
        {{-- </div> --}}
    </section>

    <!-- Feature Box -->
    <section class="content-inner">
        <div class="container">
            <h1 class="text-center mb-5">PROSEDUR</h1>
            <div class="row sp15 d-flex justify-content-center">
                @foreach ($prosedur as $item)
                <div class="col-lg-3 col-md-6 col-sm-6 col-6">
                    <div class="icon-bx-wraper style-2 m-b30 text-center">
                        <div class="icon-bx-lg">
                            <a href="{{ asset('storage/'.$item->dokumen->file_path) }}" target="_blank"> <img src="{{asset('/')}}web_assets/icons/icon-pdf.png" alt=""> </a>
                        </div>
                        <div class="icon-content">
                            <h3 class="dz-title m-b0">SOP</h3>
                            <p class="font-20">{{$item->judul}}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- Feature Box End -->
@endsection

