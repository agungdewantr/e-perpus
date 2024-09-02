<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubKategori;
use App\Models\ProfilAnggota;
use App\Models\KunjunganPerpustakaan;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\Penerbit;
use App\Models\Pengadaan;
use App\Models\Penulis;
use App\Models\Rak;

class DashboardController extends Controller
{
    public function dashboard(){
        // GET PROFIL BUKU
            $buku = Buku::join('item_bukus', 'bukus.id', 'item_bukus.bukus_id')
                            ->where('bukus.jenis','Buku')
                            ->count();
            $bukuDigital = Buku::where('jenis','Buku Digital')->count();
            $bukuAudio = Buku::where('jenis','Buku Audio')->count();
            $totalBuku = $buku + $bukuDigital + $bukuAudio;
            //BULAN INI
            $bukuBulanIni = Buku::join('item_bukus', 'bukus.id', 'item_bukus.bukus_id')
                                ->where('bukus.jenis','Buku')
                                ->whereMonth('item_bukus.created_at', now()->format('m'))
                                ->count();
            $bukuDigitalBulanIni = Buku::where('jenis','Buku Digital')->whereMonth('created_at', now()->format('m'))->count();
            $bukuAudioBulanIni = Buku::where('jenis','Buku Audio')->whereMonth('created_at', now()->format('m'))->count();
            $totalBukuBulanIni = $bukuBulanIni + $bukuDigitalBulanIni + $bukuAudioBulanIni;
            //BULAN LALU
            $bukuBulanLalu = Buku::join('item_bukus', 'bukus.id', 'item_bukus.bukus_id')
                                ->where('bukus.jenis','Buku')
                                ->whereMonth('item_bukus.created_at', now()->subMonth(1)->format('m'))
                                ->count();
            $bukuDigitalBulanLalu = Buku::where('jenis','Buku Digital')->whereMonth('created_at', now()->subMonth(1)->format('m'))->count();
            $bukuAudioBulanLalu = Buku::where('jenis','Buku Audio')->whereMonth('created_at', now()->subMonth(1)->format('m'))->count();
            $totalBukuBulanLalu = $bukuBulanLalu + $bukuDigitalBulanLalu + $bukuAudioBulanLalu;
            $persenBuku = number_format(($totalBukuBulanLalu ? ($totalBukuBulanIni - $totalBukuBulanLalu) / $totalBukuBulanLalu * 100 : 0));
        // GET PROFIL ANGGOTA
            $jumlahAnggota = ProfilAnggota::where('is_verified', 1)->count();
            $profilAnggotaBulanLalu = ProfilAnggota::where('is_verified', 1)->whereMonth('tanggal_verified', now()->subMonth(1)->format('m'))->count();
            $profilAnggotaBulanIni = ProfilAnggota::where('is_verified', 1)->whereMonth('tanggal_verified', now()->format('m'))->count();
            $persenProfilAnggota = number_format(($profilAnggotaBulanLalu ? ($profilAnggotaBulanIni - $profilAnggotaBulanLalu) / $profilAnggotaBulanLalu * 100 : 0));
        // GET KUNJUNGAN
            $jumlahPerpustakaan = KunjunganPerpustakaan::count();
            $kunjunganPerpustakaanBulanLalu = KunjunganPerpustakaan::whereMonth('created_at', now()->subMonth(1)->format('m'))->count();
            $kunjunganPerpustakaanBulanIni = KunjunganPerpustakaan::whereMonth('created_at', now()->format('m'))->count();
            $persenKunjunganPerpustakaan = number_format(($kunjunganPerpustakaanBulanLalu ? ($kunjunganPerpustakaanBulanIni - $kunjunganPerpustakaanBulanLalu) / $kunjunganPerpustakaanBulanLalu * 100 : 0));
        //GET SERING MELAKUKAN PINJAMAN
            $pinjamanTerbanyaks = \DB::SELECT(\DB::raw("SELECT
                                                pa.nama_lengkap, d.file_path
                                            FROM
                                                (SELECT
                                                    profil_anggota_id,
                                                    COUNT(profil_anggota_id) AS jumlahPeminjamans
                                                FROM
                                                    peminjamans
                                                WHERE
                                                    status = 'Sudah Kembali'
                                                GROUP BY
                                                    profil_anggota_id
                                                ORDER BY
                                                    jumlahPeminjamans DESC
                                                LIMIT 4) AS peminjaman
                                            INNER JOIN
                                                (profil_anggotas pa
                                                INNER JOIN (users u
                                                            LEFT JOIN dokumens d
                                                            ON u.foto_id = d.id)
                                                ON pa.user_id = u.id)
                                            ON
                                                peminjaman.profil_anggota_id = pa.id"));
        //GET BUKU TERLARIS
            $bukuTerlaries = \DB::SELECT("SELECT b.*, d.file_path, q.jumlahTerpinjam, k.nama
                                        FROM (SELECT ib.bukus_id, count(ib.bukus_id) as jumlahTerpinjam
                                        FROM peminjamans p INNER JOIN item_bukus ib
                                        ON p.item_bukus_id = ib.id
                                        GROUP BY ib.bukus_id
                                        ORDER BY jumlahTerpinjam DESC LIMIT 5) AS q INNER JOIN ((bukus b INNER JOIN (sub_kategoris sb INNER JOIN kategoris k on sb.kategori_id = k.id)ON b.sub_kategori_id = sb.id)LEFT JOIN dokumens d
                                                                                                ON b.cover_id = d.id)
                                        ON q.bukus_id = b.id");
                                        return view('pages.backend.dashboard', compact('totalBuku', 'persenBuku','jumlahAnggota','persenProfilAnggota','jumlahPerpustakaan','persenKunjunganPerpustakaan','pinjamanTerbanyaks','bukuTerlaries'));
    }
    public function getdatasubkategoris(Request $request,$param){
        $subKategoris = SubKategori::find_dataIsActvie(true, $param);
        if ($request->has('q')) {
            $search = $request->q;
            $subKategoris = $subKategoris->where('nama', 'ilike', "%$search%");
        }
        return response()->json($subKategoris->get());
    }
    public function getdatakategoris(Request $request){
        $kategoris = Kategori::get_dataIsActvie(true);
        if ($request->has('q')) {
            $search = $request->q;
            $kategoris = $kategoris->where('nama', 'ilike', "%$search%");
        }
        return response()->json($kategoris->get());
    }
    public function getdatapenulis(Request $request){
        $penulis = Penulis::get_dataIsActvie(true);
        if ($request->has('q')) {
            $search = $request->q;
            $penulis = $penulis->where('nama', 'ilike', "%$search%");
        }
        return response()->json($penulis->get());
    }
    public function getdatapenerbit(Request $request){
        $penerbit = Penerbit::get_dataIsActvie(true);
        if ($request->has('q')) {
            $search = $request->q;
            $penerbit = $penerbit->where('namaPenerbit', 'ilike', "%$search%");
        }
        return response()->json($penerbit->get());
    }
    public function getdataraks(Request $request){
        $rak = Rak::get_dataIsActvie(true);
        if ($request->has('q')) {
            $search = $request->q;
            $rak = $rak->where('kode', 'ilike', "%$search%");
        }
        return response()->json($rak->get());
    }
    public function getdatapengadaans(Request $request){
        $pengadaan = Pengadaan::get_dataIsActvie(true);
        if ($request->has('q')) {
            $search = $request->q;
            $pengadaan = $pengadaan->where('nama', 'ilike', "%$search%");
        }
        return response()->json($pengadaan->get());
    }
    //Line Chart
    public function getlinechart(){
        $dataPerBulan = [];
        for($i=0; $i<6; $i++){
            $bulan = now()->subMonth($i);
            $tahun = $bulan->format('Y');
            $namaBulan = substr($bulan->format('F'),0 ,3);

            $jumlahKunjunganPerBulan = KunjunganPerpustakaan::whereMonth('tanggal_kunjungan', $bulan->format('m'))->whereYear('tanggal_kunjungan', $tahun)->count();
            $jumlahKunjunganLakiLakiPerBulan = KunjunganPerpustakaan::whereMonth('tanggal_kunjungan', $bulan->format('m'))->whereYear('tanggal_kunjungan', $tahun)->where('jenis_kelamin', 'laki-laki')->count();
            $jumlahKunjunganPerempuanPerBulan = KunjunganPerpustakaan::whereMonth('tanggal_kunjungan', $bulan->format('m'))->whereYear('tanggal_kunjungan', $tahun)->where('jenis_kelamin', 'perempuan')->count();

            $dataPerBulan[] = [
                                    'bulan' => $namaBulan."'".$bulan->format('y'),
                                    'jumlahKunjungan' => $jumlahKunjunganPerBulan,
                                    'jumlahKunjuganLakiLaki'=> $jumlahKunjunganLakiLakiPerBulan,
                                    'jumlahKunjuganPerempuan'=> $jumlahKunjunganPerempuanPerBulan,
                                ];
        }
        $dataPerBulan = array_reverse($dataPerBulan);

        return $dataPerBulan;
    }
    //Pie Chart
    public function getpiechart(){
        $data = \DB::SELECT("SELECT k.nama as name, COALESCE(q.jumlah, '0') as value
                                            FROM (SELECT sb.kategori_id, count(sb.kategori_id) as jumlah
                                            FROM item_bukus ib right JOIN
                                                (bukus b INNER JOIN sub_kategoris sb
                                                ON b.sub_kategori_id = sb.id)
                                            ON ib.bukus_id = b.id
                                            GROUP BY sb.kategori_id) as q INNER JOIN kategoris k
                                            ON q.kategori_id = k.id ");
        // $data = \DB::SELECT("SELECT k.nama as name, COALESCE(q.jumlah, '0') as value
        //                     FROM (SELECT sb.kategori_id, count(sb.kategori_id) as jumlah
        //                     FROM bukus b INNER JOIN sub_kategoris sb
        //                         ON b.sub_kategori_id = sb.id
        //                     WHERE b.jenis != 'Buku'
        //                     GROUP BY sb.kategori_id) as q INNER JOIN kategoris k
        //                     ON q.kategori_id = k.id ");
        return $data;
    }
    //Bar Chart
    public function getbarchart(){
        $dataPerBulan = [];
        $dataPerBulan[] = [
                            'Bulan',
                            'Laki-laki',
                            'Perempuan',
                        ];
        for ($i=0; $i < 6 ; $i++) {
            $bulan = now()->subMonth($i);
            $tahun = $bulan->format('Y');
            $namaBulan = substr($bulan->format('F'), 0,3);

            $peminjamLakiLaki = Peminjaman::join('profil_anggotas', 'peminjamans.profil_anggota_id', 'profil_anggotas.id')
                                            ->where('peminjamans.status','!=' ,'Lewat Batas Waktu Pinjaman')
                                            ->whereMonth('peminjamans.tanggal_pengambilan_pinjaman', $bulan->format('m'))
                                            ->whereYear('peminjamans.tanggal_pengambilan_pinjaman', $tahun)
                                            ->where('profil_anggotas.jenis_kelamin','laki-laki')
                                            ->count();
            $peminjamPerempuan = Peminjaman::join('profil_anggotas', 'peminjamans.profil_anggota_id', 'profil_anggotas.id')
                                            ->where('peminjamans.status','!=' ,'Lewat Batas Waktu Pinjaman')
                                            ->whereMonth('peminjamans.tanggal_pengambilan_pinjaman', $bulan->format('m'))
                                            ->whereYear('peminjamans.tanggal_pengambilan_pinjaman', $tahun)
                                            ->where('profil_anggotas.jenis_kelamin','perempuan')
                                            ->count();
            $dataPerBulan[] = [
                                $namaBulan."'".$bulan->format('y'),
                                $peminjamLakiLaki,
                                $peminjamPerempuan,
                            ];
        }

        return $dataPerBulan;
    }
}
