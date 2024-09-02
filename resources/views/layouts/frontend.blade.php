<!DOCTYPE html>
<html lang="en">

<head>

    @include('partials.frontend.titlemeta')

    @include('partials.frontend.headcss')

    @yield('frontend.scriptatas')
    <style>
        .button-primary:hover {
            background-color: #D4C31B !important;
            color: #ffffff !important;
            border: 1px solid #D4C31B;
        }

        .btn-biru {
            background: #3C5E90;
            color: #fff;
        }

        .text-biru{
            color: #3C5E90;
        }


        @media(min-width:1200px){
            .dz-social-icon{
                margin-left: 90px;
            }
        }

        @media(max-width:991px){
            .titleKontak{
                color: #3C5E90;
                margin-top: 20px;
                text-align: center;
            }

            .footer-title{
                margin-left: 30px;
            }

            .detailKontak{
                margin-left:25px;
                /* margin-right: 40px */
            }
        }

        @media(min-width:768px){
            .cart-btn2{
                display: none !important;
            }
        }

        .bg-kuning{
           background:  #ffea20;
        }

        .media-heading{
            font-size: 11pt;
        }
        .textNotif{
            font-size: 10pt;
        }
    </style>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-NG9E0LWVZJ"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-NG9E0LWVZJ');
    </script>
</head>

