@if($acara->count() != 0)
<div class="row">
    @foreach ($acara as $item)
    <div class="col-lg-4 col-md-6">
        <div class="content-box style-1">
            <div class="dz-info mb-0">
                <h4 class="title">{{ $item->judul }}</h4>
                <p class="deskripsiAcara">{{ $item->deskripsi }}</p>
            </div>
            <div class="dz-banner-media1.jpg mb-3">
                <img src="{{ asset('storage/' . $item->gambar->file_path) }}" alt="">
            </div>
            <div class="dz-bottom">
                <span class="btn-link btnhover3"><i class="fa fa-calendar" aria-hidden="true"></i> {{ \Carbon\Carbon::parse($item->created_at)->format('d F Y') }}</span>
                <span class="btn-link btnhover3"><i class="fa-solid fa-clock"></i> {{ $item->waktu_mulai }} - {{ $item->waktu_selesai }} WITA</span> <br>
                <span class="btn-link btnhover3"><i class="fa-solid fa-location-dot"></i> {{ $item->lokasi }}</span>
            </div>
        </div>
    </div>
    @endforeach
</div>
<nav aria-label="Blog Pagination">
    <div class="d-flex justify-content-center mt-3">
        {!! $acara->withQueryString()->links() !!}
    </div>
</nav>
@else
<div class="col-11 alert alert-warning alert-dismissible fade show" role="alert">
    Acara yang Anda cari tidak tersedia.
  </div>
@endif
