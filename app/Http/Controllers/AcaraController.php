<?php

namespace App\Http\Controllers;

use App\Models\Acara;
use App\Models\Buku;
use Illuminate\Http\Request;

class AcaraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($param = null, Request $request)
    {
        $buku_rekomendasi = Buku::get_dataIsActive(1)->orderBy('id','DESC')->take(2)->get();
        if ($param == null) {
            $acara = Acara::orderBy('id', 'desc')->where('judul', 'ilike', '%' . $request->get('judul') . '%')->where('is_active', true)->paginate(5);
            return view('pages.frontend.acara.index', compact('acara', 'buku_rekomendasi'));
        } else {
            $acara = Acara::orderBy('id', 'desc')->where('is_active', true)->paginate(5);
            return view('pages.frontend.acara.' . $param, compact('acara', 'buku_rekomendasi'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function filter(Request $request)
    {
        if ($request->judul) {
            $acara = Acara::whereRaw('LOWER(judul) LIKE ?', ["%" . strtolower($request->judul) . "%"])->orderBy('id', 'desc')->paginate(6);
        } else {
            $acara = Acara::orderBy('id', 'desc')->paginate(6);
        }

        return view('pages.frontend.acara.filter', compact('acara'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
