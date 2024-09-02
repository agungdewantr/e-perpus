<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use Illuminate\Http\Request;

class KeranjangController extends Controller
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
        $cek_keranjang = Keranjang::where('buku_id', $request->buku_id)->where('profil_anggota_id', $profil_anggota->id)->first();
        if($cek_keranjang){
            $cek_keranjang->delete();
            activity()->performedOn($cek_keranjang)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($cek_keranjang)])->log('Menghapus Buku dari keranjang');
           $jumlah_keranjang = Keranjang::where('profil_anggota_id', $profil_anggota->id)->count();

            return response()->json(['success' => true, 'type' => 'hapus','data' => 'keranjang','jumlah' => $jumlah_keranjang]);
        }else{
            $newkeranjang = new Keranjang();
            $newkeranjang->profil_anggota_id =  $profil_anggota->id;
            $newkeranjang->buku_id = $request->buku_id;
            $newkeranjang->tanggal_masuk_keranjang = date('Y-m-d');
            $newkeranjang->save();
            $jumlah_keranjang = Keranjang::where('profil_anggota_id', $profil_anggota->id)->count();
            activity()->performedOn($newkeranjang)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($newkeranjang)])->log('menambahkan Buku ke keranjang');
            return response()->json(['success' => true, 'type' => 'create','data' => 'keranjang','jumlah' => $jumlah_keranjang]);
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
