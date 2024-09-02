@extends('layouts.frontend')
@section('title','Beranda')
@section('frontend.scriptatas')
<style>
    .searchBox {
    position: absolute;
    top: 50%;
    left: 50%;
    transform:  translate(-50%,50%);
    background: white;
    height: 40px;
    border-radius: 40px;
    padding: 10px;

}


.searchButton {
    width: 40px;
    height: 40px;
    border-radius: 50%;
}

.searchInput {
    border:none;
    background: none;
    outline:none;
    float:left;
    transition: 0.4s;
    line-height: 40px;
    width: 240px;
    padding: 0 6px;
}

.title{
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.title2{
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    font-size: 11pt;
    text-align: left;
    line-height: 1.2em;
    margin-top: 10px;
    height:35px;
}

.style-1 .dz-media img {
      /* height: 230px; */
      /* width: 100%; */
      object-fit: cover;
    }

    .carousel-container {
    position: relative;
}

.carousel-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.3); /* Warna oranye transparan */
    z-index: 2; /* Menempatkan latar belakang di atas carousel */
}

.carousel-inner {
    z-index: 1; /* Menempatkan carousel di atas latar belakang */
}

/* Ganti warna panah kontrol carousel */
.carousel-control-prev-icon,
.carousel-control-next-icon {
    filter: invert(75%) sepia(80%) saturate(1000%) hue-rotate(160deg); /* Warna kuning untuk panah kontrol */
}

/* Ganti warna teks panah kontrol carousel */
.carousel-control-prev,
.carousel-control-next {
    display: none; /* Warna oranye untuk teks panah kontrol */
}

