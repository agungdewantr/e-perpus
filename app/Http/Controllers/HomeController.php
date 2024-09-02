<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Prosedur;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $kategori = Kategori::all();
        $buku_bulan_ini = Buku::get_dataIsActive(1)->orderBy('id','DESC')->take(5)->get();
        $buku_audio = Buku::get_dataIsActive(1)->where('jenis','Buku Audio')->take(5)->get();
        $buku_ebook = Buku::get_dataIsActive(1)->where('jenis','Buku Digital')->take(5)->get();
        $post = Berita::orderBy('id','desc')->take(10)->get();
        if (Auth::User() !== null) {
            // if (Auth::User()->role->nama == 'admin' || Auth::User()->role->nama == 'petugas') {
            //     // $route = DashboardController::dashboard();
            //     $route = new DashboardController();
            //     $route->dashboard();
            //     // return view('pages.backend.dashboard');
            // }
            // if (Auth::User()->role->nama == 'anggota') {
            //     $user = User::where('id', Auth::User()->id)->with('role', 'profilAnggota')->first();
            //     return view('pages.frontend.beranda.index', compact('user','kategori','buku_bulan_ini','buku_audio','buku_ebook','post'));
            // }
            $user = User::where('id', Auth::User()->id)->with('role', 'profilAnggota')->first();
            return view('pages.frontend.beranda.index', compact('user','kategori','buku_bulan_ini','buku_audio','buku_ebook','post'));
        } else {
            $user = null;
            return view('pages.frontend.beranda.index', compact('user','kategori','buku_bulan_ini','buku_audio','buku_ebook','post'));
        }
    }

    public function tentang_kami()
    {
        $buku_rekomendasi = Buku::join('item_bukus', 'bukus.id', 'item_bukus.bukus_id')
        ->join('peminjamans', 'item_bukus.id', 'peminjamans.item_bukus_id')
        ->select('bukus.*', \DB::raw('COUNT(peminjamans.id) as jumlah_peminjaman'))
        ->groupBy('bukus.id')
        ->orderByDesc('jumlah_peminjaman')
        ->take(4)
        ->get();
        $prosedur = Prosedur::where('is_active', true)->orderby('created_at', 'desc')->get();
        return view('pages.frontend.tentang-kami.index', compact('prosedur','buku_rekomendasi'));
    }

    public function resetsequence(){
        $array_seq = [
            'bukus' => 'bukus_id_seq',
            'item_bukus' => 'item_bukus_id_seq',
            'sub_kategoris' => 'sub_kategoris_id_seq',
            'raks' => 'raks_id_seq',
            'penulis' => 'penulis_id_seq',
            'penulis_bukus' => 'penulis_bukus_id_seq',
            'penerbits' => 'penerbits_id_seq',
        ];
        foreach($array_seq as $table => $seq){
            $maxId = \DB::table($table)->max('id');
            \DB::statement("SELECT setval('$seq', $maxId + 1)");
        }

        return 'berhasil';
    }
}
