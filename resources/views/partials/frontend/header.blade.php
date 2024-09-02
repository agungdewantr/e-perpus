

   <!-- Header -->
    <header class="site-header mo-left header style-1">
        <!-- Main Header -->
        <div class="sticky-header main-bar-wraper navbar-expand-lg">
            <div class="main-bar clearfix bg-primary">
                <div class="container clearfix">
                    <!-- Website Logo -->
                    <div class="logo-header logo-dark">
                        <a href="{{route('beranda')}}"><img src="{{ asset('/') }}web_assets/images/logo-dark.png" alt="logo"></a>
                    </div>
                    <!-- Nav Toggle Button -->
                    <button class="navbar-toggler collapsed navicon justify-content-end" type="button"
                        data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    @if (isset(auth()->user()->username))
                        @php
                            $tot_notif = DB::table('notifikasis')
                                            ->where('user_id_to', auth()->user()->id)
                                            ->where('is_active', 1)
                                            ->count();
                            // dd($tot_notif)
                            $list_notif = App\Models\Notifikasi::where('user_id_to', auth()->user()->id)
                                                                // ->where('is_active', 1)
                                                                ->orderBy('updated_at', 'desc')
                                                                ->get();
                        @endphp
                        <button type="button" class="navbar-toggler nav-link box cart-btn2" id="readNotif">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 17H2a3 3 0 0 0 3-3V9a7 7 0 0 1 14 0v5a3 3 0 0 0 3 3zm-8.27 4a2 2 0 0 1-3.46 0"></path></svg>
                            <span class="badge bg-white text-black" id='totalNotifNotYetRead2'>{{$tot_notif??'0'}}</span>
                        </button>
                        <ul class="dropdown-menu cart-list2" id="listnotif">
                            @foreach ($list_notif as $list)
                                <li class="cart-item" style="{{ $list->is_active ? 'background: #d2f2ff;' : '' }} ; padding:2px;">
                                    <div class="media">
                                        <div class="media-body">
                                            <h6 class="">
                                                <a href="{{$list->route}}" class="media-heading"
                                                    @if ($list->tentang == 'Tolak Perpanjangan' || $list->tentang =='Terima Perpanjangan' || $list->tentang =='Batas Pengembalian')
                                                        onclick="subMenu('riwayatpeminjaman')"
                                                    @endif>
                                                    {{$list->tentang}}
                                                </a>
                                            </h6>
                                            <span class="textNotif">{{$list->isi}}</span>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    <!-- EXTRA NAV -->
                    <div class="extra-nav">
                        <div class="extra-cell">
                            <ul class="navbar-nav header-right">
                                @if (!isset(auth()->user()->username))
                                    <li class="nav-item">
                                        <button type="button" class="btn btn-secondary btn-sm button-primary"
                                            style="color: #000000;" data-bs-toggle="modal" data-bs-target="#modalLogin"
                                            onclick="show_login()">Masuk</button>
                                    </li>
                                @else
                                @if(auth()->User()->role->nama == 'anggota')
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{route('detailanggota.index')}}" onclick="subMenu('favorit')">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24"
                                                width="24px" fill="#000000">
                                                <path d="M0 0h24v24H0V0z" fill="none" />
                                                <path
                                                    d="M16.5 3c-1.74 0-3.41.81-4.5 2.09C10.91 3.81 9.24 3 7.5 3 4.42 3 2 5.42 2 8.5c0 3.78 3.4 6.86 8.55 11.54L12 21.35l1.45-1.32C18.6 15.36 22 12.28 22 8.5 22 5.42 19.58 3 16.5 3zm-4.4 15.55l-.1.1-.1-.1C7.14 14.24 4 11.39 4 8.5 4 6.5 5.5 5 7.5 5c1.54 0 3.04.99 3.57 2.36h1.87C13.46 5.99 14.96 5 16.5 5c2 0 3.5 1.5 3.5 3.5 0 2.89-3.14 5.74-7.9 10.05z" />
                                            </svg>
                                            <span class="badge bg-white text-black" id="jumlahfavorit">@if(auth()->user()->profilAnggota) {{  App\Models\Favorit::where('profil_anggota_id', auth()->user()->profilAnggota->id)->count()}} @else 0 @endif</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"  href="{{route('detailanggota.index')}}" onclick="subMenu('keranjang')">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24"
                                                width="24px" fill="#000000">
                                                <path d="M0 0h24v24H0V0z" fill="none" />
                                                <path
                                                    d="M15.55 13c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.37-.66-.11-1.48-.87-1.48H5.21l-.94-2H1v2h2l3.6 7.59-1.35 2.44C4.52 15.37 5.48 17 7 17h12v-2H7l1.1-2h7.45zM6.16 6h12.15l-2.76 5H8.53L6.16 6zM7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z" />
                                            </svg>
                                            <span class="badge bg-white text-black" id="jumlahkeranjang">@if(auth()->user()->profilAnggota) {{ App\Models\Keranjang::where('profil_anggota_id', auth()->user()->profilAnggota->id)->count()}} @else 0 @endif</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <button type="button" class="nav-link box cart-btn" id="readNotif">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 17H2a3 3 0 0 0 3-3V9a7 7 0 0 1 14 0v5a3 3 0 0 0 3 3zm-8.27 4a2 2 0 0 1-3.46 0"></path></svg>
                                            <span class="badge bg-white text-black" id='totalNotifNotYetRead'>{{$tot_notif}}</span>
                                        </button>
                                        <ul class="dropdown-menu cart-list" id="listnotif">
                                            @foreach ($list_notif as $list)
                                                <li class="cart-item" style="{{ $list->is_active ? 'background: #d2f2ff;' : '' }} ; padding:2px;">
                                                    <div class="media">
                                                        <div class="media-body">
                                                            <h6 class="">
                                                                <a href="{{$list->route}}" class="media-heading"
                                                                    @if ($list->tentang == 'Tolak Perpanjangan' || $list->tentang =='Terima Perpanjangan' || $list->tentang =='Batas Pengembalian')
                                                                        onclick="subMenu('riwayatpeminjaman')"
                                                                    @endif>
                                                                    {{$list->tentang}}
                                                                </a>
                                                            </h6>
                                                            <span class="textNotif">{{$list->isi}}</span>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @endif
                                    <li class="nav-item dropdown profile-dropdown  ms-4">
                                        <a class="nav-link" href="javascript:void(0);" role="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <img src="{{  auth()->user()->foto ? asset('storage/' .  auth()->user()->foto->file_path) : Storage::url('user/default.png') }}" alt=""  style="height:50px; width:50px; object-fit: cover;">
                                            <div class="profile-info">
                                                <h6 class="title text-black" style="font-weight: 500">{{ auth()->user()->username ?? '' }}</h6>
                                            </div>
                                        </a>
                                        <div class="dropdown-menu py-0 dropdown-menu-end">
                                            <div class="dropdown-header">
                                                <h6 class="m-0 text-black">{{ auth()->user()->username ?? '' }}</h6>
                                                <span>{{ auth()->user()->email ?? 'Belum Ada Email' }}</span>
                                            </div>
                                            @if(auth()->User()->role->nama == 'anggota')
                                            <div class="dropdown-body">
                                                <a href="{{ route('detailanggota.index', null) }}"
                                                    class="dropdown-item d-flex justify-content-between align-items-center ai-icon">
                                                    <div>
                                                        <svg xmlns="http://www.w3.org/2000/svg" height="20px"
                                                            viewBox="0 0 24 24" width="20px" fill="#000000">
                                                            <path d="M0 0h24v24H0V0z" fill="none" />
                                                            <path
                                                                d="M12 6c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2m0 10c2.7 0 5.8 1.29 6 2H6c.23-.72 3.31-2 6-2m0-12C9.79 4 8 5.79 8 8s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 10c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                                        </svg>
                                                        <span class="ms-2">Profile</span>
                                                    </div>
                                                </a>

                                            </div>
                                            @endif
                                            <div class="dropdown-footer">
                                                <a class="btn btn-primary w-100 btnhover btn-sm" data-bs-toggle="modal" data-bs-target="#modalLogin" onclick="show_logout()">
                                                    {{ __('Keluar') }}
                                                </a>

                                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                    class="d-none">
                                                    @csrf
                                                </form>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    <!-- Main Nav -->
                    <div class="header-nav navbar-collapse collapse justify-content-center" id="navbarNavDropdown">
                        <div class="logo-header logo-dark">
                            <a href="index.html"><img src="{{ asset('/') }}web_assets/images/logo-dark.png"
                                    alt=""></a>
                        </div>
                        <ul class="nav navbar-nav">
                            <li>
                                <a href="{{ route('beranda') }}"><span class="text-black" style="font-weight: 500">Beranda</span></a>
                            </li>
                            <li>
                                <a href="{{ route('katalog-buku') }}"><span class="text-black" style="font-weight: 500">Katalog
                                        Buku</span></a>
                            </li>
                            <li>
                                <a href="{{ route('berita-perpustakaan') }}"><span
                                        class="text-black" style="font-weight: 500">Berita</span></a>
                            </li>
                            <li>
                                <a href="{{ route('acara-perpustakaan') }}" style="font-weight: 500"><span class="text-black">Acara</span></a>
                            </li>
                            <li>
                                <a href="{{ route('tentang-kami') }}"><span class="text-black" style="font-weight: 500">Tentang
                                        Kami</span></a>
                            </li>
                            <div class="dz-social-icon">
                                <ul>
                                    @if (!isset(auth()->user()->username))
                                            <li class="nav-item">
                                                <button type="button" class="btn btn-secondary btn-sm button-primary"
                                                    style="color: #000000;" data-bs-toggle="modal" data-bs-target="#modalLogin"
                                                    onclick="show_login()">Masuk</button>
                                            </li>
                                    @else
                                        <li class="nav-item dropdown profile-dropdown col-12 mb-3" style="float:left">
                                            <a class="nav-link" href="javascript:void(0);" role="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <img src="{{  auth()->user()->foto ? asset('storage/' .  auth()->user()->foto->file_path) : Storage::url('user/default.png') }}" alt=""  style="height:50px; width:50px; object-fit: cover;">
                                                <div class="profile-info">
                                                    <h6 class="title text-black">{{ auth()->user()->username ?? '' }}</h6>
                                                </div>
                                            </a>
                                            <div class="dropdown-menu py-0 dropdown-menu-end">
                                                <div class="dropdown-header">
                                                    <h6 class="m-0 text-black" style="font-weight: 500">{{ auth()->user()->username ?? '' }}</h6>
                                                    <span>{{ auth()->user()->email ?? 'Belum Ada Email' }}</span>
                                                </div>
                                                <div class="dropdown-body">
                                                    <a href="{{ route('detailanggota.index', null) }}"
                                                        class="dropdown-item d-flex justify-content-between align-items-center ai-icon" style="border:none; background:none">
                                                        <div>
                                                            <svg xmlns="http://www.w3.org/2000/svg" height="20px"
                                                                viewBox="0 0 24 24" width="20px" fill="#000000">
                                                                <path d="M0 0h24v24H0V0z" fill="none" />
                                                                <path
                                                                    d="M12 6c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2m0 10c2.7 0 5.8 1.29 6 2H6c.23-.72 3.31-2 6-2m0-12C9.79 4 8 5.79 8 8s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 10c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                                            </svg>
                                                            <span class="ms-2">Profile</span>
                                                        </div>
                                                    </a>

                                                </div>
                                                <div class="dropdown-footer">
                                                    <a class="btn btn-primary w-100 btnhover btn-sm" data-bs-toggle="modal" data-bs-target="#modalLogin" onclick="show_logout()">
                                                        {{ __('Keluar') }}
                                                    </a>

                                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                        class="d-none">
                                                        @csrf
                                                    </form>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="nav-item me-3" style="float:left">
                                            <a class="nav-link bg-primary" href="{{route('detailanggota.index')}}" onclick="subMenu('favorit')">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24"
                                                    width="24px" fill="#000000">
                                                    <path d="M0 0h24v24H0V0z" fill="none" />
                                                    <path
                                                        d="M16.5 3c-1.74 0-3.41.81-4.5 2.09C10.91 3.81 9.24 3 7.5 3 4.42 3 2 5.42 2 8.5c0 3.78 3.4 6.86 8.55 11.54L12 21.35l1.45-1.32C18.6 15.36 22 12.28 22 8.5 22 5.42 19.58 3 16.5 3zm-4.4 15.55l-.1.1-.1-.1C7.14 14.24 4 11.39 4 8.5 4 6.5 5.5 5 7.5 5c1.54 0 3.04.99 3.57 2.36h1.87C13.46 5.99 14.96 5 16.5 5c2 0 3.5 1.5 3.5 3.5 0 2.89-3.14 5.74-7.9 10.05z" />
                                                </svg>
                                                <span class="badge bg-secondary text-black position-absolute" id="jumlahkavorit2">@if(auth()->user()->profilAnggota) {{  App\Models\Favorit::where('profil_anggota_id', auth()->user()->profilAnggota->id)->count()}} @else 0 @endif</span>
                                            </a>
                                        </li>
                                        <li class="nav-item" style="float:left">
                                            <a class="nav-link bg-primary" href="{{route('detailanggota.index')}}" onclick="subMenu('keranjang')">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24"
                                                    width="24px" fill="#000000">
                                                    <path d="M0 0h24v24H0V0z" fill="none" />
                                                    <path
                                                        d="M15.55 13c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.37-.66-.11-1.48-.87-1.48H5.21l-.94-2H1v2h2l3.6 7.59-1.35 2.44C4.52 15.37 5.48 17 7 17h12v-2H7l1.1-2h7.45zM6.16 6h12.15l-2.76 5H8.53L6.16 6zM7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z" />
                                                </svg>
                                                <span class="badge bg-secondary text-black position-absolute" id="jumlahkeranjang2">@if(auth()->user()->profilAnggota) {{ App\Models\Keranjang::where('profil_anggota_id', auth()->user()->profilAnggota->id)->count()}} @else 0 @endif</span>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main Header End -->

    </header>
    <!-- Header End -->
