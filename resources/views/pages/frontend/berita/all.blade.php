{{-- @foreach ($berita as $item)
<div class="col-lg-4">
    <div class="dz-blog style-1 bg-white m-b30 wow fadeInUp" data-wow-delay="0.1s">
        <div class="dz-media">
            <a href="{{ route('berita-perpustakaan.overview', 'ini-slug-berita') }}"><img
                    src="{{ asset('storage/' . $item->gambar->file_path) }}" alt="/"></a>
        </div>
        <div class="dz-info p-3">
            <h6 class="dz-title">
                <a
                    href="{{ route('berita-perpustakaan.overview', $item->slug) }}">{{ Str::limit($item->judul, 60) }}</a>
            </h6>
            <small
                class="text-black">{{ \Carbon\Carbon::parse($item->created_at)->format('d F Y') }}</small>
            <p>
                {{ Str::limit($item->deskripsi, 80) }}</p>
        </div>
    </div>
</div>

@endforeach --}}
<div class="row">
@foreach ($berita as $item)
<div class="col-xl-6 col-lg-6">
    <div class="dz-blog style-1 bg-white m-b30">
        <div class="dz-media dz-img-effect zoom">
            <img src="{{ asset('storage/' . $item->gambar->file_path) }}" alt="">
        </div>
        <div class="dz-info">
            <h4 class="dz-title">
                <a href="{{ route('berita-perpustakaan.overview', $item->slug) }}">{{$item->judul}}</a>
            </h4>
            <p class="m-b0 desc_berita">{{$item->deskripsi}}</p>
            <div class="dz-meta meta-bottom">
                <ul class="border-0 pt-0">
                    <li class="post-date"><i class="far fa-calendar fa-fw m-r10"></i>{{ \Carbon\Carbon::parse($item->created_at)->format('d F Y') }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endforeach
</div>
<div class="d-flex justify-content-center">
{{ $berita->withQueryString()->links() }}
</div>
