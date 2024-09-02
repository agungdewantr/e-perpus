<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Pusher\Pusher;

class PerpanjanganBukuController extends Controller
{
    public function index(){
        return view('pages.backend.perpanjanganbuku.index');
    }
    public function datatable(){
        $peminjamans = Peminjaman::where('is_permohonan_perpanjangan', true)
                                    ->where('is_persetujuan_permohoman_perpanjangan', null)
                                    ->get();
        return datatables()->of($peminjamans)
        ->addColumn('id', function ($row) {
            $id = encrypt($row->id);
            return $id;
        })
        ->addColumn('nomor_anggota', function ($row) {
            $nomor_anggota = $row->profilAnggota->nomor_anggota;
            return $nomor_anggota;
        })
        ->addColumn('nama_lengkap', function ($row) {
            $nama = $row->profilAnggota->nama_lengkap;
            return $nama;
        })
        ->addColumn('kode_buku', function ($row) {
            $kode = $row->itemBuku->buku->subKategori->kode. ' '. strtoupper(substr($row->itemBuku->buku->penulises[0]->nama, 0, 3)). ' '.strtolower(substr($row->itemBuku->buku->penerbit->namaPenerbit, 0, 1));
            return $kode;
        })
        ->addColumn('judul', function ($row) {
            $data = $row->itemBuku->buku->judul;
            return $data;
        })
        ->addColumn('tanggal_peminjaman', function ($row) {
            $formattedDate = Carbon::parse($row->tanggal_pengambilan_pinjaman)->isoFormat('DD MMMM YYYY');
            return $formattedDate;
        })
        ->addColumn('tanggal_batas_kembali', function ($row) {
            $formattedDate = Carbon::parse($row->tanggal_batas_pinjaman)->isoFormat('DD MMMM YYYY');
            return $formattedDate;
        })
        ->addColumn('tanggal_batas_kembali_setelah_perpanjangan', function ($row) {
            $formattedDate = Carbon::parse($row->tanggal_batas_pinjaman)->addDay(7)->isoFormat('DD MMMM YYYY');
            return $formattedDate;
        })
        ->make(true);
    }
    //Setuju Perpanjangan Buku
    public function setuju($param){
        try {
            DB::beginTransaction();
            $id = decrypt($param);
            $peminjaman = Peminjaman::find($id);
            $peminjaman->is_persetujuan_permohoman_perpanjangan = true;
            $peminjaman->tanggal_batas_pinjaman_perpanjangan = Carbon::parse($peminjaman->tanggal_batas_pinjaman)->addDays(7);
            $peminjaman->petugas_pj_permohonan_perpanjangan_id = auth()->user()->profilPetugas->id;
            $peminjaman->save();

            activity()->performedOn($peminjaman)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($peminjaman)])->log('Perpanjangan Diterima');
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
            $pusher->trigger($peminjaman->profilAnggota->user->id . '-notification', 'notify', [
                'message' => 'Perpanjangan Diterima',
                'isi' => 'Pengajuan perpanjangan peminjaman diterima.',
                'route' => route('detailanggota.index', null)
            ]);
            $notifikasi = new Notifikasi();
            $notifikasi->user_id_from = auth()->user()->id;
            $notifikasi->user_id_to = $peminjaman->profilAnggota->user->id;
            $notifikasi->tentang = 'Perpanjangan Diterima';
            $notifikasi->route = route('detailanggota.index', null);
            $notifikasi->isi = 'Pengajuan perpanjangan peminjaman diterima.';
            $notifikasi->save();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'title' => 'Sukses!',
                'message' => 'Buku dengan judul '.$peminjaman->itemBuku->buku->judul.' telah diperpanjang.'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'title' => 'Gagal!',
                'message' => 'Proses verifikasi gagal proses, hubungi Penanggung Jawab Aplikasi.'. $e->getMessage()
            ], 500);
        }
    }
    //Tolak Perpanjangan Buku
    public function tolak($param){
        try {
            DB::beginTransaction();
            $id = decrypt($param);
            $peminjaman = Peminjaman::find($id);
            $peminjaman->is_persetujuan_permohoman_perpanjangan = false;
            $peminjaman->petugas_pj_permohonan_perpanjangan_id = auth()->user()->profilPetugas->id;
            $peminjaman->save();

            activity()->performedOn($peminjaman)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($peminjaman)])->log('Perpanjangan Ditolak');

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
            $pusher->trigger($peminjaman->profilAnggota->user->id . '-notification', 'notify', [
                'message' => 'Perpanjangan Ditolak',
                'isi' => 'Pengajuan perpanjangan peminjaman ditolak.',
                'route' => route('detailanggota.index', null)
            ]);
            $notifikasi = new Notifikasi();
            $notifikasi->user_id_from = auth()->user()->id;
            $notifikasi->user_id_to = $peminjaman->profilAnggota->user->id;
            $notifikasi->tentang = 'Perpanjangan Ditolak';
            $notifikasi->route = route('detailanggota.index', null);
            $notifikasi->isi = 'Pengajuan perpanjangan peminjaman ditolak.';
            $notifikasi->save();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'title' => 'Sukses!',
                'message' => 'Buku dengan judul '.$peminjaman->itemBuku->buku->judul.' tidak diperpanjang.'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'title' => 'Gagal!',
                'message' => 'Proses verifikasi gagal proses, hubungi Penanggung Jawab Aplikasi.'. $e->getMessage()
            ], 500);
        }
    }
}
