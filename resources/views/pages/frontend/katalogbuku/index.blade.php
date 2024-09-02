@extends('layouts.frontend')
@section('title','Katalog Buku')
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

.pagination a.page-link {
    color: black !important;
}

.pagination a.page-link.active {
    background-color: #3C5E90 !important;
    color: white !important;
}

.pagination a.page-link:hover {
    background-color: #3C5E90 !important;
    color: white !important;
}

.books-grid {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
    }

.books-list {
      list-style-type: none;
      padding: 0;
}

.books-list-item {
      margin-bottom: 20px;
}

/* .dz-shop-card .dz-content{
    margin-left: -15%;
} */

svg{
    width: 30px;
}

.title{
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    height: 60px;
    font-size: 11pt;
    height:40px;
}


@media(max-width:768px){
    .title{
        height:55px;
    }

}

@media (min-width:992px) {
    .style-1 .dz-media img {
      height: 200px;
      width: 100%;
      object-fit: cover;
    }
  }

.form-check-label{
    font-size: 10pt;
}

</style>
@endsection
@section('frontend.content')
<section class="content-inner bg-white">
    <div class="container mt-4">
        <div class="row">
            <div class="col-xl-3 col-lg-4 m-b30">
                <div class="row mb-3">
                    <div class="col-lg-12">
                      <h5>Katalog Buku</h5>
                    </div>
                  </div>
                <div class="card border">
                    <div class="card-body">
                        <form id="form">
                            @csrf
                        <label style="font-weight: bold; color: #000; marhgin-bottom :5px">Cari Buku</label>
						<div class="input-group search-input">
							<input type="text" class="form-control" id="judul" name="judul" style="font-size: 10pt" placeholder="Cari berdasarkan judul...">
						</div>
                        <div class="filter-checklist">
                            {{-- select jenis --}}
                            <div class="px-3 mt-4">
                                <label style="font-weight: bold; color: #000; margin-bottom :10px">Jenis</label>
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" id="jenis_semua" name="jenis[]" value="">
                                    <label class="form-check-label" for="jenis_semua">Pilih Semua</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" id="jenis_buku" name="jenis[]" value="Buku">
                                    <label class="form-check-label" for="jenis_buku">Buku</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" id="jenis_buku_digital" name="jenis[]" value="Buku Digital">
                                    <label class="form-check-label" for="jenis_buku_digital">Buku Digital</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" id="jenis_buku_audio" name="jenis[]" value="Buku Audio">
                                    <label class="form-check-label" for="jenis_buku_audio">Buku Audio</label>
                                </div>
                            </div>
                            {{-- select kategori --}}
                            <div class="px-3 mt-4">
                                <label style="font-weight: bold; color: #000; margin-bottom :10px">Kategori</label>
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" id="kategori_semua" name="kategori[]" value="">
                                    <label class="form-check-label" for="kategori_semua">Pilih Semua</label>
                                </div>
                                @foreach ($optkategori as $item)
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" id="kategori_{{$item->nama}}" name="kategori[]" value="{{$item->id}}">
                                    <label class="form-check-label" for="kategori_{{$item->nama}}">{{$item->nama}}</label>
                                </div>
                                @endforeach
                            </div>

                            {{-- select tahun --}}
                            <div class="px-3 mt-4">
                                <label style="font-weight: bold; color: #000; margin-bottom :10px">Tahun</label>
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" id="tahun_semua" name="tahun[]" value="">
                                    <label class="form-check-label" for="tahun_semua">Pilih Semua</label>
                                </div>
                                @foreach ($optTahun as $tahun)
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" id="tahun_{{$tahun->tahun}}" name="tahun[]" value="{{$tahun->tahun}}">
                                    <label class="form-check-label" for="tahun_{{$tahun->tahun}}">{{$tahun->tahun}}</label>
                                </div>
                                @endforeach
                            </div>
                            <div class="px-3 mt-4">
                                <button type="button" class="btn btn-primary col-12" id="btn_filter" style="background: #3C5E90">Filter</button>
                            </div>
                            </form>

                        </div>
                    </div>
                  </div>
            </div>

            <div class="col-xl-9 col-lg-8 m-b30">
                <div class="row mb-3">
                    <div class="col-lg-12">
                        <div class="d-flex justify-content-end">
                                Menampilkan Total  <span id="jumlahBuku" class="mx-1"> {{$books->count()}} </span> Hasil
                                {{-- <span class="mx-4">Tampilan:</span>
                                <button type="button" class="nav-link d-inline btn btn-outline-primary btn-sm me-2" id="display_grid">
                                  <i class="fas fa-border-all" style="border:#000"></i>
                                </button>
                                <button type="button" class="nav-link d-inline btn btn-outline-primary btn-sm" id="display_list">
                                  <i class="fas fa-list"></i>
                                </button> --}}
                        </div>
                    </div>
                </div>
                {{-- <div class="shop-bx shop-profile">
                    <div class="swiper-container">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide row">
                                <div id="books-container" class="books-grid justify-content-start">

                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <div class="row book-grid-row" id="books-container">
                    @foreach ($books as $book)
					<div class="col-book style-1">
						<div class="dz-shop-card style-1">
							<div class="dz-media">
								<img src="{{ !isset($book->cover->file_path) ? asset('/') . 'web_assets/images/books/grid/not-found-book.png' : asset('storage/' . @$book->cover->file_path) }}" alt="book">
							</div>
							<div class="dz-content">
								<h5 class="title"><a href="{{route('katalog-buku.overview',$book->slug)}}">{{ $book->judul}}</a></h5>
								<ul class="dz-tags">
                                    <form action="{{route('katalog-buku')}}" method="get">
                                        <input type="hidden" name="kategori[]" id="kategori" value="{{$book->subKategori->kategori->id}}">
                                        <button type="submit" class="badge" style="border: none;">{{ preg_match('/^[A-Za-z]+/', $book->subKategori->kategori->nama, $matches) ? $matches[0] : '' }}</button>
                                    </form>
								</ul>
								<ul class="dz-rating">
                                    <li class="text-yellow"> <i class="flaticon-heart text-yellow"></i> {{$book->hitungJumlahFavorit()}} <i class="far fa-eye mt-1 ms-3"></i> {{$book->jumlah_kunjungan}}</li>
								</ul>
							</div>
						</div>
					</div>
                    @endforeach
                    <div class="d-flex justify-content-center">
                        {{ $books->withQueryString()->links() }}
                    </div>
				</div>
            </div>
        </div>
</section>
@endsection
@section('frontend.scriptbawah')
<script>
    $(document).ready(function() {

        @if($jenis == '' && $judulBuku == '' && $kategori == '')
    // var act = "{{ route('katalog-buku', ['param' => 'all']) }}" ;
    // $.ajax({
    //     type: 'GET',
    //     url: act,
    //     success: function (data) {
    //         window.history.pushState(null, null, act);
    //         $("#books-container").html(data);
    //     }
    // });
@else
    var jenis = ["{{$jenis}}"];
    var kategori = ["{{$kategori}}"];
    var judul = "{{$judulBuku}}";
    $.ajax({
        url: "{{ route('filter.katalog-buku') }}",
        method: "POST",
        data: {
            jenis: jenis,
            judul : judul,
            kategori : kategori,
            _token: "{{ csrf_token() }}"
        },
        success: function(data) {
            window.history.pushState(null, null, '{{ url()->current() }}?' + $.param({ jenis: jenis, judul: judul, kategori: kategori }));
            $("#books-container").html(data);
            $("#jumlahBuku").text($("#jumlahBukuFilter").val())
        },
        error: function(err) {
            if (err.status === 422) {
                $.each(err.responseJSON.errors, function(key, value) {
                    $("#" + key + "Error").text(value[0]);
                    $("#" + key).addClass('is-invalid');
                });
            }
        }
    });
@endif



        });
    $("#btn_filter").click(function() {
        // $(this).attr('disabled', true);

        let formData = new FormData(document.getElementById('form'));
        formData.append('_token', "{{ csrf_token() }}");
        $("#books-container").html(`
                                                            <div class="d-flex justify-content-center">
                                                                <div class="spinner-border text-primary m-1" role="status">
                                                                    <span class="sr-only">Loading...</span>
                                                                </div>
                                                            </div>
                                                        `);
        $.ajax({
            url: "{{ route('filter.katalog-buku') }}",
            method: "POST",
            data:formData,
            processData: false,
            contentType: false,
            success: function(data) {
                $("#books-container").html(data);
                $("#jumlahBuku").text($("#jumlahBukuFilter").val())
            },
            error: function(err) {
                if (err.status === 422) {
                    $.each(err.responseJSON.errors, function(key, value) {
                        $("#" + key + "Error").text(value[0]);
                        $("#" + key).addClass('is-invalid');
                    });
                }
            }
        });
    });

        // ganti tampilan grid
        $("#display_grid").click(function() {
            $(".divbuku").removeClass("style-2 col-12").addClass("col-book style-1 mb-0 col-lg-3 col-md-4 col-sm-6");
            $(".dz-shop-card").removeClass("style-2").addClass("style-1").css("height", "");
            $(".dz-media img").css({
            "height": "200px",
            "width": "100%",
            "padding-top": "",
            "object-fit": "cover"
            });
            $(".dzc").removeClass("dz-content").addClass("dz-content2");
        });

      $("#display_list").click(function() {
        $(".divbuku").removeClass("col-book style-1 mb-0 col-lg-3 col-md-4 col-sm-6").addClass("style-2 col-12");
        $(".dz-shop-card").removeClass("style-1").addClass("style-2").css("height", "170px");
        $(".dz-media img").css({
        "height": "150px",
        "width": "100px",
        "padding-top": "-30px",
        });
        $(".dzc").removeClass("dz-content2").addClass("dz-content");
      });



</script>
@endsection

