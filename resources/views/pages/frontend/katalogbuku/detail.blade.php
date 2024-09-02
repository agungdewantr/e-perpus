@extends('layouts.frontend')
@section('title','katalog Buku - Overview')
@section('frontend.content')
@section('frontend.scriptatas')
<style>
 .dz-media {
    position: relative;
}

.overlay-container {
    position: relative;
}

.bookmark-btn,
.divPlay {
    position: absolute;
    top: 0;
    z-index: 2; /* Atur nilai z-index sesuai kebutuhan agar div berada di atas gambar */
}

#btnLove {
    right: 5%;
    top:5%;
    opacity: 0;
}

.divPlay {
    top: 50%;
    text-align: center;
    left: 50%; /* Tempat tengah horizontal */
    transform: translateX(-50%);
    opacity: 0;
    /* Gaya tambahan sesuai kebutuhan */
}

th{
    color: #3C5E90;
}

.secUlasan{
    height: 370px;
    overflow-y: auto;
    overflow-x: hidden;
    position: relative;
    -ms-overflow-style: none;  /* Internet Explorer 10+ */
    scrollbar-width: none;
    margin-bottom: 10px;
}

.secUlasan::-webkit-scrollbar {
    display: none;  /* Safari and Chrome */
}

.subtitle{
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.overlay-container:hover #btnLove, .overlay-container:hover .divPlay {
   opacity: 1;
}


