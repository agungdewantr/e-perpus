    {{-- STANDART MODAL --}}
        <div id="modalStandart" class="modal fade" tabindex="-1" aria-labelledby="modalStandartLabel" aria-hidden="true" data-bs-scroll="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body" id="modalStandartContent">
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    {{-- STANDART MODAL CENTER --}}
        <div id="modalCenterStandart" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCenterStandartLabel"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div id="modalCenterStandartContent">

                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    {{-- STANDART MODAL CENTER --}}
        <div id="modalCenterStandart2" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCenterStandartLabel2"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div id="modalCenterStandartContent2">

                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    {{-- FULL SCREEN MODAL --}}
        <div id="modalFullscreen" class="modal fade" tabindex="-1" aria-labelledby="modalFullscreenLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalFullscreenLabel"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modalFullscreenContent">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary waves-effect" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-sm btn-success waves-effect waves-light">Simpan</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

    {{-- LARGE MODAL --}}
        <div id="modalLarge" class="modal" id="modal_large">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header" style="background-color:lightskyblue;">
                        <h5 class="modal-title" id="modalLargeLabel"></h5>
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body" id="modalLargeContent">

                    </div>
                </div>
            </div>
        </div>

    {{-- CENTER MODAL --}}
        <div id="modalCenter" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCenterLabel"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div id="modalCenterContent">

                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    {{-- CENTER LARGE MODAL --}}
        <div id="modalCenterLarge" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCenterLargeLabel"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div id="modalCenterLargeContent">

                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    {{-- CENTER LARGE EXTRA MODAL --}}
        <div id="modalCenterExtraLarge" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCenterExtraLargeLabel"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div id="modalCenterExtraLargeContent">

                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    {{-- CENTER MODAL LOGIN --}}
        <div id="modalLogin" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body" id="modalLoginContent">

                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    {{-- CENTER MODAL ALERT DATA BELUM LENGKAP --}}
        <div id="modalDataBelumLengkap_rellarphp" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body" id="modalDataBelumLengkap_rellarphpContent">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="d-flex justify-content-center align-items-center">
                            <img src="{{asset('web_assets/images/lainnya/lengkapi-data.png')}}" width="80%"class="text-center" alt="lengkapi-data">
                        </div>
                        <span class="text-center">
                            <h4 class="text-black">Data Diri Belum Lengkap!</h4>
                            <p>
                                Agar dapat melakukan peminjaman buku, silahkan lengkapi data diri terlebih dahulu dengan <a href="{{route('detailanggota.index')}}">Klik Profil!</a>
                            </p>
                        </span>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

            {{-- CENTER MODAL ALERT DATA BELUM DAFTAR --}}
        <div id="modalDataBelumDaftar_rellarphp" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body" id="modalDataBelumDaftar_rellarphpContent">
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="d-flex justify-content-center align-items-center">
                            <img src="{{asset('web_assets/images/lainnya/lengkapi-data.png')}}" width="80%"class="text-center" alt="lengkapi-data">
                        </div>
                        <span class="text-center">
                            <h4 class="text-black">Anda belum terdaftar sebagai Anggota Perpustakaan!</h4>
                            <p>
                                Silahkan lakukan pendaftaran dahulu dengan klik Daftar <button data-bs-toggle="modal"  data-bs-target="#modalLogin" onclick="show_register()" class="btn btn-primary">Daftar</button>
                            </p>
                        </span>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

            {{-- CENTER MODAL ALERT DAFTAR BERHASIL --}}
            <div id="modalDataBerhasil_rellarphp" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body" id="modalDataBerhasil_rellarphpContent">
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="d-flex justify-content-center align-items-center mb-4">
                                <img src="{{asset('web_assets/images/lainnya/berhasil.png')}}" width="30%"class="text-center" alt="lengkapi-data">
                            </div>
                            <span class="text-center">
                                <h4 class="text-black mb-4">Berhasil</h4>
                                <p>
                                    Silahkan ambil buku pada Perpustakaan Kabupaten Tolitoli pada tanggal <span id="tanggal_diambil"></span> dalam kurun waktu 2 x 24 jam dengan menunjukkan bukti peminjaman kepada Petugas <button data-bs-toggle="modal"  data-bs-target="#modalUnduhBuktiPeminjaman_rellarphp" class="btn btn-sm btn-biru mt-3"><i class="fa-solid fa-download me-2"></i> Unduh Bukti Peminjaman</button>
                                </p>
                            </span>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->


            {{-- CENTER MODAL ALERT UNDUH BUKTI --}}
            <div id="modalUnduhBuktiPeminjaman_rellarphp" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Bukti Peminjaman Buku</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                        <div class="modal-body" id="modalUnduhBuktiPeminjaman_rellarphpContent">
                            <div class="card shadow p-3 mb-5 bg-body rounded">
                                <div class="card-body">
                                    <h6>Peminjaman Buku</h6>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="d-flex justify-content-between">
                                                <span>Nomor Anggota</span>
                                                <span class="fw-bold" id="nomor_anggota"></span>
                                            </div>

                                            <div class="d-flex justify-content-between">
                                                <span>Nama</span>
                                                <span class="fw-bold" id="nama_anggota"></span>
                                            </div>

                                            <div class="d-flex justify-content-between">
                                                <span>Total Buku</span>
                                                <span class="fw-bold" id="total_buku_pinjam"></span>
                                            </div>
                                            <div class="">
                                                <span>Judul Buku</span><br>
                                                <span id="list_buku_pinjam" class="fw-bold">
                                                    {{-- 1. Buku Anak Belajar Membaca dan Menulis Huruf HIJAIYAH Untuk TK
                                                    2. Buku Anak Belajar Membaca dan Menulis Huruf HIJAIYAH Untuk TK --}}
                                                </span>

                                            </div>
                                            <hr>
                                            {{-- <div class="d-flex justify-content-between">
                                                <span>Tanggal Peminjaman</span>
                                                <span class="fw-bold">17 November 2023</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-3">
                                                <span>Tanggal Batas Kembali</span>
                                                <span class="fw-bold">24 November 2023</span>
                                            </div> --}}
                                            <div class="d-flex justify-content-between mb-3">
                                                <span>Tanggal Maks Pengambilan</span>
                                                <span class="fw-bold" id="tgl_max_ambil"></span>
                                            </div>
                                            <small>*Batas waktu peminjam hanya 1 minggu</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <span class="d-flex justify-content-end">
                                <a class="btn btn-sm btn-biru mt-3" id="downloadPdfButton" href=""><i class="fa-solid fa-download me-2"></i> Download</a>
                            </span>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            {{-- CENTER MODAL ALERT PERPANJANGAN --}}
            <div id="modalPerpanjanganPeminjamanBuku_rellarphp" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">

                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <form>
                        <div class="modal-body" id="modalPerpanjanganPeminjamanBuku_rellarphpContent">
                            <h5 class="modal-title text-center mb-3" id="exampleModalLabel">Detail Perpanjangan Peminjaman Buku</h5>
                                <div class="mb-3">
                                  <label for="exampleInputEmail1" class="text-black form-label fw-bold">Judul Buku</label>
                                  <input type="text" readonly class="form-control" id="judul_buku_perpanjangan" name="judul_buku">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="text-black form-label fw-bold">Tanggal Perpanjangan Peminjaman</label>
                                    <input type="text" readonly class="form-control" id="tanggal_perpanjangan_peminjaman" name="tanggal_perpanjangan_peminjaman">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="text-black form-label fw-bold">Tanggal Pengembalian Setelah Perpanjangan</label>
                                    <input type="text" readonly class="form-control" id="tanggal_pengembalian_setelah_perpanjangan" name="tanggal_pengembalian_setelah_perpanjangan">
                                    <input type="hidden" name="id_pinjaman" id="id_pinjaman">
                                </div>
                                <div class="mb-3">
                                    <small>*Perpanjangan peminjaman buku terhitung satu minggu
                                        setelah peminjaman pertama</small>
                                </div>

                            <hr>
                            <div class=" px-2 text-center">
                                <span class="text-black fw-bold">Apakah anda yakin untuk melakukan perpanjangan peminjaman buku?</span>
                                <div class="row text-center d-flex justify-content-center">
                                    <button type="button" data-bs-dismiss="modal" class="btn btn-sm btn-light mt-3 col-6">Tidak</button>
                                    <button type="button" class="btn btn-sm btn-biru mt-3 col-6" id="btn_perpanjangan_yakin">Yakin</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            {{-- <div id="modalGantiPassword" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body" id="modalGantiPasswordContent">
                            <form class="form" id="form_ganti_password_anggota_rellarphp" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" class="form-control" name="id" id="id" value="{{encrypt($user->id)}}" readonly>
                                <div class="row m-b30">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="password_lama" class="form-label">Password Lama <span class="text-danger"> *</span></label>

                                            <div class="input-group">
                                                <input type="password" class="form-control text-black" name="password_lama" id="password_lama" placeholder="Masukkan Password Lama" tabindex="1" required value="">
                                                <button type="button" id="togglePasswordLama" class="btn btn-outline-light text-black" style="border: 1px solid #d6d6d6;">
                                                    <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                            <small class="validation-password_lama text-danger"></small>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="password_baru" class="form-label">Password Baru <span class="text-danger"> *</span></label>
                                            <div class="input-group">
                                                <input type="password" class="form-control text-black" name="password_baru" id="password_baru" placeholder="Masukkan Password Baru" tabindex="2" required value="">
                                                <button type="button" id="togglePasswordBaru" class="btn btn-outline-light text-black" style="border: 1px solid #d6d6d6;">
                                                    <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                            <small class="validation-password_baru text-danger"></small>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="konfirmasi_password_baru" class="form-label">Konfirmasi Password Baru <span class="text-danger"> *</span></label>
                                            <div class="input-group">
                                            <input type="password" class="form-control text-black" name="konfirmasi_password_baru" id="konfirmasi_password_baru" placeholder="Masukkan Konfirmasi Password Baru" tabindex="3" required value="">
                                            <button type="button" id="togglePasswordKonfirmasi" class="btn btn-outline-light text-black" style="border: 1px solid #d6d6d6;">
                                                <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                            <small class="validation-konfirmasi_password_baru text-danger"></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-danger btnhover mx-2">Batal</button>
                                    <button type="submit" class="btn btn-success btnhover">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal --> --}}



