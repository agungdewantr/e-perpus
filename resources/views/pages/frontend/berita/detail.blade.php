@extends('layouts.frontend')
@section('title','Berita')
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

.subtitle{
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}


</style>
@endsection
@section('frontend.content')
<div class="page-content">
    <!-- inner page banner -->
    <div class="dz-bnr-inr overlay-primary-light dz-bnr-inr-sm" style="background-image: url('{{ asset('web_assets/images/about/grid_image.png') }}');">
        <div class="container">
            <div class="dz-bnr-inr-entry">
                <h1>Detail Berita</h1>
                <nav aria-label="breadcrumb" class="breadcrumb-row">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('beranda')}}"> Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{route('berita-perpustakaan')}}"> Berita</a></li>
                        <li class="breadcrumb-item">Detail Berita</li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <!-- inner page banner End-->
    <!-- Blog Large -->
    <section class="content-inner-1 bg-img-fix">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-8">
                    <!-- blog start -->
                    <div class="dz-blog blog-single style-1">
                        <div class="dz-media rounded-md">
                            <img src="{{ asset('storage/'.$berita->gambar->file_path) }}" alt="">
                        </div>
                        <div class="dz-info">
                            <div class="dz-meta  border-0 py-0 mb-2">
                                <ul class="border-0 pt-0">
                                    <li class="post-date"><i class="far fa-calendar fa-fw m-r10"></i>{{ \Carbon\Carbon::parse($berita->created_at)->format('d F Y')}}</li>
                                </ul>
                            </div>
                            <h4 class="dz-title">{{$berita->judul}}</h4>
                            <div class="dz-post-text">
                                <p>{{$berita->deskripsi}}</p>
                            </div>
                        </div>
                    </div>
                    <!-- blog END -->
                </div>
                <div class="col-xl-4 col-lg-4">
                    <aside class="side-bar sticky-top">
                        <div class="widget recent-posts-entry">
							<h4 class="widget-title">Buku Serupa</h4>
							<div class="row">
                                @foreach ($buku_rekomendasi as $item)
								<div class="col-xl-12 col-lg-12">
									<div class="dz-shop-card style-5">
										<div class="dz-media">
											<img src="{{ !isset($item->cover->file_path) ? asset('/') . 'web_assets/images/books/grid/not-found-book.png' : asset('storage/' . @$item->cover->file_path) }}" alt="book">
										</div>
										<div class="dz-content">
											<a href="{{route('katalog-buku.overview',$item->slug)}}"><h5 class="subtitle">{{ $item->judul }}</h5></a>
											<ul class="dz-tags">
                                                <li>{{ $item->subKategori->kategori->nama }}</li>
											</ul>
											<a href="#"  onclick="pinjamBuku({{$item->id}})" class="btn btn-outline-primary btn-sm btnhover2"> Pinjam</a>
										</div>
									</div>
								</div>
                                @endforeach
							</div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </section>
    <!-- Feature Box -->
</div>
@endsection
@section('frontend.scriptbawah')
<script>
    $(document).ready(function() {
        //OPEN POP UP MODAL CEK KELENGKAPAN DATA
            // @if (auth()->check())
            //     var auth = @json(auth()->user());
            //     var profilAnggota = @json(auth()->user()->profilAnggota);
            //     if (!profilAnggota) {
            //         $('#modalDataBelumLengkap_rellarphp').modal('show');
            //     }
            // @endif
        //CLOSE POP UP MODAL CEK KELENGKAPAN DATA

        // ganti tampilan grid
        $("#display_grid").click(function() {
            $("#books-container").removeClass("books-list").addClass("books-grid");
            $(".dz-shop-card").removeClass("dz-shop-card style-2");
            $(".books-card").addClass("wow fadeInUp");
            $(".books-card").css("height", "");
            $(".books-card").css("padding-top", "");
            $(".books-card img").css("width", "");
            $(".books-card h5").css("font-size", "");
            $(".dz-content").css("margin-left", "");
        });

      $("#display_list").click(function() {
        $("#books-container").removeClass("books-grid").addClass("books-list");
        $(".books-card").removeClass("wow fadeInUp").addClass("dz-shop-card style-2 fadeInUp").css("width", "");
        $(".dz-shop-card").addClass("wow fadeInUp").css("height", "145px");
        $(".dz-shop-card").css("padding-top", "0");
        $(".dz-shop-card").css("margin-bottom", "5px");
        $(".dz-shop-card img").css("width", "100px");
        $(".dz-shop-card h5").css("font-size", "14pt");
        // $(".dz-content").css("margin-left", "-15%");

      });


    });
</script>
@endsection

