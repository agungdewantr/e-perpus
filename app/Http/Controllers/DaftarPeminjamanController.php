<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\ProfilAnggota;
use App\Models\SubKategori;
use App\Models\Buku;
use App\Models\ItemBuku;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Pusher\Pusher;

class DaftarPeminjamanController extends Controller
{
    public function index(){
        return view('pages.backend.daftarpeminjaman.index');
    }

    public function datatable(){
        $peminjamans = Peminjaman::where('status','!=', 'Lewat Batas Waktu Pengambilan')->get();
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
            if($row->tanggal_pengambilan_pinjaman != null){
                $formattedDate = Carbon::parse($row->tanggal_pengambilan_pinjaman)->isoFormat('DD MMMM YYYY');
            }
            else{
                $formattedDate = '-';
            }
            return $formattedDate;
        })
        ->addColumn('tanggal_batas_kembali', function ($row) {
            if($row->tanggal_batas_pinjaman != null){
                if($row->is_persetujuan_permohoman_perpanjangan == true){
                    $formattedDate = Carbon::parse($row->tanggal_batas_pinjaman_perpanjangan)->isoFormat('DD MMMM YYYY');
                }
                else{
                    $formattedDate = Carbon::parse($row->tanggal_batas_pinjaman)->isoFormat('DD MMMM YYYY');
                }
            }
            else{
                $formattedDate = '-';
            }
            return $formattedDate;
        })
        ->addColumn('tanggal_pengembalian', function ($row) {
            if($row->tanggal_pengembalian_pinjaman == null){
                $formattedDate = '-';
            }
            else{
                $formattedDate = Carbon::parse($row->tanggal_pengembalian_pinjaman)->isoFormat('DD MMMM YYYY');
            }
            return $formattedDate;
        })
        ->make(true);
    }
    //Modal Create Peminjaman
    public function create(){
        $anggotas = ProfilAnggota::get_dataIsActvie(true)->get();
        $idBukus = \DB::table('bukus as b')
                    ->join('item_bukus as ib', 'b.id', '=', 'ib.bukus_id')
                    ->where('ib.is_tersedia', true)
                    ->select('b.id')
                    ->groupby('b.id')
                    ->pluck('b.id');
        $bukus = Buku::with('penulises')
                    ->whereIn('id', $idBukus)
                    ->get();

        $result = $bukus->map(function($buku){
            $penulis = strtoupper(substr($buku->penulises[0]->nama, 0, 3));
            $penerbit = strtolower(substr($buku->penerbit->namaPenerbit, 0, 1));
            $value = $buku->subKategori->kode. ' '. $penulis. ' '.$penerbit.' - '.$buku->judul;
            return $value;
        });

        $bukuTersedias = $result->all();

        return view('pages.backend.daftarpeminjaman.modal.create', compact('anggotas','bukus','bukuTersedias'));
    }
    //Proses Create Peminjaman
    public function store(Request $request)
    {
        try {
            \DB::beginTransaction();
            //OPEN VALIDASI INPUTAN
            $validate = $request->validate(
                [
                    'profil_anggota_id' => 'required',
                    'item_bukus_id' => 'required',
                    'tanggal_pengambilan_pinjaman' => 'required',
                    'tanggal_batas_pinjaman' => 'required',
                    'status' => 'required',
                ],
                [
                    'profil_anggota_id.required' => 'Pilh Data Anggota.',
                    'item_bukus_id.required' => 'Pilh Buku yang dipinjam.',
                    'tanggal_pengambilan_pinjaman.required' => 'Pilih tanggal peminjaman.',
                    'tanggal_batas_pinjaman.required' => 'Pilih tanggal peminjaman untuk mendapatkan tanggal batas kembali.',
                    'status.required' => 'Pilh Status.',
                ]
            );
            //CLOSE VALIDASI INPUTAN
            $buku = Buku::find($request->item_bukus_id);
            $anggota = ProfilAnggota::find($request->profil_anggota_id);
            //Pengecekan Buku yang sedang dipinjam
            $itemBuku =  $buku->itemBukus->pluck('id')->all();
            $cek = Peminjaman::where('profil_anggota_id', $request->profil_anggota_id)
                                ->whereIn('status', ['Belum Diambil','Sedang Dipinjam','Belum Kembali'])
                                ->whereIn('item_bukus_id',$itemBuku)
                                ->get();
            if($cek->count() > 0){
                return response()->json([
                    'icon' => 'error',
                    'title' => 'Gagal!',
                    'message' => $anggota->nama_lengkap.' sedang dalam peminjaman buku tersebut.',
                ], 200);
            }
            //Update stok buku tersedia
            $itemBuku = ItemBuku::find($buku->itemBukus[0]->id);
            $itemBuku->is_tersedia = false;
            $itemBuku->save();

            //Generate kode nota
            $jumlah = Peminjaman::whereDate('created_at', now())->count();
            $kodeNotaPeminjaman = $jumlah > 0 ? $jumlah + 1 : 1;
            $kode_nota_peminjaman = now()->format('ymd'). str_pad($kodeNotaPeminjaman, 4, '0', STR_PAD_LEFT);

            //Proses Peminjaman
            $peminjaman = new Peminjaman();
            $peminjaman->item_bukus_id = $itemBuku->id;
            $peminjaman->profil_anggota_id = $request->profil_anggota_id;

            $peminjaman->kode_nota_peminjaman = $kode_nota_peminjaman;
            $peminjaman->is_accepted = true;
            $peminjaman->tanggal_pengajuan_pinjaman = now();
            $peminjaman->tanggal_pengambilan_pinjaman = $request->tanggal_pengambilan_pinjaman;
            $peminjaman->tanggal_batas_pinjaman = $request->tanggal_batas_pinjaman;
            $peminjaman->petugas_pj_pengambilan_pinjaman_id = auth()->user()->profilPetugas->id;
            $peminjaman->status = $request->status;

            $peminjaman->save();
            activity()->performedOn($peminjaman)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($peminjaman)])->log('Tambah Pinjaman');

            \DB::commit();
            return response()->json([
                'icon' => 'success',
                'title' => 'Sukses!',
                'message' => 'Pinjaman telah berhasil dibuat.'
            ], 200);
        } catch (ValidationException $e) {
            \DB::rollback();
            return response()->json([
                'icon' => 'error',
                'title' => 'Gagal!',
                'message' => 'Cek kembali data yang telah diinput.',
                'messageValidate' => $e->validator->getMessageBag()
            ], 422);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'icon' => 'error',
                'title' => 'Gagal!',
                'message' => 'Proses pinjaman buku gagal proses, hubungi Penanggung Jawab Aplikasi' . $e->getMessage()
            ], 500);
        }
    }
    //Modal Edit Manajemen Buku
    public function edit($param)
    {
        $id = decrypt($param);
        $peminjaman = Peminjaman::find($id);
        return view('pages.backend.daftarpeminjaman.modal.edit', compact('peminjaman'));
    }
    //Proses Update Peminjaman
    public function update(Request $request)
    {
        try {
            \DB::beginTransaction();
            //OPEN VALIDASI INPUTAN
            $validate = $request->validate(
                [
                    'status' => 'required',
                ],
                [
                    'status.required' => 'Pilh Status.',
                ]
            );
            //CLOSE VALIDASI INPUTAN
            //Proses Peminjaman
            $id = decrypt($request->id);
            $peminjaman = Peminjaman::find($id);
            if($request->status == 'Sudah Kembali')
            {
                $itemBuku = $peminjaman->itemBuku;
                $itemBuku->is_tersedia = true;
                $itemBuku->save();
                $peminjaman->tanggal_pengembalian_pinjaman = now();
                $peminjaman->petugas_pj_pengembalian_pinjaman_id = auth()->user()->profilPetugas->id;

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
                    'message' => 'Butuh Ulasan Kamu',
                    'isi' => 'Silahkan tambahkan ulasan buku yang telah kamu pinjam',
                    'route' => route('katalog-buku.overview', $peminjaman->itemBuku->buku->slug)
                ]);
                $notifikasi = new Notifikasi();
                $notifikasi->user_id_from = auth()->user()->id;
                $notifikasi->user_id_to = $peminjaman->profilAnggota->user->id;
                $notifikasi->tentang = 'Butuh Ulasan Kamu';
                $notifikasi->route = route('katalog-buku.overview', $peminjaman->itemBuku->buku->slug);
                $notifikasi->isi = 'Silahkan tambahkan ulasan buku yang telah kamu pinjam.';
                $notifikasi->save();
            }
            $peminjaman->status = $request->status;

            $peminjaman->save();
            activity()->performedOn($peminjaman)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($peminjaman)])->log('Ubah Pinjaman');

            \DB::commit();
            return response()->json([
                'title' => 'Sukses!',
                'message' => 'Pinjaman telah berhasil diubah.'
            ], 200);
        } catch (ValidationException $e) {
            \DB::rollback();
            return response()->json([
                'title' => 'Gagal!',
                'message' => 'Cek kembali data yang telah diinput.',
                'messageValidate' => $e->validator->getMessageBag()
            ], 422);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'title' => 'Gagal!',
                'message' => 'Proses pinjaman buku gagal proses, hubungi Penanggung Jawab Aplikasi' . $e->getMessage()
            ], 500);
        }
    }
    //Modal Detail Manajemen Buku
    public function detail($param)
    {
        $id = decrypt($param);
        $peminjaman = Peminjaman::find($id);
        return view('pages.backend.daftarpeminjaman.modal.detail', compact('peminjaman'));
    }
}
