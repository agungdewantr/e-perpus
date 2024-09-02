<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProfilAnggota;
use DataTables;
use Carbon\Carbon;

class VerifikasiAnggotaController extends Controller
{
    //Halaman verifikasi anggota
    public function index()
    {
        return view('pages.backend.verifikasianggota.index');
    }
    //Datatable menampilkan anggota belum diverifikasi
    public function datatable()
    {
        $anggotas = ProfilAnggota::get_dataIsActvieAndIsVerified(true, null)->get();
        return datatables()->of($anggotas)
        ->addColumn('id', function ($row) {
            $id = encrypt($row->id);
            return $id;
        })
        ->addColumn('ttl', function ($row) {
            $formattedDate = Carbon::parse($row->tanggal_lahir)->isoFormat('DD MMMM YYYY');
            $ttl = $row->tempat. ', '. $formattedDate;
            return $ttl;
        })
        ->addColumn('created_at', function ($row) {
            $formattedDate = Carbon::parse($row->created_at)->isoFormat('DD MMMM YYYY');
            return $formattedDate;
        })
        ->make(true);
    }
    //Setuju Verifikasi Anggota
    public function setujuverifikasi($param){
        try {
            \DB::beginTransaction();
            $id = decrypt($param);
            $profilAnggota = ProfilAnggota::find($id);
            $profilAnggota->is_verified = true;
            $profilAnggota->petugas_pj_verified = auth()->user()->profilPetugas->id;
            $profilAnggota->tanggal_verified = now();
            $nomorAnggota = ProfilAnggota::generateNomorAnggota(date("Y"));
            $profilAnggota->nomor_anggota = $nomorAnggota;
            $profilAnggota->save();

            activity()->performedOn($profilAnggota)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($profilAnggota)])->log('Setuju Verifikasi');

            \DB::commit();
            return response()->json([
                'status' => 'success',
                'title' => 'Sukses!',
                'message' => $profilAnggota->nama_lengkap.' telah diverifikasi.'
            ], 200);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'status' => 'error',
                'title' => 'Gagal!',
                'message' => 'Proses verifikasi gagal proses, hubungi Penanggung Jawab Aplikasi.'. $e->getMessage()
            ], 500);
        }
    }
    //Tolak Verifikasi Anggota
    public function tolakverifikasi($param){
        try {
            \DB::beginTransaction();
            $id = decrypt($param);
            $profilAnggota = ProfilAnggota::find($id);
            $profilAnggota->is_verified = false;
            $profilAnggota->petugas_pj_verified = auth()->user()->profilPetugas->id;
            $profilAnggota->tanggal_verified = now();
            $profilAnggota->save();
            activity()->performedOn($profilAnggota)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($profilAnggota)])->log('Tolak Verifikasi');
            \DB::commit();
            return response()->json([
                'status' => 'success',
                'title' => 'Sukses!',
                'message' => $profilAnggota->nama_lengkap.' telah ditolak verifikasi.'
            ], 200);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'status' => 'error',
                'title' => 'Gagal!',
                'message' => 'Proses verifikasi gagal proses, hubungi Penanggung Jawab Aplikasi.'
            ], 500);
        }
    }
}
