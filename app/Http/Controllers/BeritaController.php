<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class BeritaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($param = null, Request $request)
    {
        // $perPage = 2;
        // $currentPage = $request->page ?: 1;

        // $berita = Berita::paginate($perPage, ['*'], 'page', $currentPage);
        // $html = '';
        // foreach ($berita as $item) {
        //     $html .= '<div class="col-lg-4">
        //         <div class="dz-blog style-1 bg-white m-b30 wow fadeInUp" data-wow-delay="0.1s">
        //             <div class="dz-media">
        //                 <a href="'.route('berita-perpustakaan.overview', $item->slug).'"><img src="'.asset('storage/'.$item->gambar->file_path).'" alt="/"></a>
        //             </div>
        //             <div class="dz-info p-3">
        //                 <h6 class="dz-title">
        //                     <a href="'.route('berita-perpustakaan.overview', $item->slug).'">'.$item->judul.'</a>
        //                 </h6>
        //                 <small class="text-black">'.\Carbon\Carbon::parse($item->created_at)->format('d F Y').'</small>
        //                 <p style="text-align: justify; margin-bottom:0;">'.Str::limit($item->deskripsi, 80).'</p>
        //             </div>
        //         </div>
        //     </div>';
        // }

        // $pagination = $berita->links()->toHtml();
        // $html .= '<div class="col-lg-12">'.$pagination.'</div>';

        // if($request->ajax()){
        //     return response()->json(['html' => $html]);
        // }
        $buku_rekomendasi = Buku::get_dataIsActive(1)->join('item_bukus', 'bukus.id', 'item_bukus.bukus_id')
            ->join('peminjamans', 'item_bukus.id', 'peminjamans.item_bukus_id')
            ->select('bukus.*', \DB::raw('COUNT(peminjamans.id) as jumlah_peminjaman'))
            ->groupBy('bukus.id')
            ->orderByDesc('jumlah_peminjaman')
            ->take(2)
            ->get();
        if ($param == null) {
            $berita = Berita::orderBy('id', 'desc')->where('judul', 'ilike', '%' . $request->get('judul') . '%')->where('is_active', true)->paginate(6);
            return view('pages.frontend.berita.index', compact('berita', 'buku_rekomendasi'));
        } else {
            $berita = Berita::orderBy('id', 'desc')->where('is_active', true)->paginate(6);
            return view('pages.frontend.berita.' . $param, compact('berita', 'buku_rekomendasi'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request)
    {
        if ($request->judul) {
            $berita = Berita::whereRaw('LOWER(judul) LIKE ?', ["%" . strtolower($request->judul) . "%"])->orderBy('id', 'desc')->paginate(6);
        } else {
            $berita = Berita::orderBy('id', 'desc')->paginate(6);
        }

        return view('pages.frontend.berita.filter', compact('berita'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $buku_rekomendasi = Buku::join('item_bukus', 'bukus.id', 'item_bukus.bukus_id')
            ->join('peminjamans', 'item_bukus.id', 'peminjamans.item_bukus_id')
            ->select('bukus.*', \DB::raw('COUNT(peminjamans.id) as jumlah_peminjaman'))
            ->groupBy('bukus.id')
            ->orderByDesc('jumlah_peminjaman')
            ->take(4)
            ->get();
        $berita = Berita::where('slug', $slug)->first();
        return view('pages.frontend.berita.detail', compact('berita', 'buku_rekomendasi'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
}
