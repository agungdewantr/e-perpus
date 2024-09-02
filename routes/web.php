<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\BukuTelahDibacaController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\ProfilBackEndController;
use App\Http\Controllers\CetakKartuKeanggotaanController;
use App\Http\Controllers\VerifikasiAnggotaController;
use App\Http\Controllers\DaftarKeanggotaanController;
use App\Http\Controllers\KatalogBukuController;
use App\Http\Controllers\PetugasAdminController;
use App\Http\Controllers\KunjunganPerpustakaanController;
use App\Http\Controllers\ManajemenBukuController;
use App\Http\Controllers\RakController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PenulisController;
use App\Http\Controllers\PengadaanController;
use App\Http\Controllers\PenerbitController;
use App\Http\Controllers\ProsedurController;
use App\Http\Controllers\DaftarPeminjamanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\SubKategoriController;
use App\Http\Controllers\PengambilanBukuController;
use App\Http\Controllers\PerpanjanganBukuController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/url-reset-sequence', [App\Http\Controllers\HomeController::class, 'resetsequence'])->name('resetsequence');


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('beranda');
Route::get('/get-notif/{param}', [App\Http\Controllers\NotifkasiController::class, 'getnotif'])->name('get.notif');
Route::get('/cek-notif', [App\Http\Controllers\NotifkasiController::class, 'notif'])->name('notif');
Route::get('/read-notif/{param}', [App\Http\Controllers\NotifkasiController::class, 'readnotif'])->name('readnotif');

// front end

// catalog
Route::get('/katalog-buku/{param?}', [App\Http\Controllers\KatalogBukuController::class, 'index'])->name('katalog-buku');
Route::post('/katalog-buku/filter', [App\Http\Controllers\KatalogBukuController::class, 'filter'])->name('filter.katalog-buku');
Route::get('/katalog-buku/overview/{slug}', [App\Http\Controllers\KatalogBukuController::class, 'show'])->name('katalog-buku.overview');


// berita
Route::get('/berita-perpustakaan/{param?}', [App\Http\Controllers\BeritaController::class, 'index'])->name('berita-perpustakaan');
Route::post('/berita-perpustakaan//filter', [App\Http\Controllers\BeritaController::class, 'filter'])->name('filter.berita-perpustakaan');
// Route::get('/get-berita-perpustakaan', [App\Http\Controllers\BeritaController::class, 'getDataBerita'])->name('get-berita-perpustakaan');
Route::get('/berita-perpustakaan/overview/{slug}', [App\Http\Controllers\BeritaController::class, 'show'])->name('berita-perpustakaan.overview');

