@extends('layouts.frontend')
@section('title','Detail Anggota')
@section('frontend.scriptatas')
<style>
    .dataTables_empty {
        color: red;
        font-size: 16px;
        font-weight: bold;
    }
</style>
@endsection
@section('frontend.content')
        <!-- Content -->
        <div class="page-content bg-white">
            <!-- contact area -->
            <div class="content-block">
                <!-- Browse Jobs -->
                <section class="content-inner bg-white">
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-3 col-lg-4 m-b30">
                                <div class="sticky-top">
                                    <div class="shop-account">
                                        <div class="account-detail text-center">
                                            <div class="my-image">
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#modalCenterLarge" onclick="show_ubah_foto('Ubah Foto', {{$user->id}})">
                                                    {{-- <img alt="" src="{{ asset('/') }}web_assets/images/profile3.jpg"> --}}
                                                    <img src="{{ $user->foto ? asset('storage/' . $user->foto->file_path) : Storage::url('user/default.png') }}" alt=""  style="height:145px; object-fit: cover;">
                                                </a>
                                            </div>
                                            <div class="account-title">
                                                <div class="">
                                                    <h4 class="m-b5"><a href="javascript:void(0);">{{$user->username}}</a></h4>
                                                    <p class="m-b0"><a href="javascript:void(0);">Anggota Perpustakaan</a></p>
                                                    <p class="m-b0 fw-bold"><a href="javascript:void(0);">{{ $user->profilAnggota ? ($user->profilAnggota->is_verified ? 'Terverifikasi' : 'Belum diverifikasi') : ''}}</a></p>
                                                </div>
                                            </div>
                                        </div>
                                        <ul>
                                            <li>
                                                <a data-target="profil" class="tab_detail_anggota_rellarphp"><i class="far fa-user" aria-hidden="true"></i>
                                                <span>Profil</span></a>
                                            </li>
                                            <li>
                                                <a data-target="favorit" class="tab_detail_anggota_rellarphp"><i class="far fa-heart" aria-hidden="true"></i>
                                                <span>Favorit</span></a>
                                            </li>
                                            <li>
                                                <a data-target="keranjang" class="tab_detail_anggota_rellarphp"><i class="flaticon-shopping-cart-1"></i>
                                                <span>Keranjang</span></a>
                                            </li>
                                            <li>
                                                <a data-target="riwayatpeminjaman" class="tab_detail_anggota_rellarphp"><i class="fa fa-history" aria-hidden="true"></i>
                                                <span>Riwayat Peminjaman</span></a>
                                            </li>
                                            <li>
                                                <a data-target="gantipassword" class="tab_detail_anggota_rellarphp"><i class="fa fa-refresh"></i>
                                                <span>Ganti Password</span></a>


                                            </li>
                                            {{-- <li>
                                                <a data-bs-toggle="modal" data-bs-target="#modalLogin" onclick="show_logout()">
                                                    <i class="fa fa-sign-out-alt" aria-hidden="true"></i>
                                                    <span class="text-black">Keluar</span>
                                                </a>
                                            </li> --}}
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-9 col-lg-8 m-b30">
                                <div class="shop-bx shop-profile">
                                    <div id="content_detail_anggota_rellarphp"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Browse Jobs END -->
            </div>
        </div>
        <!-- Content END-->