</style>
@endsection
<section class="content-inner-1">
    <div class="container" style="margin-top: -30px">
        <div class="row book-grid-row style-4 m-b60">
            <div class="col">
                <div class="dz-box">
                    <div class="dz-media">
                        <div class="overlay-container">
                            <img src="{{ !isset($book->cover->file_path) ? asset('/') . 'web_assets/images/books/grid/not-found-book.png' : asset('storage/' . @$book->cover->file_path) }}" alt="book">
                            @if(@auth()->User()->role->nama != 'petugas')
                            <div class="bookmark-btn style-1 d-none d-sm-block" id="btnLove">
                                <input class="form-check-input" type="checkbox" id="checkBoxFavorit" {{@$isFavorit ? 'checked' : '' }}  onclick="addToFavoritOrKeranjang('favorit')">
                                <label class="form-check-label" for="checkBoxFavorit">
                                    <i class="flaticon-heart"></i>
                                </label>
                            </div>
                            @endif
                            @if($book->file_audio_id && @auth()->User()->role->nama != 'petugas')
                            <div class="divPlay">
                                <button id="playButton" style="border: none; background:none; font-size: 30pt; color: #fff"><i id="playIcon" class="fa-solid fa-circle-play"></i></button>
                                <h6 class="text-white">Putar</h6>
                            </div>
                            @endif
                        </div>
                        @if($book->jenis == 'Buku' && @auth()->User()->role->nama != 'petugas')
                        <div class="row mt-3">
                            <div class="col-8">
                                <button class="btn btn-biru btn-sm col-12 btnPinjam" style="font-size:12pt" data-bs-toggle="modal">Pinjam</button>
                            </div>
                            <div class="col-4">
                                <button type="button" onclick="addToFavoritOrKeranjang('keranjang')" class="btn btn-biru col-12" style="font-size:12pt"><i class="fa-solid fa-cart-shopping"></i></button>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="dz-content">
                        <div class="dz-header">
                            <h3 class="title">{{$book->judul}}</h3>
                            <div class="shop-item-rating">
                                    <form action="{{route('katalog-buku')}}" method="get">
                                        <input type="hidden" name="kategori[]" id="kategori" value="{{$book->subKategori->kategori->id}}">
                                        <button type="submit" class="badge" style="border: none;">{{$book->subKategori->kategori->nama}}</button>
                                    </form>
                                </div>
                                @if($book->jenis == 'Buku')
                                <span class="font-weight-bold">Stok : {{App\Models\Buku::get_stok($book->id)}} Buku</span>
                                @endif
                                <div class="mt-3" style="display: flex; align-items: center; ">
                                    <span class="me-4"><i class="fa-regular fa-heart"></i> {{$book->hitungJumlahFavorit()}}</span>
                                    <span class="me-4"><i class="fa-regular fa-eye"></i> {{$book->jumlah_kunjungan}}</span>

                                    @if($book->file_digital_id && @auth()->User()->role->nama != 'petugas')
                                    <span id="eBookLink" onclick="handleEBookClick()" title="Baca E-Book" style="cursor: pointer">
                                        <i class="fas fa-file-pdf"></i> Baca E-Book
                                    </span>
                                    @endif
                                    @if($book->file_audio_id && auth()->user() && @auth()->User()->role->nama != 'petugas')
                                    <audio id="audioPlayer" style="height: 20px" controls class="col-6" title="Dengarkan Audio Buku">
                                        <source src="{{ asset('storage/' . $book->audio->file_path) }}" type="audio/mpeg"/>
                                    </audio>
                                    @endif
                            </div>
                        </div>
                        <div class="dz-body">
                            <div class="book-detail">
                                <ul class="book-info">
                                    <li>
                                        <div class="writer-info">
                                            <div>
                                                <span>Penulis</span> <p class="text-biru">{{$book->penulises[0]->nama}}</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li><span>Penerbit</span><p class="text-biru">{{$book->penerbit->namaPenerbit}}</p></li>
                                    <li><span>Tahun</span><p class="text-biru">{{ \Carbon\Carbon::parse($book->tanggal_terbit)->format('Y')}}</p></li>
                                </ul>
                            </div>
                            <p class="text-1"> {{$book->deskripsi}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-8">
                <div class="product-description tabs-site-button">
                    <ul class="nav nav-tabs">
                        <li><a data-bs-toggle="tab" href="#graphic-design-1" class="active text-primary">Detail Buku</a></li>
                        <li><a data-bs-toggle="tab" href="#developement-1" class="text-primary">Ulasan Pembaca</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="graphic-design-1" class="tab-pane show active">
                            <table class="table border">
                                <tr>
                                    <th>Judul Buku</th>
                                    <td>{{$book->judul}}</td>
                                </tr>
                                <tr>
                                    <th>Penulis</th>
                                    <td>{{$book->penulises[0]->nama}}</td>
                                </tr>
                                <tr>
                                    <th>ISBN</th>
                                    <td>{{$book->isbn}}</td>
                                </tr>
                                <tr>
                                    <th>Bahasa</th>
                                    <td>{{$book->bahasa ? $book->bahasa : '-'}}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Terbit</th>
                                    <td>{{ \Carbon\Carbon::parse($book->tanggal_terbit)->format('d F Y')}}</td>
                                </tr>
                                <tr>
                                    <th>Penerbit</th>
                                    <td>{{{$book->penerbit->namaPenerbit}}}</td>
                                </tr>
                                <tr>
                                    <th>Halaman</th>
                                    <td>{{$book->jumlah_halaman}}</td>
                                </tr>
                                <tr class="tags">
                                    <th>Kategori</th>
                                    <td>
                                        <form action="{{route('katalog-buku')}}" method="get">
                                            <input type="hidden" name="kategori[]" id="kategori" value="{{$book->subKategori->kategori->id}}">
                                            <button type="submit" class="badge" style="border: none;">{{$book->subKategori->kategori->nama}}</button>
                                        </form>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div id="developement-1" class="tab-pane">
                            <div class="clear" id="divUlasan">
                                <div class="post-comments comments-area style-1 clearfix">
                                    <div class="secUlasan">
                                        @if($ulasan->count() == 0)
                                            <span>Belum ada ulasan</span>
                                        @else
                                        @foreach($ulasan as $item)
                                            <div id="comment">
                                                <ol class="comment-list">
                                                    <li class="comment even thread-even depth-1 comment" id="comment-2">
                                                        <div class="comment-body">
                                                        <div class="comment-author vcard">
                                                                <img src="{{ $item->profilAnggota->user->foto ? asset('storage/' . $item->profilAnggota->user->foto->file_path) : Storage::url('user/default.png') }}" alt="" class="avatar"/>
                                                                <div class="row">
                                                                    <div class="col">
                                                                        <cite class="fn">{{$item->profilAnggota->nama_lengkap}}</cite>
                                                                    </div>
                                                                    <div class="col text-end">
                                                                        <small class="text-secondary">{{$item->waktuYangLalu()}}</small>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                        <div class="comment-content dlab-page-text">
                                                            <p>{{$item->ulasan}}</p>
                                                        </div>
                                                    </div>
                                                    </li>

                                                </ol>
                                            </div>
                                        @endforeach
                                        @endif
                                    </div>
                                    @if($cek_pinjam > 0 && $status_ulasan === 0 && auth()->user() && auth()->user()->profilAnggota)
                                    <div class="default-form comment-respond style-1" id="respond">
                                        <h4 class="comment-reply-title" id="reply-title">Berikan Ulasan <small> <a rel="nofollow" id="cancel-comment-reply-link" href="javascript:void(0)" style="display:none;">Cancel reply</a> </small></h4>
                                        <div class="clearfix">
                                            <form method="post" id="comments_form" class="comment-form" novalidate>
                                            <p class="comment-form-comment"><textarea id="ulasan" placeholder="Tulis ulasan" class="form-control4" name="comment" cols="45" rows="3" required="required"></textarea></p>
                                            <p class="col-md-12 col-sm-12 col-xs-12 form-submit">
                                                <button  id="kirimUlasan" type="button" class="submit btn btn-primary filled">
                                                Kirim <i class="fa fa-angle-right m-l10"></i>
                                                </button>
                                            </p>
                                            </form>
                                        </div>
                                    </div>
                                  @endif
                               </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 mt-5 mt-xl-0">
                <div class="widget">
                    <h4 class="widget-title">BUKU SERUPA</h4>
                    <div class="row">
                        @foreach ($buku_rekomendasi as $item)
                        <div class="col-xl-12 col-lg-6">
                            <div class="dz-shop-card style-5">
                                <div class="dz-media">
                                    <img src="{{ !isset($item->cover->file_path) ? asset('/') . 'web_assets/images/books/grid/not-found-book.png' : asset('storage/' . @$item->cover->file_path) }}" alt="book">
                                </div>
                                <div class="dz-content">
                                    <a href="{{route('katalog-buku.overview',$item->slug)}}"><h6 class="subtitle">{{ Str::limit($item->judul, 35) }}
                                    </h6>
                                </a>
                                    <ul class="dz-tags">
                                        <li>{{ $item->subKategori->kategori->nama }}</li>
                                    </ul>
                                    @if($item->jenis == 'Buku' && @auth()->User()->role->nama != 'petugas')
                                    <a  href="#" onclick="pinjamBuku({{$item->id}})" class="btn btn-outline-primary btn-sm btnhover2"> Pinjam</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('frontend.scriptbawah')
<script>
    $(document).ready(function() {
        // $('#modalUnduhBuktiPeminjaman_rellarphp').modal('show');

        //OPEN POP UP MODAL CEK KELENGKAPAN DATA
            @if (auth()->check())
                var auth = @json(auth()->user());
                var profilAnggota = @json(auth()->user()->profilAnggota);
                var role = @json(auth()->User()->role->nama);
                if (!profilAnggota && role == 'anggota') {
                    $('#modalDataBelumLengkap_rellarphp').modal('show');
                }
            @endif
                //CLOSE POP UP MODAL CEK KELENGKAPAN DATA

                $("#kirimUlasan" ).on( "click", function() {
                    $.ajax({
                        url : "{{route('ulasan')}}",
                        method : 'POST',
                        data: {
                            buku_id: {{$book->id}},
                            ulasan: $("#ulasan").val(),
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            $("#divUlasan").html(data);
                            Swal.fire('Berhasil', 'Ulasan berhasil disubmit.', 'success');

                        },
                        error: function(err) {
                            Swal.fire('Gagal', 'Ulasan gagal disubmit.', 'error');
                        }
                    });
                });


            $(".btnPinjam" ).on( "click", function() {
                @if (auth()->check())
                var auth = @json(auth()->user());
                var profilAnggota = @json(auth()->user()->profilAnggota);
                if (!profilAnggota) {
                    $('#modalDataBelumLengkap_rellarphp').modal('show');
                }else{
                    if(profilAnggota.is_verified == true){
                        Swal.fire({
                            title: "Apakah anda yakin?",
                            text: "Akan meminjam buku ini",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Ya"
                            }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url : "{{route('katalog-buku.pinjam')}}",
                                    method : 'POST',
                                    data: {
                                        buku_id: {{$book->id}},
                                        _token: "{{ csrf_token() }}"
                                    },
                                    success: function(res) {
                                        if (res.responses.some(response => response.success)) {
                                            if (res.responses.length === 1) {
                                                var tanggalAmbil = res.responses[0].tanggal_ambil
                                                var judulBuku = res.responses[0].judul_buku
                                                var noAnggota = res.responses[0].nomor_anggota
                                                var namaAnggota = res.responses[0].nama_anggota
                                                $('#nomor_anggota').text(noAnggota);
                                                $('#nama_anggota').text(namaAnggota);
                                                $('#tanggal_diambil').text(tanggalAmbil);
                                                $('#list_buku_pinjam').text(judulBuku);
                                                $('#tgl_max_ambil').text(tanggalAmbil);
                                                $('#total_buku_pinjam').text(res.responses.length);
                                                $('#downloadPdfButton').attr('href', '/cetakbuktipeminjaman/' + res.responses[0].kode_nota);
                                                $('#modalDataBerhasil_rellarphp').modal('show');

                                            } else {
                                                var tanggalAmbil = res.responses[0].tanggal_ambil
                                                var noAnggota = res.responses[0].nomor_anggota
                                                var namaAnggota = res.responses[0].nama_anggota
                                                var tableContent = '';
                                                res.responses.forEach((response, index) => {
                                                    tableContent += `${index + 1}. ${response.judul_buku} <br>`;
                                                });
                                                $('#nomor_anggota').text(noAnggota);
                                                $('#nama_anggota').text(namaAnggota);
                                                $('#tanggal_diambil').text(tanggalAmbil);
                                                $('#tgl_max_ambil').text(tanggalAmbil);
                                                $('#total_buku_pinjam').text(res.responses.length);
                                                $('#list_buku_pinjam').html(tableContent);
                                                $('#modalDataBerhasil_rellarphp').modal('show');
                                                // Swal.fire('Berhasil', 'Peminjaman Buku berhasil untuk beberapa buku. Silakan tunggu verifikasi dari petugas.', 'success');
                                            }
                                        } else {
                                            if (res.responses.length === 1) {
                                                Swal.fire('Warning', 'Peminjaman Buku Gagal. Buku ini mungkin sudah Anda pinjam.', 'warning');
                                            } else {
                                                Swal.fire('Warning', 'Peminjaman Buku Gagal untuk beberapa buku. Beberapa buku mungkin sudah Anda pinjam.', 'warning');
                                            }
                                        }
                                    },
                                    error: function(err) {
                                        Swal.fire('Warning', 'Peminjaman Buku Gagal, tidak ada stok buku tersedia', 'error');
                                    }
                                })
                                // $('#modalDataBerhasil_rellarphp').modal('show');
                            }
                        });
                    } else{
                        Swal.fire('Warning', 'Akun anda belum terverifikasi.', 'warning');
                    }

                }
                @else
                    $('#modalDataBelumDaftar_rellarphp').modal('show');
                @endif
            } );

    });

    // Function menambahkan buku ke favorit atau keranjang
    function addToFavoritOrKeranjang(param) {
        @if (auth()->check())
        var auth = @json(auth()->user());
        var profilAnggota = @json(auth()->user()->profilAnggota);
        submit = false;
        if (profilAnggota) {
            if(param == 'favorit'){
                url = "{{route('favorit.store')}}";
                var submit = true;
            }else{
                var isKeranjang = "{{$isKeranjang}}";
                url = "{{route('keranjang.store')}}";
                if(isKeranjang){
                    submit = false;
                }else{
                    submit = true;
                }
            }
            if(submit){
                $.ajax({
                    url,
                    method : 'POST',
                    data: {
                        buku_id: {{$book->id}},
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        $("#jumlah"+res.data).text(res.jumlah);
                        $("#jumlah"+res.data+"2").text(res.jumlah);
                        if(res.type == 'create'){
                            Swal.fire('Berhasil', 'Buku berhasil ditambahkan ke ' + res.data, 'success');
                        } else {
                            Swal.fire('Berhasil', 'Buku berhasil dihapus dari '+ res.data, 'success');
                        }
                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
            }else{
                Swal.fire('Warning', 'Buku ini telah anda tambahkan ke keranjang', 'info');
            }
        }else{
            $('#modalDataBelumLengkap_rellarphp').modal('show');
        }
        @else
        var checkBox = document.getElementById('checkBoxFavorit');
        checkBox.checked = false;
        $('#modalDataBelumDaftar_rellarphp').modal('show');
        return;
        @endif
    }

    // cek ketika klik baca ebook
    @if($book->file_digital_id)
    function handleEBookClick() {

        var isLoggedIn = @json(auth()->check());
        if (isLoggedIn) {
            window.open("{{ route('katalog-buku.show.pdf', $book->slug) }}", "_blank");
        } else {
            $('#modalDataBelumDaftar_rellarphp').modal('show');
        }
    }
    @endif

    // cek ketika play audio
    @if($book->file_audio_id)
    var isLoggedIn2 = @json(auth()->check());
    var playButton = document.getElementById('playButton');
    var playIcon = document.getElementById('playIcon');
    var audio = document.getElementById('audioPlayer');

        playButton.addEventListener('click', function () {
            if (isLoggedIn2) {
                // Cek apakah audio sedang dimainkan atau tidak
                if (audio.paused) {
                    // Jika audio sedang di-pause, maka putar dan ganti ikon menjadi pause
                    audio.play();
                    playIcon.classList.remove('fa-circle-play');
                    playIcon.classList.add('fa-pause');

                } else {
                    // Jika audio sedang dimainkan, maka pause dan ganti ikon menjadi play
                    audio.pause();
                    playIcon.classList.remove('fa-pause');
                    playIcon.classList.add('fa-circle-play');
                }

                // Tambahkan event listener untuk mengubah ikon saat audio selesai
                audio.addEventListener('ended', function () {
                    playIcon.classList.remove('fa-pause');
                    playIcon.classList.add('fa-circle-play');
                });
            } else {
                $('#modalDataBelumDaftar_rellarphp').modal('show');
            }

        });
    @endif

    // set scroll ulasan
    var secUlasan = document.querySelector('.secUlasan');
    var indicatorHeight = 100;
    secUlasan.style.setProperty('--indicator-height', indicatorHeight + 'px');

    secUlasan.addEventListener('scroll', function() {
        var scrollPercentage = (secUlasan.scrollTop / (secUlasan.scrollHeight - secUlasan.clientHeight)) * 100;
        indicatorHeight = (scrollPercentage * secUlasan.clientHeight) / 100;
        secUlasan.style.setProperty('--indicator-height', indicatorHeight + 'px');
    });

</script>
@endsection