Route::get('/acara-perpustakaan/{param?}', [App\Http\Controllers\AcaraController::class, 'index'])->name('acara-perpustakaan');
Route::post('/acara-perpustakaan//filter', [App\Http\Controllers\AcaraController::class, 'filter'])->name('filter.acara-perpustakaan');
Route::get('/tentang-kami', [App\Http\Controllers\HomeController::class, 'tentang_kami'])->name('tentang-kami');
// front end close

Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'hal_login'])->name('login');
Route::post('/proses-login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('proses.login');
Route::get('/register', [App\Http\Controllers\Auth\LoginController::class, 'hal_register'])->name('register');
Route::post('/proses-register', [App\Http\Controllers\Auth\LoginController::class, 'register'])->name('proses.register');

Route::group(["middleware" => ['auth', 'checkuserrole:admin,superadmin,petugas']], function () {
    //OPEN GET DATA
    Route::get('/get-kategori', [DashboardController::class, 'getdatakategoris'])->name('getdata.kategoris');
    Route::get('/get-sub-kategori/{param}', [DashboardController::class, 'getdatasubkategoris'])->name('getdata.subkategoris');
    Route::get('/get-penerbit', [DashboardController::class, 'getdatapenerbit'])->name('getdata.penerbit');
    Route::get('/get-penulis', [DashboardController::class, 'getdatapenulis'])->name('getdata.penulis');
    Route::get('/get-raks', [DashboardController::class, 'getdataraks'])->name('getdata.raks');
    Route::get('/get-pengadaans', [DashboardController::class, 'getdatapengadaans'])->name('getdata.pengadaans');
    //CLOSE GET DATA
    //DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    //GET DATA LINECHART
    Route::get('/dashboard-get-line-chart', [DashboardController::class, 'getlinechart'])->name('dashboard.get.linechart');
    //GET DATA PIECHART
    Route::get('/dashboard-get-pie-chart', [DashboardController::class, 'getpiechart'])->name('dashboard.get.piechart');
    //GET DATA BARCHART
    Route::get('/dashboard-get-bar-chart', [DashboardController::class, 'getbarchart'])->name('dashboard.get.barchart');
    //OPEN USER PROFIL
    Route::get('/profil/{param}', [ProfilBackEndController::class, 'index'])->name('profilParam.index');
    //UBAH PASSWORD
    Route::get('/modal/edit-password/{param}', [ProfilBackEndController::class, 'editpassword'])->name('profilParam.modal.editpassword');
    Route::post('/update-password', [ProfilBackEndController::class, 'updatepassword'])->name('profilParam.updatepassword');
    //UBAH FOTO
    Route::get('/modal/edit-foto/{param}', [ProfilBackEndController::class, 'editfoto'])->name('profilParam.modal.editfoto');
    Route::post('/update-foto', [ProfilBackEndController::class, 'updatefoto'])->name('profilParam.updatefoto');
    //UBAH PROFIL
    Route::post('/update-profil', [ProfilBackEndController::class, 'updateprofil'])->name('profilParam.updateprofil');
    //CLOSE USER PROFIL
    //OPEN MANAGEMENTS
    //OPEN USER
    Route::get('/user', [UserController::class, 'index'])->name('user.index')->middleware('checkmenupermission');
    //CREATE
    Route::get('/modal/create-user', [UserController::class, 'create'])->name('user.modal.create');
    Route::post('/store-user', [UserController::class, 'store'])->name('user.store');
    //EDIT
    Route::get('/modal/edit-user/{param}', [UserController::class, 'edit'])->name('user.modal.edit');
    Route::post('/update-user', [UserController::class, 'update'])->name('user.update');
    //DELETE
    Route::delete('/delete-user/{param}',  [UserController::class, 'delete'])->name('user.delete');
    //CLOSE USER
    //OPEN ROLE
    Route::get('/role', [RoleController::class, 'index'])->name('role.index')->middleware('checkmenupermission');
    //CREATE
    Route::get('/modal/create-role', [RoleController::class, 'create'])->name('role.modal.create');
    Route::post('/store-role', [RoleController::class, 'store'])->name('role.store');
    //EDIT
    Route::get('/modal/edit-role/{param}', [RoleController::class, 'edit'])->name('role.modal.edit');
    Route::post('/update-role', [RoleController::class, 'update'])->name('role.update');
    //DELETE
    Route::delete('/delete-role/{param}',  [RoleController::class, 'delete'])->name('role.delete');
    //CLOSE ROLE
    //OPEN MENU
    Route::get('/menu', [MenuController::class, 'index'])->name('menu.index')->middleware('checkmenupermission');
    //CREATE
    Route::get('/modal/create-menu', [MenuController::class, 'create'])->name('menu.modal.create');
    Route::post('/store-menu', [MenuController::class, 'store'])->name('menu.store');
    //EDIT
    Route::get('/modal/edit-menu/{param}', [MenuController::class, 'edit'])->name('menu.modal.edit');
    Route::post('/update-menu', [MenuController::class, 'update'])->name('menu.update');
    //DELETE
    Route::delete('/delete-menu/{param}',  [MenuController::class, 'delete'])->name('menu.delete');
    //CLOSE MENU
    //OPEN ACTIVTY LOG
    Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activitylog.index')->middleware('checkmenupermission');
    //DETAIL
    Route::get('/modal/edit-activity-log/{param}', [ActivityLogController::class, 'detail'])->name('activitylog.modal.detail');
    //CLOSE ACTIVTY LOG
    //OPEN PERMISSIONS
    Route::get('/permission', [PermissionController::class, 'index'])->name('permission.index')->middleware('checkmenupermission');
    //OPEN DETAIL
    Route::get('/modal/detail-permission/{param}', [PermissionController::class, 'detail'])->name('permission.modal.detail');
    Route::post('/update-permission', [PermissionController::class, 'update'])->name('permission.update');
    //CLOSE PERMISSIONS
    //CLOSE MANAGEMENTS
    //OPEN MASTER DATA
    //OPEN PETUGAS ADMIN
    Route::get('/petugas-admin', [PetugasAdminController::class, 'index'])->name('petugasadmin.index')->middleware('checkmenupermission');
    //CREATE
    Route::get('/modal/create-petugas-admin', [PetugasAdminController::class, 'create'])->name('petugasadmin.modal.create');
    Route::post('/store-petugas-admin', [PetugasAdminController::class, 'store'])->name('petugasadmin.store');
    //EDIT
    Route::get('/modal/edit-petugas-admin/{param}', [PetugasAdminController::class, 'edit'])->name('petugasadmin.modal.edit');
    Route::post('/update-petugas-admin', [PetugasAdminController::class, 'update'])->name('petugasadmin.update');
    //DELETE
    Route::delete('/delete-petugas-admin/{param}',  [PetugasAdminController::class, 'delete'])->name('petugasadmin.delete');
    //CLOSE PETUGAS ADMIN
    //OPEN RAK
    Route::get('/rak', [RakController::class, 'index'])->name('rak.index')->middleware('checkmenupermission');
    //CREATE
    Route::get('/modal/create-rak', [RakController::class, 'create'])->name('rak.modal.create');
    Route::post('/store-rak', [RakController::class, 'store'])->name('rak.store');
    //EDIT
    Route::get('/modal/edit-rak/{param}', [RakController::class, 'edit'])->name('rak.modal.edit');
    Route::post('/update-rak', [RakController::class, 'update'])->name('rak.update');
    //DELETE
    Route::delete('/delete-rak/{param}',  [RakController::class, 'delete'])->name('rak.delete');
    //CLOSE RAK
    //OPEN KATEGORI
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index')->middleware('checkmenupermission');
    //CREATE
    Route::get('/modal/create-kategori', [KategoriController::class, 'create'])->name('kategori.modal.create');
    Route::post('/store-kategori', [KategoriController::class, 'store'])->name('kategori.store');
    //EDIT
    Route::get('/modal/edit-kategori/{param}', [KategoriController::class, 'edit'])->name('kategori.modal.edit');
    Route::post('/update-kategori', [KategoriController::class, 'update'])->name('kategori.update');
    //DELETE
    Route::delete('/delete-kategori/{param}',  [KategoriController::class, 'delete'])->name('kategori.delete');
    //CLOSE KATEGORI
    //OPEN SUB KATEGORI
    Route::get('/sub-kategori', [SubKategoriController::class, 'index'])->name('subkategori.index')->middleware('checkmenupermission');
    //Datatable
    Route::get('/sub-kategori/datatable', [SubKategoriController::class, 'datatable'])->name('subkategori.datatable');
    //CREATE
    Route::get('/modal/create-sub-kategori', [SubKategoriController::class, 'create'])->name('subkategori.modal.create');
    Route::post('/store-sub-kategori', [SubKategoriController::class, 'store'])->name('subkategori.store');
    //EDIT
    Route::get('/modal/edit-sub-kategori/{param}', [SubKategoriController::class, 'edit'])->name('subkategori.modal.edit');
    Route::post('/update-sub-kategori', [SubKategoriController::class, 'update'])->name('subkategori.update');
    //DELETE
    Route::delete('/delete-sub-kategori/{param}',  [SubKategoriController::class, 'delete'])->name('subkategori.delete');
    //CLOSE SUB KATEGORI
    //OPEN PENULIS
    Route::get('/penulis', [PenulisController::class, 'index'])->name('penulis.index')->middleware('checkmenupermission');
    //CREATE
    Route::get('/modal/create-penulis', [PenulisController::class, 'create'])->name('penulis.modal.create');
    Route::post('/store-penulis', [PenulisController::class, 'store'])->name('penulis.store');
    //EDIT
    Route::get('/modal/edit-penulis/{param}', [PenulisController::class, 'edit'])->name('penulis.modal.edit');
    Route::post('/update-penulis', [PenulisController::class, 'update'])->name('penulis.update');
    //DELETE
    Route::delete('/delete-penulis/{param}',  [PenulisController::class, 'delete'])->name('penulis.delete');
    //CLOSE PENULIS
    //OPEN PENGADAAN
    Route::get('/pengadaan', [PengadaanController::class, 'index'])->name('pengadaan.index')->middleware('checkmenupermission');
    //CREATE
    Route::get('/modal/create-pengadaan', [PengadaanController::class, 'create'])->name('pengadaan.modal.create');
    Route::post('/store-pengadaan', [PengadaanController::class, 'store'])->name('pengadaan.store');
    //EDIT
    Route::get('/modal/edit-pengadaan/{param}', [PengadaanController::class, 'edit'])->name('pengadaan.modal.edit');
    Route::post('/update-pengadaan', [PengadaanController::class, 'update'])->name('pengadaan.update');
    //DELETE
    Route::delete('/delete-pengadaan/{param}',  [PengadaanController::class, 'delete'])->name('pengadaan.delete');
    //CLOSE PENGADAAN
    //OPEN PENERBIT
    Route::get('/penerbit', [PenerbitController::class, 'index'])->name('penerbit.index')->middleware('checkmenupermission');
    //CREATE
    Route::get('/modal/create-penerbit', [PenerbitController::class, 'create'])->name('penerbit.modal.create');
    Route::post('/store-penerbit', [PenerbitController::class, 'store'])->name('penerbit.store');
    //EDIT
    Route::get('/modal/edit-penerbit/{param}', [PenerbitController::class, 'edit'])->name('penerbit.modal.edit');
    Route::post('/update-penerbit', [PenerbitController::class, 'update'])->name('penerbit.update');
    //DELETE
    Route::delete('/delete-penerbit/{param}',  [PenerbitController::class, 'delete'])->name('penerbit.delete');
    //CLOSE PENERBIT
    //OPEN BERITA
    Route::prefix('berita')->controller(NewsController::class)->name('berita.')->group(function () {
        Route::get('', 'index')->name('index')->middleware('checkmenupermission');
        Route::get('create', 'create')->name('create');
        Route::post('create', 'store')->name('store');
        Route::get('{news}', 'edit')->name('edit');
        Route::put('{news}', 'update')->name('update');
    });
    Route::prefix('acara')->controller(AgendaController::class)->name('acara.')->group(function () {
        Route::get('', 'index')->name('index')->middleware('checkmenupermission');
        Route::get('create', 'create')->name('create');
        Route::post('create', 'store')->name('store');
        Route::get('{agenda}', 'edit')->name('edit');
        Route::put('{agenda}', 'update')->name('update');
    });
    Route::prefix('prosedur')->controller(ProsedurController::class)->name('prosedur.')->group(function () {
        Route::get('', 'index')->name('index')->middleware('checkmenupermission');
        Route::get('create', 'create')->name('create');
        Route::post('create', 'store')->name('store');
        Route::get('{prosedur}', 'edit')->name('edit');
        Route::put('{prosedur}', 'update')->name('update');
    });
    Route::prefix('laporan')->controller(LaporanController::class)->name('laporan.')->group(function () {
        Route::get('', 'index')->name('index')->middleware('checkmenupermission');
        Route::post('export', 'export')->name('export');
    });


    //CLOSE BERITA
    //CLOSE MASTER DATA
    //OPEN OTHER
    //OPEN KEANGGOTAAN
    //OPEN DAFTAR KEANGGOTAAN
    Route::get('/keanggotaan', [DaftarKeanggotaanController::class, 'index'])->name('keanggotaan.index')->middleware('checkmenupermission');
    //Datatable
    Route::get('/keanggotaan/datatable', [DaftarKeanggotaanController::class, 'datatable'])->name('keanggotaan.datatable');
    //CREATE
    Route::get('/modal/create-keanggotaan', [DaftarKeanggotaanController::class, 'create'])->name('keanggotaan.modal.create');
    Route::post('/store-keanggotaan', [DaftarKeanggotaanController::class, 'store'])->name('keanggotaan.store');
    //EDIT
    Route::get('/modal/edit-keanggotaan/{param}', [DaftarKeanggotaanController::class, 'edit'])->name('keanggotaan.modal.edit');
    Route::post('/update-keanggotaan', [DaftarKeanggotaanController::class, 'update'])->name('keanggotaan.update');
    //CLOSE DAFTAR KEANGGOTAAN
    //OPEN CETAK KARTU KEANGGOTAAN
    Route::get('/cetak-kartu-keanggotaan', [CetakKartuKeanggotaanController::class, 'index'])->name('cetakkartukeanggotaan.index')->middleware('checkmenupermission');
    //Datatable
    Route::get('/cetak-kartu-keanggotaan/datatable', [CetakKartuKeanggotaanController::class, 'datatable'])->name('cetakkartukeanggotaan.datatable');
    //Detail (Gambar Kartu)
    Route::get('/modal/cetak-kartu-keanggotaan/{param}', [CetakKartuKeanggotaanController::class, 'detail'])->name('cetakkartukeanggotaan.modal.detail');
    //PDF
    Route::get('/modal/cetak-kartu-keanggotaan/{profilAnggota}/pdf', [CetakKartuKeanggotaanController::class, 'download'])->name('cetakkartukeanggotaan.modal.pdf');
    //CLOSE CETAK KARTU KEANGGOTAAN
    //OPEN VERIFIKASI ANGGOTA
    Route::get('/verifikasi-anggota', [VerifikasiAnggotaController::class, 'index'])->name('verifikasianggota.index')->middleware('checkmenupermission');
    //Datatable
    Route::get('/verifikasi-anggota/datatable', [VerifikasiAnggotaController::class, 'datatable'])->name('verifikasianggota.datatable');
    //PROSES SETUJU VERIFIKASI
    Route::get('/verifikasi-anggota/setuju-verifikasi/{param}', [VerifikasiAnggotaController::class, 'setujuverifikasi'])->name('verifikasianggota.setujuverifikasi');
    //PROSES TOLAK VERIFIKASI
    Route::get('/verifikasi-anggota/tolak-verifikasi/{param}', [VerifikasiAnggotaController::class, 'tolakverifikasi'])->name('verifikasianggota.tolakverifikasi');
    //CLOSE VERFIKASI ANGGOTA
    //CLOSE KEANGGOTAAN
    //OPEN KUNJUNGAN PERPUSTAKAAN
    Route::get('/kunjungan-perpustakaan', [KunjunganPerpustakaanController::class, 'index'])->name('kunjunganperpustakaan.index')->middleware('checkmenupermission');
    //Datatable
    Route::get('/kunjungan-perpustakaan/datatable', [KunjunganPerpustakaanController::class, 'datatable'])->name('kunjunganperpustakaan.datatable');
    //CREATE
    Route::get('/modal/create-kunjungan-perpustakaan', [KunjunganPerpustakaanController::class, 'create'])->name('kunjunganperpustakaan.modal.create');
    Route::post('/store-kunjungan-perpustakaan', [KunjunganPerpustakaanController::class, 'store'])->name('kunjunganperpustakaan.store');
    Route::get('/get-jenis-kelamin-kunjungan-perpustakaan/{param}', [KunjunganPerpustakaanController::class, 'getjeniskelamin'])->name('kunjunganperpustakaan.get.jeniskelamin');
    //EDIT
    Route::get('/modal/edit-kunjungan-perpustakaan/{param}', [KunjunganPerpustakaanController::class, 'edit'])->name('kunjunganperpustakaan.modal.edit');
    Route::post('/update-kunjungan-perpustakaan', [KunjunganPerpustakaanController::class, 'update'])->name('kunjunganperpustakaan.update');
    //IMPORT
    Route::get('/modal/import-kunjungan-perpustakaan', [KunjunganPerpustakaanController::class, 'import'])->name('kunjunganperpustakaan.modal.import');
    Route::get('/download-template-kunjungan-perpustakaan', [KunjunganPerpustakaanController::class, 'downloadtemplate'])->name('kunjunganperpustakaan.downloadtemplate');
    Route::post('/import-kunjungan-perpustakaan', [KunjunganPerpustakaanController::class, 'importexcel'])->name('kunjunganperpustakaan.importexcel');

    //CLOSE KUNJUNGAN PERPUSTAKAAN
    //OPEN MANAGEMENT BUKU
    Route::get('/manajemen-buku', [ManajemenBukuController::class, 'index'])->name('manajemenbuku.index')->middleware('checkmenupermission');
    //Datatable
    Route::get('/manajemen-buku/datatable', [ManajemenBukuController::class, 'datatable'])->name('manajemenbuku.datatable');
    //CREATE
    Route::get('/modal/create-manajemen-buku', [ManajemenBukuController::class, 'create'])->name('manajemenbuku.modal.create');
    Route::post('/store-manajemen-buku', [ManajemenBukuController::class, 'store'])->name('manajemenbuku.store');
    //EDIT
    Route::get('/modal/edit-manajemen-buku/{param}', [ManajemenBukuController::class, 'edit'])->name('manajemenbuku.modal.edit');
    Route::post('/update-manajemen-buku', [ManajemenBukuController::class, 'update'])->name('manajemenbuku.update');
    Route::get('/manajemen-buku/{id}/pdf', [ManajemenBukuController::class, 'show_buku'])->name('manajemenbuku.show.pdf');
    //CLOSE MANAGEMENT BUKU
    //OPEN PEMINJAMAN DAN PENGAMBILAN
    //OPEN DAFTAR PEMMINJAMAN
    Route::get('/daftar-peminjaman', [DaftarPeminjamanController::class, 'index'])->name('daftarpeminjaman.index')->middleware('checkmenupermission');
    //Datatable
    Route::get('/daftar-peminjaman/datatable', [DaftarPeminjamanController::class, 'datatable'])->name('daftarpeminjaman.datatable');
    //CREATE
    Route::get('/modal/create-daftar-peminjaman', [DaftarPeminjamanController::class, 'create'])->name('daftarpeminjaman.modal.create');
    Route::post('/store-daftar-peminjaman', [DaftarPeminjamanController::class, 'store'])->name('daftarpeminjaman.store');
    //EDIT
    Route::get('/modal/edit-daftar-peminjaman/{param}', [DaftarPeminjamanController::class, 'edit'])->name('daftarpeminjaman.modal.edit');
    Route::post('/update-daftar-peminjaman', [DaftarPeminjamanController::class, 'update'])->name('daftarpeminjaman.update');
    //DETAIL
    Route::get('/modal/detail-daftar-peminjaman/{param}', [DaftarPeminjamanController::class, 'detail'])->name('daftarpeminjaman.modal.detail');
    //CLOSE DAFTAR PEMINJAMAN
    //OPEN DAFTAR PENGAMBILAN BUKU
    Route::get('/pengambilan-buku', [PengambilanBukuController::class, 'index'])->name('pengambilanbuku.index')->middleware('checkmenupermission');
    //Datatable
    Route::get('/pengambilan-buku/datatable', [PengambilanBukuController::class, 'datatable'])->name('pengambilanbuku.datatable');
    //PROSES SETUJU
    Route::get('/pengambilan-buku/setuju/{param}', [PengambilanBukuController::class, 'setuju'])->name('pengambilanbuku.setuju');
    //CLOSE DAFTAR PENGAMBILAN BUKU
    //OPEN DAFTAR PERPANJANGAN BUKU
    Route::get('/perpanjangan-buku', [PerpanjanganBukuController::class, 'index'])->name('perpanjanganbuku.index')->middleware('checkmenupermission');
    //Datatable
    Route::get('/perpanjangan-buku/datatable', [PerpanjanganBukuController::class, 'datatable'])->name('perpanjanganbuku.datatable');
    //PROSES SETUJU
    Route::get('/perpanjangan-buku/setuju/{param}', [PerpanjanganBukuController::class, 'setuju'])->name('perpanjanganbuku.setuju');
    //PROSES TOLAK
    Route::get('/perpanjangan-buku/tolak/{param}', [PerpanjanganBukuController::class, 'tolak'])->name('perpanjanganbuku.tolak');
    //CLOSE DAFTAR PERPANJANGAN BUKU
    //CLOSE PEMINJAMAN DAN PENGAMBILAN
    //CLOSE OTHER

    // OPEN BUKU TELAH DIBACA
    Route::get('/buku-telah-dibaca', [BukuTelahDibacaController::class, 'index'])->name('bukutelahdibaca.index')->middleware('checkmenupermission');

     //Datatable
     Route::get('/buku-telah-dibaca/datatable', [BukuTelahDibacaController::class, 'datatable'])->name('bukutelahdibaca.datatable');
     //CREATE
     Route::get('/modal/create-buku-telah-dibaca', [BukuTelahDibacaController::class, 'create'])->name('bukutelahdibaca.modal.create');
     Route::post('/store-buku-telah-dibaca', [BukuTelahDibacaController::class, 'store'])->name('bukutelahdibaca.store');
     Route::post('/cekjumlahbuku',[BukuTelahDibacaController::class,'cekJumlahBuku'])->name('cekjumlahbuku');
    //EDIT
     Route::get('/modal/edit-buku-telah-dibaca/{param}', [BukuTelahDibacaController::class, 'edit'])->name('bukutelahdibaca.modal.edit');
     Route::post('/update-buku-telah-dibaca', [BukuTelahDibacaController::class, 'update'])->name('bukutelahdibaca.update');
     //IMPORT
     Route::get('/modal/import-buku-telah-dibaca', [BukuTelahDibacaController::class, 'import'])->name('bukutelahdibaca.modal.import');
     Route::get('/download-template-buku-telah-dibaca', [BukuTelahDibacaController::class, 'downloadtemplate'])->name('bukutelahdibaca.downloadtemplate');
     Route::post('/import-buku-telah-dibaca', [BukuTelahDibacaController::class, 'importexcel'])->name('bukutelahdibaca.importexcel');

    // CLOSE BUKU TELAH DIBACA
});

// Route::group(["middleware" => ['auth', 'checkuserrole:anggota']], function () {
    Route::get('/detail-anggota/{param?}', [UserController::class, 'detailanggota'])->name('detailanggota.index');
    //PROFIL
    Route::POST('/detail-anggota/profil', [UserController::class, 'profil'])->name('detailanggota.profil');
    Route::POST('/detail-anggota/ganti-password', [UserController::class, 'gantiPassword'])->name('detailanggota.gantiPassword');
    Route::get('/modal/edit-foto-anggota/{param}', [UserController::class, 'editfoto'])->name('profilAnggota.modal.editfoto');
    Route::post('/update-foto-anggota', [UserController::class, 'updatefoto'])->name('profilAnggota.updatefoto');

    Route::post('/katalog-buku/pinjam-buku', [App\Http\Controllers\KatalogBukuController::class, 'pinjamBuku'])->name('katalog-buku.pinjam');
    Route::post('/katalog-buku/ajukan-perpanjangan', [App\Http\Controllers\KatalogBukuController::class, 'ajukanPerpanjangan'])->name('katalog-buku.ajukan-perpanjangan');
    Route::get('/cetakbuktipeminjaman/{param}', [App\Http\Controllers\KatalogBukuController::class, 'downloads'])->name('katalog-buku.cetakbuktipeminjamanpdf');
    Route::get('/baca-buku/{id}/pdf', [KatalogBukuController::class, 'show_buku'])->name('katalog-buku.show.pdf');
    Route::post('/ulasan', [App\Http\Controllers\KatalogBukuController::class, 'ulasan'])->name('ulasan');
    Route::post('/favorit', [App\Http\Controllers\FavoritController::class, 'store'])->name('favorit.store');
    Route::post('/keranjang', [App\Http\Controllers\KeranjangController::class, 'store'])->name('keranjang.store');
// });
