@if($books->count() != 0)
{{-- @foreach ($books as $book)
<div class="books-card wow fadeInUp m-3" style="width: 168px">
    <a href="{{route('katalog-buku.overview',$book->slug)}}">
        <div class="dz-media">
            <img style=" width: 165px;height: 200px;object-fit: cover;" src="{{ asset('storage/'.$book->cover->file_path) }}" alt="book">
        </div>
        <div class="dz-content">
            <h6 class="title"><a href="{{route('katalog-buku.overview',$book->slug)}}"><span class="text-black">{{Str::limit($book->judul,40)}}</span></a></h6>
            <ul class="dz-tags">
                <li>
                    <a href="#"><span style="color: #D4C31B;">{{$book->subKategori->kategori->nama}}</span></a>
                </li>

            </ul>
            <div class="book-footer">
                <div class="rate"  style="color: #D4C31B;">
                    <i class="flaticon-heart"></i> {{$book->hitungJumlahFavorit()}}  <i class="far fa-eye mt-1 ms-3"></i> {{$book->jumlah_kunjungan}}
                </div>
            </div>
        </div>
    </a>
</div>
@endforeach --}}
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

@else
<div class="col-12 alert alert-warning alert-dismissible fade show" role="alert">
    Buku yang Anda cari tidak tersedia.
  </div>
@endif
<input type="hidden" id="jumlahBukuFilter" value="{{$books->count()}}">