<body>
    <div class="page-wraper">
        <div id="loading-area" class="preloader-wrapper-1">
            <div class="preloader-inner">
                <div class="preloader-shade"></div>
                <div class="preloader-wrap"></div>
                <div class="preloader-wrap wrap2"></div>
                <div class="preloader-wrap wrap3"></div>
                <div class="preloader-wrap wrap4"></div>
                <div class="preloader-wrap wrap5"></div>
            </div>
        </div>

        @include('partials.frontend.header')

        @yield('frontend.content')
        @include('partials.modals')

        @include('partials.frontend.footer')

        <button class="scroltop" type="button"><i class="fas fa-arrow-up"></i></button>
    </div>
    <!-- JAVASCRIPT FILES ========================================= -->
    <script src="{{ asset('/') }}web_assets/js/jquery.min.js"></script><!-- JQUERY MIN JS -->
    <script src="{{ asset('/') }}web_assets/vendor/wow/wow.min.js"></script><!-- WOW JS -->
    <script src="{{ asset('/') }}web_assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script><!-- BOOTSTRAP MIN JS -->
    <script src="{{ asset('/') }}web_assets/vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script><!-- BOOTSTRAP SELECT MIN JS -->
    <script src="{{ asset('/') }}web_assets/vendor/counter/waypoints-min.js"></script><!-- WAYPOINTS JS -->
    <script src="{{ asset('/') }}web_assets/vendor/counter/counterup.min.js"></script><!-- COUNTERUP JS -->
    <script src="{{ asset('/') }}web_assets/vendor/swiper/swiper-bundle.min.js"></script><!-- SWIPER JS -->
    <script src="{{ asset('/') }}web_assets/js/dz.carousel.js"></script><!-- DZ CAROUSEL JS -->
    <script src="{{ asset('/') }}web_assets/js/dz.ajax.js"></script><!-- AJAX -->
    <script src="{{ asset('/') }}web_assets/js/custom.js?v='.time())"></script><!-- CUSTOM JS -->


    <!-- Required datatable js -->
    <script src="{{ asset('/') }}admin_assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('/') }}admin_assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('/') }}admin_assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('/') }}admin_assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
    <script src="{{ asset('/') }}admin_assets/libs/jszip/jszip.min.js"></script>
    <script src="{{ asset('/') }}admin_assets/libs/pdfmake/build/pdfmake.min.js"></script>
    <script src="{{ asset('/') }}admin_assets/libs/pdfmake/build/vfs_fonts.js"></script>
    <script src="{{ asset('/') }}admin_assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="{{ asset('/') }}admin_assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="{{ asset('/') }}admin_assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>
    <!-- Responsive examples -->
    <script src="{{ asset('/') }}admin_assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{ asset('/') }}admin_assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js">
    </script>


    {{-- BACKEND --}}
    <!-- Sweet Alerts js -->
    <script src="{{ asset('/') }}admin_assets/libs/sweetalert2/sweetalert2.min.js"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    @stack('frontend.scripts')
    @yield('frontend.scriptbawah')
    <script>
        var data = null
        @if (session()->exists('error'))
            data = {
                    error: '{{ session('error') }}'
                },
        @endif
        $(document).ready(function() {
            //NOTIF
                $('#readNotif').click(function () {
                    var act = "{{ route('readnotif', ['param' => ':param']) }}";
                        act = act.replace(':param', {{ auth()->id() }});
                    $.ajax({
                        url: act,
                        method: 'GET',
                        success: function(data) {
                            $('#totalNotifNotYetRead').html(0);
                            // $('#listnotif li').css('background', '');
                        },
                    });
                });
                // Pusher.logToConsole = true;
                var pusher = new Pusher('9dad3b12c04aa30cbde4', {
                    cluster: 'ap1',
                    forceTLS: true
                });

                var channel = pusher.subscribe('{{ auth()->id() }}-notification');
                channel.bind('notify', function(data) {
                    var act = "{{ route('get.notif', ['param' => ':param']) }}";
                        act = act.replace(':param', {{ auth()->id() }});
                    $.ajax({
                        url: act,
                        method: 'GET',
                        dataType: 'json',
                        success: function(data2) {
                            $('#listnotif').empty();
                            var totalNotYetRead = 0;
                            console.log(data2);
                            $.each(data2, function(index, item) {
                                $('#listnotif').append(`
                                                            <li class="cart-item" style="${item.is_active ? 'background: #d2f2ff;' : ''} ; padding:2px;">
                                                                <div class="media">
                                                                    <div class="media-body">
                                                                        <h6 class="">
                                                                            <a href="${item.route}" class="media-heading"
                                                                            ${item.tentang == 'Tolak Perpanjangan' || item.tentang == 'Terima Perpanjangan' || item.tentang == 'Batas Pengembalian' ?
                                                                                'onclick="subMenu(\'riwayatpeminjaman\')"' : ''
                                                                            }
                                                                            >
                                                                                ${item.tentang}
                                                                            </a>
                                                                        </h6>
                                                                        <span class="textNotif">${item.isi}</span>
                                                                    </div>
                                                                </div>
                                                            </li>
                                `);
                                if(item.is_active == true){
                                    totalNotYetRead++;
                                }
                            });
                            $('#totalNotifNotYetRead').html(totalNotYetRead);
                        },
                        error: function(error) {
                            console.error('Error loading countries:', error);
                        }
                    });
                });
            //NOTIF
            //OPEN Salah PW
            @if (session()->exists('error'))
                $("#modalLogin").modal('show')
                show_login()
            @endif
            //CLOSE Salah PW
            //OPEN PASSWORD LIHAT
            var passwordInput = $('#password');
            var togglePasswordButton = $('#togglePassword');
            togglePasswordButton.click(function() {
                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    togglePasswordButton.html('<i class="fa fa-eye" aria-hidden="true"></i>');
                } else {
                    passwordInput.attr('type', 'password');
                    togglePasswordButton.html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
                }
            });
            //CLOSE PASSWORD LIHAT
        });
        // // like toggle button
        // function toggleLike() {
        //     var heartIcon = document.querySelector('.heart-icon');
        //     heartIcon.classList.toggle('active');
        // }

        //OPEN ON CLOSE MODAL
        $("#modalLogin").on('hidden.bs.modal', function() {
            data = null
        })

        //OPEN CLICK FAVORIT
        function subMenu(param) {
            sessionStorage.setItem('directDetailAnggota', param);
        }
            //CLOSE CLICK FAVORIT
        //CLOSE ON CLOSE MODAL
        //OPEN MODAL LOGIN
        function show_login() {
            $("#modalLoginContent").html(`<div class="d-flex justify-content-center">
                                                            <div class="spinner-border text-primary m-1" role="status">
                                                                <span class="sr-only">Loading...</span>
                                                            </div>
                                                        </div>`);
            var act = "{{ route('login') }}";
            $.ajax({
                url: act,
                data: data,
                success: function(data) {
                    $("#modalLoginContent").html(data);
                },
                error: function(xhr, status, error) {
                    $("#modalLoginContent").html(`<div class="modal-body d-flex justify-content-center align-items-center">
                                                                    <img src="{{ asset('admin_assets/images/error-img.png') }}" width="80%"class="text-center">
                                                                </div>`);
                }
            });
        }
        //CLOSE MODAL LOGIN
        //OPEN MODAL REGISTER
        function show_register() {
            $("#modalLoginContent").html(`<div class="d-flex justify-content-center">
                                                            <div class="spinner-border text-primary m-1" role="status">
                                                                <span class="sr-only">Loading...</span>
                                                            </div>
                                                        </div>`);
            var act = "{{ route('register') }}";
            $.ajax({
                url: act,
                success: function(data) {
                    $("#modalLoginContent").html(data);
                },
                error: function(xhr, status, error) {
                    $("#modalLoginContent").html(`<div class="modal-body d-flex justify-content-center align-items-center">
                                                                    <img src="{{ asset('admin_assets/images/error-img.png') }}" width="80%"class="text-center">
                                                                </div>`);
                }
            });
        }
        //CLOSE MODAL REGISTER

                //OPEN MODAL LOGOUT
                function show_logout(){
                $("#modalLoginContent").html(`<div class="d-flex justify-content-center">
                                                        <div class="spinner-border text-primary m-1" role="status">
                                                            <span class="sr-only">Loading...</span>
                                                        </div>
                                                    </div>`);
                var act = "{{ route('login')}}";
                $.ajax({
                    url: act,
                    success: function(data){
                        $("#modalLoginContent").html( `
                                                        <div class="row mt-4">
                                                            <h4 class="text-center text-black mb-2">Apakah anda yakin <br> ingin keluar dari aplikasi ?</h4>
                                                            <div class="my-3 text-center">
                                                                <button class="btn btn-light mr-2" type="button" data-bs-dismiss="modal">Tidak</button>
                                                                <a class="btn btn-danger ml-2" href="{{ route('logout') }}"
                                                                    onclick="event.preventDefault();
                                                                                document.getElementById('logout-form').submit();">
                                                                    {{ __('Keluar') }}
                                                                </a>

                                                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                                    @csrf
                                                                </form>
                                                            </div>
                                                        </div>
                                                `);
                    },
                    error: function(xhr, status, error) {
                        $("#modalLoginContent").html(`<div class="modal-body d-flex justify-content-center align-items-center">
                                                                <img src="{{asset('admin_assets/images/error-img.png')}}" width="80%"class="text-center">
                                                            </div>`
                                                        );
                    }
                });
            }
        //CLOSE MODAL LOGOUT

        //OPEN MODAL BELUM DAFTAR
        function show_alert_belum_daftar() {
            $('#modalDataBelumDaftar_rellarphp').modal('show');
        }
        //CLOSE MODAL BELUM DAFTAR

        // function pinjam
        function pinjamBuku(paramBuku){
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
                                        buku_id: paramBuku,
                                        _token: "{{ csrf_token() }}"
                                    },
                                    success: function(res) {
                                        if (res.responses.some(response => response.success)) {
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
                                            Swal.fire('Warning', 'Peminjaman Buku Gagal. Buku ini mungkin sudah Anda pinjam.', 'warning');
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
            }
    </script>
</body>

</html>