@endsection
@section('frontend.scriptbawah')
    <script>
        $(document).ready(function() {
            $("[data-target='profil']").addClass('active');
            var act = "{{ route('detailanggota.index', ['param' => ':param']) }}";
                    act = act.replace(':param', 'profil');
            $.ajax({
                type: 'GET',
                url: act,
                success: function (data) {
                    $("#content_detail_anggota_rellarphp").html(data);
                }
            })
            var storedValue = sessionStorage.getItem('directDetailAnggota');
            // alert(storedValue);
            if(storedValue == 'favorit'){
                var favoritTab = $("[data-target='favorit']");
                favoritTab.click();
                sessionStorage.removeItem('directDetailAnggota');
            }
            if(storedValue == 'keranjang'){
                var favoritTab = $("[data-target='keranjang']");
                favoritTab.click();
                sessionStorage.removeItem('directDetailAnggota');
            }
            if(storedValue == 'riwayatpeminjaman'){
                var favoritTab = $("[data-target='riwayatpeminjaman']");
                favoritTab.click();
                sessionStorage.removeItem('directDetailAnggota');
            }
            let table = new DataTable('#table_favorit');
        });

                //OPEN MODAL EDIT FOTO
                function show_ubah_foto(judul, param){
                $("#modalCenterLargeLabel").html(judul)
                $("#modalCenterLargeContent").html(`<div class="d-flex justify-content-center">
                                                <div class="spinner-border text-primary m-1" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </div>`);
                var act = "{{ route('profilAnggota.modal.editfoto', ['param' => ':param']) }}";
                    act = act.replace(':param', param);
                $.ajax({
                    url: act,
                    success: function(data){
                        $("#modalCenterLargeContent").html(data);
                    },
                    error: function(xhr, status, error) {
                        $("#modalCenterLargeContent").html(`<div class="modal-body d-flex justify-content-center align-items-center">
                                                                <img src="{{asset('admin_assets/images/error-img.png')}}" width="80%"class="text-center">
                                                            </div>`
                                                        );
                    }
                });
            }
        //CLOSE MODAL EDIT FOTO

        //OPEN PROSES TAB MENU
            $('.tab_detail_anggota_rellarphp').on('click', function(e){
                var act = "{{ route('detailanggota.index', ['param' => ':param']) }}";
                    act = act.replace(':param', $(this).data('target'));
                var target = $(this);
                $("#content_detail_anggota_rellarphp").html(`
                                                            <div class="d-flex justify-content-center">
                                                                <div class="spinner-border text-primary m-1" role="status">
                                                                    <span class="sr-only">Loading...</span>
                                                                </div>
                                                            </div>
                                                        `);
                $('.tab_detail_anggota_rellarphp').removeClass('active');
                $.ajax({
                    type: 'GET',
                    url: act,
                    success: function (data) {
                        target.addClass('active');
                        $("#content_detail_anggota_rellarphp").html(data);
                    }
                })
            });
        //CLOSED PROSES TAB MENU


        //


        function checkall(){
            if ($("#checkAll").is(':checked')){
                $('input[type="checkbox"]').each(function (){
                $(this).prop("checked", true);
            });
            }else{
                $('input[type="checkbox"]').each(function (){
                        $(this).prop("checked", false);
                });
            }
        }

        $('#form_ganti_password_anggota_rellarphp').on('submit', function(event) {
                event.preventDefault();
                idata = new FormData($('#form_ganti_password_anggota_rellarphp')[0]);
                $.ajax({
                    type: 'POST',
                    url: "{{ route('detailanggota.gantiPassword')}}",
                    data: idata,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        swal.fire({
                            icon: data.status,
                            title: data.title,
                            text: data.message,
                        }).then(function () {
                            window.location.reload();
                        });
                    },error: function(xhr, status, error) {
                        var response = xhr.responseJSON;
                        if(response.messageValidate['password_lama']){
                            $('.validation-password_lama').text(response.messageValidate['password_lama'][0]);
                        }
                        else{
                            $('.validation-password_lama').text('');
                        }
                        if(response.messageValidate['password_baru']){
                            $('.validation-password_baru').text(response.messageValidate['password_baru'][0]);
                        }
                        else{
                            $('.validation-password_baru').text('');
                        }
                        if(response.messageValidate['konfirmasi_password_baru']){
                            $('.validation-konfirmasi_password_baru').text(response.messageValidate['konfirmasi_password_baru'][0]);
                        }
                        else{
                            $('.validation-konfirmasi_password_baru').text('');
                        }
                        Swal.fire({
                            icon: response.status,
                            title: response.title,
                            text: response.message
                        });
                    }
                })
            });
        // CLOSE GANTI PASSWORD
    </script>
@endsection
