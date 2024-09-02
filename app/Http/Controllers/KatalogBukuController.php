<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Favorit;
use App\Models\ItemBuku;
use App\Models\Kategori;
use App\Models\Keranjang;
use App\Models\Notifikasi;
use App\Models\Peminjaman;
use App\Models\Ulasan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Pusher\Pusher;

class KatalogBukuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($param = null, Request $request)
{
    $optkategori = Kategori::all();
    $optTahun =Buku::selectRaw('DISTINCT EXTRACT(YEAR FROM tanggal_terbit) as tahun')->orderBy('tahun','asc')->get();

    if ($param == null) {
        $jenis = isset($request->jenis[0]) ? $request->jenis[0] : '';
        $kategori = isset($request->kategori[0]) ? $request->kategori[0] : '';
        $judulBuku = isset($request->judul) ? $request->judul : '';
        $books = Buku::get_dataIsActive(1)->orderByRaw('
                                            CASE
                                                WHEN updated_at IS NOT NULL THEN 0
                                                ELSE 1
                                            END, updated_at DESC
                                            ')->paginate(12);

        if ($request->ajax()) {
            return view('pages.frontend.katalogbuku.filter', compact('books', 'optkategori', 'optTahun', 'jenis', 'judulBuku', 'kategori'));
        }

        return view('pages.frontend.katalogbuku.index', compact('optkategori', 'books', 'optTahun', 'jenis', 'judulBuku', 'kategori'));
    } else {
        if ($param == 'all') {
            $books = Buku::get_dataIsActive(1)->orderByRaw('
                                                CASE
                                                    WHEN updated_at IS NOT NULL THEN 0
                                                    ELSE 1
                                                END, updated_at DESC
                                                ')->paginate(12);

            if ($request->ajax()) {
                return view('pages.frontend.katalogbuku.filter', compact('books', 'optkategori', 'optTahun'));
            }

            return view('pages.frontend.katalogbuku.'.$param, compact('optkategori', 'books', 'optTahun'));
        } else {
            $lihat_semua = true;
            $books = Buku::get_dataIsActive(1)->where('jenis', $param)->orderByRaw('
                                                                                    CASE
                                                                                        WHEN updated_at IS NOT NULL THEN 0
                                                                                        ELSE 1
                                                                                    END, updated_at DESC
                                                                                    ')->paginate(12);

            if ($request->ajax()) {
                return view('pages.frontend.katalogbuku.filter', compact('books', 'optkategori', 'optTahun', 'lihat_semua', 'param'));
            }

            return view('pages.frontend.katalogbuku.all', compact('optkategori', 'books', 'optTahun', 'lihat_semua', 'param'));
        }
    }

    return view('pages.frontend.katalogbuku.index', compact('optkategori', 'books', 'optTahun'));
}


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function downloads($param)
    {
        $peminjaman = Peminjaman::where('kode_nota_peminjaman',$param)->get();
        $pdf = FacadePdf::loadview('pages.frontend.katalogbuku.cetakbuktipeminjaman',compact('peminjaman'));
        $pdf->setPaper([0, 0, 368.55, 670], 'portrait');
        // $pdf->setOptions(['margin-top' => 10, 'margin-right' => 10, 'margin-bottom' => 10, 'margin-left' => 10]);
        $nama_file = 'cetak_bukti_peminjaman_' . $peminjaman[0]->kode_nota_peminjaman . '.pdf';
        return $pdf->download($nama_file);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request)
    {
        $kategori = $request->kategori;
        $jenis = $request->jenis;
        $tahun = $request->tahun;
        $judul = $request->judul;
        $optkategori = Kategori::all();
        $optTahun = range(2022, date('Y'));
        $books = Buku::get_dataIsActive(1)->when(!empty($kategori) && !in_array('', $kategori), function ($query) use ($kategori) {
            return $query->whereHas('subKategori', function ($subQuery) use ($kategori) {
                $subQuery->whereIn('kategori_id', $kategori);
            });
        })
        ->when(!empty($jenis) && !in_array('', $jenis) && !in_array('bulan_ini', $jenis), function ($query) use ($jenis) {
            return $query->whereIn('jenis', $jenis);
        })
        ->when(!empty($tahun) && !in_array('', $tahun), function ($query) use ($tahun) {
            return $query->whereIn(\DB::raw('EXTRACT(YEAR FROM tanggal_terbit)'), $tahun);
        })
        ->when(!empty($judul), function ($query) use ($judul) {
            return $query->whereRaw('LOWER(judul) LIKE ?', ["%".strtolower($judul)."%"]);
        })
        ->when(!empty($jenis) && in_array('bulan_ini', $jenis), function ($query) use ($jenis) {
            return $query->orderBy('id','desc')->take(12);
        })
        ->get();
        return view('pages.frontend.katalogbuku.filter', compact('optkategori','books','optTahun'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $book = Buku::join('sub_kategoris', 'bukus.sub_kategori_id', 'sub_kategoris.id')
        ->where('slug', $slug)->first(['bukus.*','sub_kategoris.kategori_id']);


        $ipAddress = request()->ip();
        $today = Carbon::now()->toDateString();
        $cacheKey = "kunjungan:{$book->id}:{$ipAddress}:{$today}";
        if (!Cache::has($cacheKey)) {
            $book->increment('jumlah_kunjungan');
            Cache::put($cacheKey, true, Carbon::tomorrow());
        }


        if(auth()->user()){
            $profil_anggota = auth()->user()->profilAnggota;
            $idProfilAnggota = $profil_anggota->id ?? null;
        } else{
            $idProfilAnggota = 0;
        }

        $buku_rekomendasi = Buku::join('sub_kategoris', 'bukus.sub_kategori_id', 'sub_kategoris.id')
        ->join('kategoris', 'sub_kategoris.kategori_id', 'kategoris.id')
        ->where('kategoris.id',$book->kategori_id)
        ->take(2)
        ->get();

        $cek_favorit = Favorit::where('buku_id', $book->id)->where('profil_anggota_id', $idProfilAnggota)->first();
        $cek_keranjang = Keranjang::where('buku_id', $book->id)->where('profil_anggota_id', $idProfilAnggota)->first();
        if($cek_favorit){
            $isFavorit = true;
        } else{
            $isFavorit = false;
        }
        if($cek_keranjang){
            $isKeranjang = true;
        } else{
            $isKeranjang = false;
        }
        $cek_pinjam = Peminjaman::join('item_bukus', 'peminjamans.item_bukus_id', 'item_bukus.id')
        ->join('bukus','item_bukus.bukus_id','bukus.id')
        ->where('bukus.id', $book->id)
        ->where('peminjamans.profil_anggota_id',$idProfilAnggota)
        ->where('peminjamans.status','Sudah Kembali')
        ->count();
        $ulasan = Ulasan::join('bukus','ulasans.bukus_id','bukus.id')
        ->join('item_bukus','bukus.id','item_bukus.bukus_id')
        ->join('peminjamans','item_bukus.id','peminjamans.item_bukus_id')
        ->where('bukus.id',$book->id)
        ->orderBy('id','desc')
        ->get(['ulasans.*']);
        $status_ulasan = Ulasan::join('bukus','ulasans.bukus_id','bukus.id')
        ->join('item_bukus','bukus.id','item_bukus.bukus_id')
        ->join('peminjamans','item_bukus.id','peminjamans.item_bukus_id')
        ->where('peminjamans.profil_anggota_id',$idProfilAnggota)
        ->where('bukus.id',$book->id)
        ->where('peminjamans.status','Sudah Kembali')
        ->count();
        // dd($cek_pinjam, $status_ulasan);
        return view('pages.frontend.katalogbuku.detail',compact('book','isFavorit','isKeranjang','buku_rekomendasi','ulasan','status_ulasan','cek_pinjam'));
    }

    public function show_buku($id)
    {
        $buku = Buku::with('digital')->where('slug', $id)->first();
        return view('pages.frontend.buku.index', compact('buku'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function pinjamBuku(Request $request)
    {
        $bukusId = (array) $request->buku_id;
        $profil_anggota = auth()->user()->profilAnggota;
        $responses = [];

        $cek_kode_nota = Peminjaman::where('tanggal_pengajuan_pinjaman', date('Y-m-d'))->orderBy('id','desc')->first();
        foreach ($bukusId as $buku_id) {
            $judul_buku = Buku::find($buku_id)->judul;

            $cek_peminjaman = Peminjaman::join('item_bukus', 'peminjamans.item_bukus_id', 'item_bukus.id')
                                        ->join('bukus','item_bukus.bukus_id','bukus.id')
                                        ->where('bukus.id', $buku_id)
                                        ->where('peminjamans.profil_anggota_id',$profil_anggota->id)
                                        ->whereIn('peminjamans.status',['Belum Kembali','Sedang Dipinjam','Belum Diambil'])
                                        ->count();
            if($cek_peminjaman == 0){
                $item_buku = ItemBuku::where('bukus_id',$buku_id)->where('is_tersedia', 1)->first();
                $item_buku->is_tersedia = false;
                $item_buku->save();
                if($cek_kode_nota == null){
                    $kode_nota =  date('ymd').'0001';
                }else{
                    $kode_terakhir = $cek_kode_nota->kode_nota_peminjaman;
                    $nomor_terakhir = intval(substr($kode_terakhir, -4));
                    $nomor_baru = $nomor_terakhir + 1;
                    $nomor_baru_format = str_pad($nomor_baru, 4, '0', STR_PAD_LEFT);
                    $kode_nota = date('ymd') . $nomor_baru_format;
                }

                $pinjam = new Peminjaman();
                $pinjam->profil_anggota_id = $profil_anggota->id;
                $pinjam->item_bukus_id = $item_buku->id;
                $pinjam->kode_nota_peminjaman = $kode_nota;
                $pinjam->tanggal_pengajuan_pinjaman = date('Y-m-d');
                $pinjam->status = 'Belum Diambil';
                $pinjam->save();
                activity()->performedOn($pinjam)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($pinjam)])->log('Meminjam Buku');

                $petugases = User::join('profil_petugases', 'users.id','=', 'profil_petugases.user_id')
                                    ->select('users.id')
                                    ->get();
                $options = [
                    'cluster' => env('PUSHER_APP_CLUSTER'),
                    'useTLS' => true
                ];

                $pusher = new Pusher(
                    env('PUSHER_APP_KEY'),
                    env('PUSHER_APP_SECRET'),
                    env('PUSHER_APP_ID'),
                    $options
                );
                foreach($petugases as $petugas){
                    $pusher->trigger($petugas->id . '-notification', 'notify', [
                        'message' => 'Pengajuan Pinjaman',
                        'isi' => auth()->user()->profilAnggota->nama_lengkap.' telah mengajukan pinjaman.',
                        'route' => route('pengambilanbuku.index')
                    ]);
                    $notifikasi = new Notifikasi();
                    $notifikasi->user_id_from =  auth()->user()->id;
                    $notifikasi->user_id_to = $petugas->id;
                    $notifikasi->tentang = 'Pengajuan Pinjaman';
                    $notifikasi->route = route('pengambilanbuku.index');
                    $notifikasi->isi = auth()->user()->profilAnggota->nama_lengkap.' telah mengajukan pinjaman.';
                    $notifikasi->save();
                }

                $tanggal_ambil = now()->addDay(2)->format('d F Y');
                $responses[] = [
                    'id' => $buku_id,
                    'success' => true,
                    'tanggal_ambil' => $tanggal_ambil,
                    'judul_buku' => $judul_buku,
                    'kode_nota' => $pinjam->kode_nota_peminjaman,
                    'nama_anggota' => $profil_anggota->nama_lengkap,
                    'nomor_anggota' => $profil_anggota->nomor_anggota,
                ];
            } else{
                $responses[] = ['id' => $buku_id, 'error' => true];
            }

        }
        return response()->json(['responses' => $responses]);
    }

    public function ajukanPerpanjangan(Request $request)
    {
        try{
            \DB::beginTransaction();
            $cek_peminjaman = Peminjaman::find($request->peminjaman_id);
            if($cek_peminjaman->is_permohonan_perpanjangan){
                if($cek_peminjaman->status == 'Sudah Kembali'){
                    return response()->json(['sudahkembali' => true]);
                }else{
                    return response()->json(['sudah' => true]);
                }

            }else{
                if($cek_peminjaman->tanggal_batas_pinjaman >= date('Y-m-d')){
                    $cek_peminjaman->is_permohonan_perpanjangan = true;
                    $cek_peminjaman->save();
                    activity()->performedOn($cek_peminjaman)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($cek_peminjaman)])->log('Mengajukan perpanjangan peminjaman buku');
                    $petugases = User::join('profil_petugases', 'users.id','=', 'profil_petugases.user_id')
                                        ->select('users.id')
                                        ->get();
                    $options = [
                        'cluster' => env('PUSHER_APP_CLUSTER'),
                        'useTLS' => true
                    ];

                    $pusher = new Pusher(
                        env('PUSHER_APP_KEY'),
                        env('PUSHER_APP_SECRET'),
                        env('PUSHER_APP_ID'),
                        $options
                    );
                    foreach($petugases as $petugas){
                        $pusher->trigger($petugas->id . '-notification', 'notify', [
                            'message' => 'Pengajuan Perpanjangan Pinjaman',
                            'isi' => auth()->user()->profilAnggota->nama_lengkap.' telah mengajukan perpanjangan pinjaman.',
                            'route' => route('perpanjanganbuku.index')
                        ]);
                        $notifikasi = new Notifikasi();
                        $notifikasi->user_id_from =  auth()->user()->id;
                        $notifikasi->user_id_to = $petugas->id;
                        $notifikasi->tentang = 'Pengajuan Perpanjangan Pinjaman';
                        $notifikasi->route = route('perpanjanganbuku.index');
                        $notifikasi->isi = auth()->user()->profilAnggota->nama_lengkap.' telah mengajukan perpanjangan pinjaman.';
                        $notifikasi->save();
                    }
                    \DB::commit();
                    return response()->json(['success' => true]);
                }else{
                    return response()->json(['lewat' => true]);
                }
            }
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function ulasan(Request $request)
    {
        $newUlasan = new Ulasan();
        $newUlasan->bukus_id = $request->buku_id;
        $newUlasan->ulasan = $request->ulasan;
        $newUlasan->profil_anggota_id = auth()->user()->profilAnggota->id;
        $newUlasan->save();
        activity()->performedOn($newUlasan)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($newUlasan)])->log('Menambahkan ulasan');

        $cek_pinjam = Peminjaman::join('item_bukus', 'peminjamans.item_bukus_id', 'item_bukus.id')
        ->join('bukus','item_bukus.bukus_id','bukus.id')
        ->where('bukus.id',  $request->buku_id)
        ->where('peminjamans.status','Sudah Kembali')
        ->count();
        $ulasan = Ulasan::join('bukus','ulasans.bukus_id','bukus.id')
        ->join('item_bukus','bukus.id','item_bukus.bukus_id')
        ->join('peminjamans','item_bukus.id','peminjamans.item_bukus_id')
        ->where('bukus.id', $request->buku_id)
        ->orderBy('id','desc')
        ->get(['ulasans.*']);
        $status_ulasan = Ulasan::join('bukus','ulasans.bukus_id','bukus.id')
        ->join('item_bukus','bukus.id','item_bukus.bukus_id')
        ->join('peminjamans','item_bukus.id','peminjamans.item_bukus_id')
        ->where('peminjamans.profil_anggota_id',auth()->user()->profilAnggota->id)
        ->where('bukus.id', $request->buku_id)
        ->where('peminjamans.status','Sudah Kembali')
        ->count();
        return view('pages.frontend.katalogbuku.ulasan', compact('cek_pinjam','ulasan','status_ulasan'));
    }
}
