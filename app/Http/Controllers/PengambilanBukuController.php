<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use Carbon\Carbon;

class PengambilanBukuController extends Controller
{
    public function index(){
        return view('pages.backend.pengambilanbuku.index');
    }
    public function datatable(){
        $peminjamans = Peminjaman::where('status', 'Belum Diambil')
                                    ->orWhere('status', 'Lewat Batas Waktu Pengambilan')
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
        ->addColumn('tanggal_pengajuan_peminjaman', function ($row) {
            $formattedDate = Carbon::parse($row->tanggal_pengajuan_pinjaman)->isoFormat('DD MMMM YYYY');
            return $formattedDate;
        })
        ->make(true);
    }
    //Setuju Verifikasi Pengambilan
    public function setuju($param){
        try {
            \DB::beginTransaction();
            $id = decrypt($param);
            $peminjaman = Peminjaman::find($id);
            $peminjaman->is_accepted = true;
            $peminjaman->tanggal_pengambilan_pinjaman = now();
            $peminjaman->tanggal_batas_pinjaman = now()->addDay(7);
            $peminjaman->status = 'Sedang Dipinjam';
            $peminjaman->save();

            activity()->performedOn($peminjaman)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($peminjaman)])->log('Buku Telah Diambil');

            \DB::commit();
            return response()->json([
                'status' => 'success',
                'title' => 'Sukses!',
                'message' => 'Buku dengan judul '.$peminjaman->itemBuku->buku->judul.' telah diserahkan.'
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
}
