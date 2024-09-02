{{-- <div class="row align-items-center">
    @foreach ($acara as $item)
        <div class="col-2 text-black fw-bold d-flex align-items-center">
            <h4 class="me-2 mb-0">{{ \Carbon\Carbon::parse($item->created_at)->format('d') }}</h4>
            <span>{{ \Carbon\Carbon::parse($item->created_at)->format('F Y') }}</span>
        </div>
        <div class="col-4"><img src="{{ asset('storage/' . $item->gambar->file_path) }}"
                style="width: 100%" alt="/">
        </div>
        <div class="col-6">
            <li>
                <h6>{{ $item->judul }}</h6>
            </li>
            <li><small> Lokasi : {{ $item->lokasi }}</small></li>
            <li><small>Waktu : {{ $item->waktu_mulai }} - {{ $item->waktu_selesai }} WITA</small></li>
            <li><small>Peserta : {{ $item->peserta }}</small></li>
            <p><small>{{ $item->deskripsi }}</small></p>
        </div>
    @endforeach
</div> --}}
<div class="row">
    @foreach ($acara as $item)
    <div class="col-lg-4 col-md-6">
        <div class="content-box style-1">
            <div class="dz-info mb-0">
                <h4 class="title">{{ $item->judul }} Perpustakaan Keliling</h4>
                <p  class="deskripsiAcara">{{ $item->deskripsi }}</p>
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