.dz-title{
    text-align: left;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.dz-info{
    height: 250px;
}
.dz-info p{
    text-align: left;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.content-info .title{
    font-size: 20pt;
    color:#000;
}

.swiper-content .content-info .h2banner{
    font-size: 28pt; font-weight:300;
    color: #fff;
}
.swiper-content>.content-info>.h1banner{
font-size: 32pt;
color: #fff;
}

.deskripsiBeranda{
    color:#1a1668;
    font-weight: bold;
    line-height: 1.75;
}

mark{
    background-color: rgba(215, 248, 250, 0.6);
    color:#1a1668;
}


</style>
@endsection
@section('frontend.content')
    <div class="page-content bg-white">
        <!--Swiper Banner Start -->
		<div class="main-slider style-1">
			<div class="main-swiper">
				<div class="swiper-wrapper">
                    <div class="swiper-slide bg-blue" style="background-image: url('{{ asset('web_assets/images/banner/homepage_img.jpg') }}'); min-height:100vh">

						<div class="container">
							<div class="banner-content">
								<div class="row">
									<div class="col-md-6">
										<div class="swiper-content">
											<div class="content-info">
												<h1 class="title mb-0 text-secondary" data-swiper-parallax="-20">Perpustakaan Digital</h1>
                                                <h2 class="title mb-0" style="color:#1a1668;" data-swiper-parallax="-10">"Bamba Pongngoto"</h2>
												<p class=" mb-0 deskripsiBeranda" data-swiper-parallax="-40"><mark>Membaca buku melalui perpustakaan digital cara mudah dan menyenangkan untuk menambah pengetahuan</mark></p>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="banner-media" data-swiper-parallax="-100">
											<img src="{{asset('/')}}web_assets/images/banner/banner_vektor.png" alt="banner-media">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="container swiper-pagination-wrapper">
					<div class="swiper-pagination-five"></div>
				</div>
			</div>
			<div class="swiper main-swiper-thumb">
				<div class="swiper-wrapper">
					<div class="swiper-slide">
						{{-- <div class="books-card">
							<div class="dz-media">
								<img src="images/books/book16.png" alt="book">
							</div>
							<div class="dz-content">
								<h5 class="title mb-0">Think and Grow Rich</h5>
								<div class="dz-meta">
									<ul>
										<li>by Napoleon Hill</li>
									</ul>
								</div>
								<div class="book-footer">
									<div class="price">
										<span class="price-num">$9.5</span>
									</div>
									<div class="rate">
										<i class="flaticon-star text-yellow"></i>
										<i class="flaticon-star text-yellow"></i>
										<i class="flaticon-star text-yellow"></i>
										<i class="flaticon-star text-yellow"></i>
										<i class="flaticon-star text-yellow"></i>
									</div>
								</div>
							</div>
						</div> --}}
					</div>
					<div class="swiper-slide">
						{{-- <div class="books-card">
							<div class="dz-media">
								<img src="images/books/grid/book9.jpg" alt="book">
							</div>
							<div class="dz-content">
								<h5 class="title mb-0">Pushing Clouds</h5>
								<div class="dz-meta">
									<ul>
										<li>by Jamet Sigh</li>
									</ul>
								</div>
								<div class="book-footer">
									<div class="price">
										<span class="price-num">$5.7</span>
									</div>
									<div class="rate">
										<i class="flaticon-star text-yellow"></i>
										<i class="flaticon-star text-yellow"></i>
										<i class="flaticon-star text-yellow"></i>
										<i class="flaticon-star text-yellow"></i>
										<i class="flaticon-star text-muted"></i>
									</div>
								</div>
							</div>
						</div> --}}
					</div>
					<div class="swiper-slide">
						{{-- <div class="books-card">
							<div class="dz-media">
								<img src="images/books/book16.png" alt="book">
							</div>
							<div class="dz-content">
								<h5 class="title mb-0">Think and Grow Rich</h5>
								<div class="dz-meta">
									<ul>
										<li>by Napoleon Hill</li>
									</ul>
								</div>
								<div class="book-footer">
									<div class="price">
										<span class="price-num">$9.5</span>
									</div>
									<div class="rate">
										<i class="flaticon-star text-yellow"></i>
										<i class="flaticon-star text-yellow"></i>
										<i class="flaticon-star text-yellow"></i>
										<i class="flaticon-star text-yellow"></i>
										<i class="flaticon-star text-yellow"></i>
									</div>
								</div>
							</div>
						</div> --}}
					</div>
					<div class="swiper-slide">
						{{-- <div class="books-card">
							<div class="dz-media">
								<img src="images/books/grid/book9.jpg" alt="book">
							</div>
							<div class="dz-content">
								<h5 class="title mb-0">Pushing Clouds</h5>
								<div class="dz-meta">
									<ul>
										<li>by Jamet Sigh</li>
									</ul>
								</div>
								<div class="book-footer">
									<div class="price">
										<span class="price-num">$5.7</span>
									</div>
									<div class="rate">
										<i class="flaticon-star text-yellow"></i>
										<i class="flaticon-star text-yellow"></i>
										<i class="flaticon-star text-yellow"></i>
										<i class="flaticon-star text-yellow"></i>
										<i class="flaticon-star text-muted"></i>
									</div>
								</div>
							</div>
						</div> --}}
					</div>
				</div>
			</div>
		</div>
        <!--Swiper Banner End-->
        <!-- Cari Buka Favoritmu Start -->
            <section class="content-inner-1 bg-grey reccomend">
                <div class="container">
                    <div class="section-head text-center">
                        <h2 class="title text-black">Cari Buku Favoritmu</h2>
                        <form action="{{route('katalog-buku')}}" method="get">
                            @csrf
                            <div class="input-group search-input mt-4">

                                <input type="text" class="form-control bg-white" aria-label="Text input with dropdown button" id="judul" name="judul" placeholder="Cari Judul Buku">
                                <button class="btn" type="submit"><i class="flaticon-loupe"></i></button>

                            </div>
                        </form>
                    </div>
                </div>
            </section>
        <!-- Cari Buka Favoritmu End -->
        <!--Recommend Section Start-->
            <section class="content-inner-1 bg-white reccomend">

                <div class="container">
                    <div class="section-head text-center">
                        <h2 class="title text-black">Rekomendasi Buat Kamu</h2>
                        <p>
                            Jelajahi lebih dari 500 buku bacaan, e-book, buku komik, dan buku audio, video pembelajaran.
                        </p>
                    </div>

                    <div class="swiper-container books-wrapper-3 swiper-four">
                        <div class="swiper-wrapper">
                            @foreach ($kategori as $item)
                            <div class="swiper-slide">
                                <div class="">
                                    <div class="shadow card bg-body-tertiary wow fadeInUp" style="height: 230px; margin-top:30px" data-wow-delay="0.1s">
                                        <div class="card-body text-center pb-0 pt-2">
                                            <img class="mt-3" src="{{ asset('storage/kategori/'.$item->kode.'.png') }}" alt="kategori-img" style="height: 80px;">
                                            <p class="">
                                                <form action="{{route('katalog-buku')}}" method="get">
                                                    <input type="hidden" name="kategori[]" id="kategori" value="{{$item->id}}">
                                                    <button type="submit" style="border: none;background: none;">
                                                <strong>
                                                    {{$item->nama}}
                                                </strong>
                                            </button>
                                            </form>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                        </div>
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        <div class="pagination-align style-1 wow fadeInUp" data-wow-delay="0.7s">
                            <div class="swiper-button-prev" style="background-color: #C6E5F2;"><i class="fa-solid fa-angle-left"></i></div>
                            <div class="swiper-button-next" style="background-color: #8DCBE6;"><i class="fa-solid fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
            </section>
        <!--Recommend Section End-->
        <!-- Book Sale -->
            <section class="content-inner-1 bg-grey">
                <div class="container">
                    <div class="section-head book-align">
                        <h2 class="title mb-0">Buku Terbaru Bulan Ini</h2>
                        <div class="pagination-align style-1">
                            <form action="{{route('katalog-buku')}}" method="get">
                                <input type="hidden" name="jenis[]" id="jenis" value="bulan_ini">
                                <button type="submit" class="btn btn-outline-success float-right" style="color: #205E61; border-color: #205E61; border-radius:30px">Lihat Semua</button>
                            </form>
                        </div>
                    </div>
                    <div class="swiper-container books-wrapper-3 swiper-four">
                        <div class="swiper-wrapper">
                            @foreach ($buku_bulan_ini as $book)
                            <div class="swiper-slide">
                                <div class="books-card style-3 wow fadeInUp" data-wow-delay="0.6s">
                                    <div class="dz-media">
                                        <img src="{{ !isset($book->cover->file_path) ? asset('/') . 'web_assets/images/books/grid/not-found-book.png' : asset('storage/' . @$book->cover->file_path) }}" alt="book">
                                    </div>
                                    <div class="dz-content">
                                        <h5 class="title"><a href="{{route('katalog-buku.overview',$book->slug)}}">{{ $book->judul}}</a></h5>
                                        <ul class="dz-tags">
                                            <li>
                                                <form action="{{route('katalog-buku')}}" method="get">
                                                    <input type="hidden" name="kategori[]" id="kategori" value="{{$book->subKategori->kategori->id}}">
                                                    <button type="submit" class="badge" style="border: none;">{{ preg_match('/^[A-Za-z]+/', $book->subKategori->kategori->nama, $matches) ? $matches[0] : '' }}</button>
                                                </form>
                                            </li>
                                        </ul>
                                        <div class="book-footer">
                                            <div class="rate">
                                               <span class="text-yellow"><i class="flaticon-heart"></i> {{$book->hitungJumlahFavorit()}} <i class="far fa-eye mt-1 ms-3"></i> {{$book->jumlah_kunjungan}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        <div class="pagination-align style-1 wow fadeInUp" data-wow-delay="0.7s">
                            <div class="testimonial-button-prev swiper-button-prev" style="background-color: #C6E5F2;"><i class="fa-solid fa-angle-left"></i></div>
                            <div class="testimonial-button-next swiper-button-next" style="background-color: #8DCBE6;"><i class="fa-solid fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content-inner-1 bg-white">
                <div class="container">
                    <div class="section-head book-align">
                        <h2 class="title mb-0">Dengarkan Cerita Terbaik Setiap Hari</h2>
                        <div class="pagination-align style-1">
                            <form action="{{route('katalog-buku')}}" method="get">
                                <input type="hidden" name="jenis[]" id="jenis" value="Buku Audio">
                                <button type="submit" class="btn btn-outline-success float-right" style="color: #205E61; border-color: #205E61; border-radius:30px">Lihat Semua</button>
                            </form>
                        </div>
                    </div>
                    <div class="swiper-container books-wrapper-3 swiper-four">
                        <div class="swiper-wrapper">
                            @foreach ($buku_audio as $book)
                            <div class="swiper-slide">
                                <div class="books-card style-3 wow fadeInUp" data-wow-delay="0.6s">
                                    <div class="dz-media">
                                        <img src="{{ !isset($book->cover->file_path) ? asset('/') . 'web_assets/images/books/grid/not-found-book.png' : asset('storage/' . @$book->cover->file_path) }}" alt="book">
                                    </div>
                                    <div class="dz-content">
                                        <h5 class="title"><a href="{{route('katalog-buku.overview',$book->slug)}}">{{ $book->judul}}</a></h5>
                                        <ul class="dz-tags">
                                            <li>
                                                <form action="{{route('katalog-buku')}}" method="get">
                                                    <input type="hidden" name="kategori[]" id="kategori" value="{{$book->subKategori->kategori->id}}">
                                                    <button type="submit" class="badge" style="border: none;">{{ preg_match('/^[A-Za-z]+/', $book->subKategori->kategori->nama, $matches) ? $matches[0] : '' }}</button>
                                                </form>
                                            </li>
                                        </ul>
                                        <div class="book-footer">
                                            <div class="rate">
                                               <span class="text-yellow"><i class="flaticon-heart"></i> {{$book->hitungJumlahFavorit()}} <i class="far fa-eye mt-1 ms-3"></i> {{$book->jumlah_kunjungan}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        <div class="pagination-align style-1 wow fadeInUp" data-wow-delay="0.7s">
                            <div class="swiper-button-prev" style="background-color: #C6E5F2;"><i class="fa-solid fa-angle-left"></i></div>
                            <div class="swiper-button-next" style="background-color: #8DCBE6;"><i class="fa-solid fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content-inner-1 bg-grey">
                <div class="container">
                    <div class="section-head book-align">
                        <h2 class="title mb-0">Kumpulan E-Book</h2>
                        <div class="pagination-align style-1">
                            <form action="{{route('katalog-buku')}}" method="get">
                            <input type="hidden" name="jenis[]" id="jenis" value="Buku Digital">
                            <button type="submit" class="btn btn-outline-success float-right" style="color: #205E61; border-color: #205E61; border-radius:30px">Lihat Semua</button>
                        </form>
                        </div>
                    </div>
                    <div class="swiper-container books-wrapper-3 swiper-four">
                        <div class="swiper-wrapper">
                            @foreach ($buku_ebook as $book)
                            <div class="swiper-slide">
                                <div class="books-card style-3 wow fadeInUp" data-wow-delay="0.6s">
                                    <div class="dz-media">
                                        <img src="{{ !isset($book->cover->file_path) ? asset('/') . 'web_assets/images/books/grid/not-found-book.png' : asset('storage/' . @$book->cover->file_path) }}" alt="book">
                                    </div>
                                    <div class="dz-content">
                                        <h5 class="title"><a href="{{route('katalog-buku.overview',$book->slug)}}">{{ $book->judul}}</a></h5>
                                        <ul class="dz-tags">
                                            <li>
                                                <form action="{{route('katalog-buku')}}" method="get">
                                                    <input type="hidden" name="kategori[]" id="kategori" value="{{$book->subKategori->kategori->id}}">
                                                    <button type="submit" class="badge" style="border: none;">{{ preg_match('/^[A-Za-z]+/', $book->subKategori->kategori->nama, $matches) ? $matches[0] : '' }}</button>
                                                </form>
                                            </li>
                                        </ul>
                                        <div class="book-footer">
                                            <div class="rate">
                                               <span class="text-yellow"><i class="flaticon-heart"></i> {{$book->hitungJumlahFavorit()}} <i class="far fa-eye mt-1 ms-3"></i> {{$book->jumlah_kunjungan}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        <div class="pagination-align style-1 wow fadeInUp" data-wow-delay="0.7s">
                            <div class="book-button-prev swiper-button-prev" style="background-color: #C6E5F2;"><i class="fa-solid fa-angle-left"></i></div>
                            <div class="book-button-next swiper-button-next" style="background-color: #8DCBE6;"><i class="fa-solid fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="content-inner-2">
                <div class="container">
                    <div class="section-head book-align">
                        <h2 class="title mb-0">Pojok Baca</h2>
                        <div class="pagination-align style-1">
                            <form action="{{route('berita-perpustakaan')}}" method="get">
                                <input type="hidden" name="jenis[]" id="jenis" value="bulan_ini">
                                <button type="submit" class="btn btn-outline-success float-right" style="color: #205E61; border-color: #205E61; border-radius:30px">Lihat Semua</button>
                            </form>
                        </div>
                    </div>
                    <div class="swiper-container blog-swiper">
                        <div class="swiper-wrapper">
                            @foreach ($post as $item)
                            <div class="swiper-slide">
                                <div class="dz-blog style-1 bg-white m-b30 wow fadeInUp" data-wow-delay="0.1s" >
                                    <div class="dz-media">
                                        <a href="{{route('berita-perpustakaan.overview',$item->slug)}}"><img src="{{ asset('storage/'.$item->gambar->file_path) }}" alt="/"></a>
                                    </div>
                                    <div class="dz-info p-3">
                                        <h6 class="dz-title">
                                            <a href="{{route('berita-perpustakaan.overview',$item->slug)}}">{{$item->judul}}</a>
                                        </h6>
                                        <p class="m-b0">{{ $item->deskripsi }}</p>
                                        <div class="dz-meta meta-bottom mt-3 pt-3">
                                            <ul class="text-start">
                                                <li class="post-date"><i class="far fa-calendar fa-fw m-r10"></i>{{ \Carbon\Carbon::parse($item->created_at)->format('d F Y')}}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
		<!-- Book Sale End -->

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


    });
</script>
@endsection

