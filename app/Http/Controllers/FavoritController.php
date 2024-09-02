<?php

namespace App\Http\Controllers;

use App\Models\Favorit;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;

class FavoritController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        $profil_anggota = auth()->user()->profilAnggota;
        $cek_favorit = Favorit::where('buku_id', $request->buku_id)->where('profil_anggota_id', $profil_anggota->id)->first();
        if($cek_favorit){
            $cek_favorit->delete();
            $jumlah_favorit = Favorit::where('profil_anggota_id', $profil_anggota->id)->count();
            activity()->performedOn($cek_favorit)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($cek_favorit)])->log('Menghapus Buku dari favorit');
            return response()->json(['success' => true, 'type' => 'hapus','data' => 'favorit','jumlah' => $jumlah_favorit]);
        }else{
            // Favorit::create([
            //     'profil_anggota_id' => $profil_anggota->id,
            //     'buku_id'=> $request->buku_id
            // ]);
            $newFavorit = new Favorit();
            $newFavorit->profil_anggota_id =  $profil_anggota->id;
            $newFavorit->buku_id = $request->buku_id;
            $newFavorit->tanggal_masuk_favorit = date('Y-m-d');
            $newFavorit->save();
            $jumlah_favorit = Favorit::where('profil_anggota_id', $profil_anggota->id)->count();
            activity()->performedOn($newFavorit)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($newFavorit)])->log('Menambahkan Buku ke favorit');
            return response()->json(['success' => true, 'type' => 'create','data' => 'favorit','jumlah' => $jumlah_favorit]);
        }

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
