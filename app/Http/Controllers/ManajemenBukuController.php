<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Rak;
use App\Models\ItemBuku;
use App\Models\Penulis;
use App\Models\Penerbit;
use App\Models\Pengadaan;
use App\Models\SubKategori;
use App\Models\Buku;
use App\Models\Dokumen;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Image;
use carbon\Carbon;

class ManajemenBukuController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::get_dataIsActvie(true)->get();
        // $buku = Buku::all();
        // foreach($buku as $b){
        //     foreach ($b->itemBukus as $key => $item) {
        //         $urutan = $key+1;
        //         $item->kode_buku = $item->kode_buku."-".$urutan;
        //         $item->save();
        //     }
        // }
        return view('pages.backend.manajemenbuku.index', compact('kategoris'));
    }
    //Datatable menampilkan manajemen buku
    public function datatable()
    {
        $bukus = Buku::with('penulises', 'kategories', 'cover', 'audio', 'penerbit', 'rak')->orderby('id', 'desc')->get();
        // dd($bukus);
        return datatables()->of($bukus)
        ->addColumn('id', function ($row) {
            $id = encrypt($row->id);
            return $id;
        })
        ->addColumn('jumlah', function ($row) {

            if (count($row->itemBukus) > 0) {
                $jumlahTersedia = $row->itemBukus()->where('is_active', 1)->count();
                return $jumlahTersedia;
            } else {
                if($row->jenis == 'Buku'){
                    return '0';
                }
                else{
                    return $row->is_active == 1 ? '1' : '0';
                }
            }
        })
        ->addColumn('jumlah_aktif', function ($row) {
            if (count($row->itemBukus) > 0) {
                $jumlahAktif = $row->itemBukus()->where('is_active', 1)->count();
                return $jumlahAktif;
            } else {
                return $row->is_active == 1 ? '1' : '0';
            }
        })
        ->addColumn('jumlah_tidak_aktif', function ($row) {
            if (count($row->itemBukus) > 0) {
                $jumlahTidakAktif = $row->itemBukus()->where('is_active', 0)->count();
                return $jumlahTidakAktif;
            } else {
                return $row->is_active == 0 ? '1' : '0';
            }
        })
        ->addColumn('rak', function ($row) {
            return $row->rak ? $row->rak->kode : '-';
        })
        ->addColumn('penulies', function ($row) {
            $penulises = $row->penulises;
            if ($penulises->count() > 0) {
                $penulisNames = $penulises->pluck('nama')->toArray();
                $penulisesText = implode(', ', $penulisNames);

                return $penulisesText;
            }

            return '';
        })
        ->addColumn('tanggal_terbit', function ($row) {
            $formattedDate = Carbon::parse($row->created_at)->isoFormat('DD MMMM YYYY');
            return $formattedDate;
        })
        ->make(true);
    }
    //Modal Create Manajemen Buku
    public function create()
    {
        $kategoris = Kategori::get_dataIsActvie(true)->get();
        $raks = Rak::get_dataIsActvie(true)->get();
        $penulises = Penulis::get_dataIsActvie(true)->get();
        $penerbits = Penerbit::get_dataIsActvie(true)->orderby('namaPenerbit')->get();
        $pengadaans = Pengadaan::get_dataIsActvie(true)->get();

        return view('pages.backend.manajemenbuku.modal.create', compact('kategoris', 'raks', 'penulises', 'penerbits', 'pengadaans'));
    }
    //Proses Create Manajemen Buku
    public function store(Request $request)
    {

        // $penulises = $request->input('penulis_id', []);
        // $idPenulises = [];
        // foreach($penulises as $item){
        //     if(is_numeric($item)){
        //         $cek_penulis = Penulis::where('id',$item)->first();
        //         if($cek_penulis == null){
        //             $cek_penulis = new Penulis();
        //             $cek_penulis->nama = $item;
        //             $cek_penulis->is_active = true;
        //             $cek_penulis->save();
        //         }
        //         $id = $cek_penulis->id;
        //     }
        //     else{
        //         $cek_penulis = new Penulis();
        //         $cek_penulis->nama = $item;
        //         $cek_penulis->is_active = true;
        //         $cek_penulis->save();
        //         $id = $cek_penulis->id;
        //     }
        //     $idPenulises[] = $id;
        // }
        // dd($idPenulises);
        try {
            \DB::beginTransaction();
            //Proses Create Buku
            if ($request->jenis_id == 'Buku') {
                //OPEN VALIDASI INPUTAN
                    $validate = $request->validate(
                        [
                            'judul' => 'required|string',
                            'deskripsi' => 'required|string',
                            'kategori_id' => 'required',
                            'sub_kategori_id' => 'required',
                            'isbn' => 'required',
                            'penerbit_id' => 'required',
                            'penulis_id' => 'required',
                            'tahun_cetak' => 'nullable',
                            'tanggal_terbit' => 'nullable',
                            'jumlah_halaman' => 'required|integer',
                            'ukuran' => 'required',
                            'kode_buku' => 'required',
                            'kertas_isi' => 'required',
                            'cetak_cover' => 'required',
                            'cetak_isi' => 'required',
                            'rak_id' => 'required',
                            'cover_id' => 'required|mimes:jpeg,png,jpg|max:5000',
                        ],
                        [
                            'judul.required' => 'Masukkan judul.',
                            'judul.string' => 'Masukkan format judul dengan benar.',
                            'deskripsi.required' => 'Masukkan deskripsi.',
                            'deskripsi.string' => 'Masukkan format deskripsi dengan benar.',
                            'kategori_id.required' => 'Masukkan kategori.',
                            'sub_kategori_id.required' => 'Masukkan sub kategori.',
                            'isbn.required' => 'Masukkan ISBN.',
                            'penerbit_id' => 'Masukkan penerbit.',
                            'penulis_id.required' => 'Masukkan penulis.',
                            'jumlah_halaman.required' => 'Masukkan jumlah halaman.',
                            'jumlah_halaman.integer' => 'Masukkan jumlah halaman dengan benar.',
                            'ukuran.required' => 'Masukkan ukuran.',
                            'kode_buku.required' => 'Masukkan kode buku.',
                            'kertas_isi.required' => 'Masukkan kertas isi.',
                            'cetak_cover.required' => 'Masukkan cetak cover.',
                            'cetak_isi.required' => 'Masukkan cetak isi.',
                            'rak_id.required' => 'Masukkan rak.',
                            'cover_id.image' => 'Pilih file cover.',
                            'cover_id.mimes' => 'Format cover tidak valid.',
                            'cover_id.max' => 'Ukuran cover melebihi :max MB.',
                        ]
                    );
                //CLOSE VALIDASI INPUTAN

                $penulises = $request->input('penulis_id', []);
                $idPenulises = [];
                foreach($penulises as $item){
                    if(is_numeric($item)){
                        $cek_penulis = Penulis::where('id',$item)->first();
                        if($cek_penulis == null){
                            $cek_penulis = new Penulis();
                            $cek_penulis->nama = $item;
                            $cek_penulis->is_active = true;
                            $cek_penulis->save();
                        }
                        $id = $cek_penulis->id;
                    }
                    else{
                        $cek_penulis = new Penulis();
                        $cek_penulis->nama = $item;
                        $cek_penulis->is_active = true;
                        $cek_penulis->save();
                        $id = $cek_penulis->id;
                    }
                    $idPenulises[] = $id;
                }

                        // cek penerbit
                if(is_numeric($request->penerbit_id)){
                    $cek_penerbit = Penerbit::where('id',$request->penerbit_id)->first();
                    if($cek_penerbit == null){
                        $cek_penerbit = new Penerbit();
                        $cek_penerbit->namaPenerbit = $request->penerbit_id;
                        $cek_penerbit->is_active = true;
                        $cek_penerbit->save();
                    }
                    $penerbit_id = $cek_penerbit->id;
                }else{
                    $cek_penerbit = new Penerbit();
                    $cek_penerbit->namaPenerbit = $request->penerbit_id;
                    $cek_penerbit->is_active = true;
                    $cek_penerbit->save();
                    $penerbit_id = $cek_penerbit->id;
                }

                // cek rak
                if(is_numeric($request->rak_id)){
                    $cek_rak = Rak::where('id',$request->rak_id)->first();
                    if($cek_rak == null){
                        $cek_rak = new Rak();
                        $cek_rak->kode = $request->rak_id;
                        $cek_rak->is_active = true;
                        $cek_rak->save();
                    }
                    $rak_id = $cek_rak->id;
                }else{
                    $cek_rak = new Rak();
                    $cek_rak->kode = $request->rak_id;
                    $cek_rak->is_active = true;
                    $cek_rak->save();
                    $rak_id = $cek_rak->id;
                }

                $kondisis = $request->input('kondisi', []);
                $hargas = $request->input('harga', []);
                $pengadaans = $request->input('pengadaan', []);
                $is_actives = $request->input('is_active', []);
                $keterangan_is_actives = $request->input('keterangan_is_active', []);
                //Proses Foto Sampul
                $fileCover = $request->file('cover_id');
                $ori_filenamecover = $fileCover->getClientOriginalName();
                $extensionCover = $fileCover->getClientOriginalExtension();
                $judul = strtolower(str_replace(' ', '_', $request->judul));
                $nama_fileCover = $judul . '_' . date('d-m-y') . '.' . $extensionCover;

                $thumbImageCover = Image::make($fileCover->getRealPath());
                $thumbImageCover->resize(398, 572, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                $thumbImageCover->resizeCanvas(398, 572, 'center', false, '#FFFFFF');

                $dokumenCoverBuku = new Dokumen();
                $jenis = strtolower(str_replace(' ', '_', $request->jenis_id));
                $dokumenCoverBuku->filename = $jenis. '_' .$nama_fileCover;
                $dokumenCoverBuku->ori_filename = $ori_filenamecover;
                $dokumenCoverBuku->ekstensi = $extensionCover;
                $dokumenCoverBuku->type = 'cover_buku';
                $dokumenCoverBuku->jenis = 'upload';
                $dokumenCoverBuku->keterangan = null;
                $dokumenCoverBuku->file_path = 'buku/' . $jenis. '_' .$nama_fileCover;
                $dokumenCoverBuku->save();

                // Proses Buku
                $year = date("Y", strtotime($request->tanggal_terbit));
                $delimiters = ['.', '!', '?', ' ', "'", '/', '|', '\\'];
                $str = strtolower($request->judul . ' ' . $request->jenis_id . ' ' . $year);
                $newStr = str_replace($delimiters, $delimiters[0], $str);
                $arr = explode($delimiters[0], $newStr);
                $ex = array_filter($arr);
                $slug = implode("-", $ex);

                $buku = new Buku();
                $buku->slug = $slug;
                $buku->judul = $request->judul;
                $buku->deskripsi = $request->deskripsi;
                $buku->sub_kategori_id = $request->sub_kategori_id;
                $buku->jenis = $request->jenis_id;
                $buku->isbn = $request->isbn;
                $buku->penerbit_id = $penerbit_id;
                $buku->tahun_cetak = $request->tahun_cetak;
                $buku->tanggal_terbit = $request->tanggal_terbit;
                $buku->jumlah_halaman = $request->jumlah_halaman;
                $buku->ukuran = $request->ukuran;
                $buku->kode_buku = $request->kode_buku;
                $buku->kertas_isi = $request->kertas_isi;
                $buku->cetak_cover = $request->cetak_cover;
                $buku->cetak_isi = $request->cetak_isi;
                $buku->raks_id = $rak_id;
                $buku->cover_id = $dokumenCoverBuku->id;
                $buku->is_active = count($is_actives) > 0 ? true : false;
                $buku->save();
                $buku->penulises()->attach($idPenulises);
                //VALUE KODE BUKU
                // $subKategori = SubKategori::find($buku->sub_kategori_id);
                // $penulis = strtoupper(substr($buku->penulises[0]->nama, 0, 3));
                // $penerbit = strtolower(substr($buku->penerbit->namaPenerbit, 0, 1));
                $kode = $buku->kode_buku;
                //PROSES ITEM BUKU
                for ($no = 0; $no < count($pengadaans); $no++) {
                    $urutan = $no+1;
                    $dataPengadaan = [
                        'pengadaan' => $pengadaans[$no],
                    ];
                    $rulesPengadaan = [
                        'pengadaan' => 'required',
                    ];
                    $validatorPengadaan = Validator::make($dataPengadaan, $rulesPengadaan);
                    if ($validatorPengadaan->fails()) {
                        \DB::rollback();
                        return response()->json([
                            'title' => 'Gagal!',
                            'message' => 'Data pengadaan harus diisi.',
                            'messageValidate' => $validatorPengadaan->errors()->getMessageBag()
                        ], 422);
                    }
                    $dataHarga = [
                        'harga' => $hargas[$no],
                    ];
                    $rulesHarga = [
                        'harga' => 'required|numeric',
                    ];
                    $validatorHarga = Validator::make($dataHarga, $rulesHarga);
                    if ($validatorHarga->fails()) {
                        \DB::rollback();
                        return response()->json([
                            'title' => 'Gagal!',
                            'message' => 'Cek kembali harga item buku yang telah diinput.',
                            'messageValidate' => $validatorHarga->errors()->getMessageBag()
                        ], 422);
                    }

                    // cek pengadaan
                    if(is_numeric($pengadaans[$no])){
                        $cek_pengadaan = Pengadaan::where('id',$pengadaans[$no])->first();
                        if($cek_pengadaan == null){
                            $cek_pengadaan = new Pengadaan();
                            $cek_pengadaan->nama = $pengadaans[$no];
                            $cek_pengadaan->is_active = true;
                            $cek_pengadaan->save();
                        }
                        $pengadaan_id = $cek_pengadaan->id;
                    }else{
                        $cek_pengadaan = new Pengadaan();
                        $cek_pengadaan->nama = $pengadaans[$no];
                        $cek_pengadaan->is_active = true;
                        $cek_pengadaan->save();
                        $pengadaan_id = $cek_pengadaan->id;
                    }



                    $itemBuku = new ItemBuku();
                    $itemBuku->bukus_id = $buku->id;
                    $itemBuku->kode_buku = $kode.'-'.$urutan;
                    $itemBuku->kondisi = $kondisis[$no];
                    $itemBuku->harga = $hargas[$no];
                    $itemBuku->pengadaans_id = $pengadaan_id;
                    $itemBuku->is_tersedia = true;
                    $itemBuku->is_active = isset($is_actives[$no]) ? true : false;
                    if (!isset($is_actives[$no])) {
                        $itemBuku->keterangan_is_active = $keterangan_is_actives[$no];
                    }
                    $itemBuku->tanggal_pengadaan = now();
                    $itemBuku->save();
                }

                $thumbImageCover->save(public_path('storage/buku/'. $jenis . '_' . $nama_fileCover));
                activity()->performedOn($buku)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($buku)])->log('Tambah Buku');
                $message = 'Buku telah berhasil dibuat.';
            }
            //Proses Create Buku Digital
            else if ($request->jenis_id == 'Buku Digital') {
                //OPEN VALIDASI INPUTAN
                $validate = $request->validate(
                    [
                        'judul' => 'required|string',
                        'deskripsi' => 'required|string',
                        'kategori_id' => 'required',
                        'sub_kategori_id' => 'required',
                        'isbn' => 'required',
                        'penerbit_id' => 'required',
                        'penulis_id' => 'required',
                        'tanggal_terbit' => 'required',
                        'jumlah_halaman' => 'required|integer',
                        'cover_id' => 'required|mimes:jpeg,png,jpg|max:5000',
                        'file_digital_id' => 'required|mimes:pdf|max:20000',
                    ],
                    [
                        'judul.required' => 'Masukkan judul.',
                        'judul.string' => 'Masukkan format judul dengan benar.',
                        'deskripsi.required' => 'Masukkan deskripsi.',
                        'deskripsi.string' => 'Masukkan format deskripsi dengan benar.',
                        'kategori_id.required' => 'Masukkan kategori.',
                        'sub_kategori_id.required' => 'Masukkan Sub kategori.',
                        'isbn.required' => 'Masukkan ISBN.',
                        'penerbit_id' => 'Masukkan penerbit.',
                        'penulis_id.required' => 'Masukkan penulis.',
                        'tanggal_terbit.required' => 'Masukkan tanggal terbit.',
                        'jumlah_halaman.required' => 'Masukkan jumlah halaman.',
                        'jumlah_halaman.integer' => 'Masukkan jumlah halaman dengan benar.',
                        'cover_id.image' => 'Pilih file cover.',
                        'cover_id.mimes' => 'Format cover tidak valid.',
                        'cover_id.max' => 'Ukuran cover melebihi :max MB.',
                        'file_digital_id.image' => 'Pilih file digital.',
                        'file_digital_id.mimes' => 'Format file digital tidak valid.',
                        'file_digital_id.max' => 'Ukuran file digital melebihi :max MB.',
                    ]
                );
                //CLOSE VALIDASI INPUTAN
                $penulises = $request->input('penulis_id', []);
                $idPenulises = [];
                foreach($penulises as $item){
                    if(is_numeric($item)){
                        $cek_penulis = Penulis::where('id',$item)->first();
                        if($cek_penulis == null){
                            $cek_penulis = new Penulis();
                            $cek_penulis->nama = $item;
                            $cek_penulis->is_active = true;
                            $cek_penulis->save();
                        }
                        $id = $cek_penulis->id;
                    }
                    else{
                        $cek_penulis = new Penulis();
                        $cek_penulis->nama = $item;
                        $cek_penulis->is_active = true;
                        $cek_penulis->save();
                        $id = $cek_penulis->id;
                    }
                    $idPenulises[] = $id;
                }

                // cek penerbit
                if(is_numeric($request->penerbit_id)){
                    $cek_penerbit = Penerbit::where('id',$request->penerbit_id)->first();
                    if($cek_penerbit == null){
                        $cek_penerbit = new Penerbit();
                        $cek_penerbit->namaPenerbit = $request->penerbit_id;
                        $cek_penerbit->is_active = true;
                        $cek_penerbit->save();
                    }
                    $penerbit_id = $cek_penerbit->id;
                }else{
                    $cek_penerbit = new Penerbit();
                    $cek_penerbit->namaPenerbit = $request->penerbit_id;
                    $cek_penerbit->is_active = true;
                    $cek_penerbit->save();
                    $penerbit_id = $cek_penerbit->id;
                }


                //Proses Foto Sampul
                $fileCover = $request->file('cover_id');
                $ori_filenamecover = $fileCover->getClientOriginalName();
                $extensionCover = $fileCover->getClientOriginalExtension();
                $judul = strtolower(str_replace(' ', '_', $request->judul));
                $nama_fileCover = $judul . '_' . date('d-m-y') . '.' . $extensionCover;

                $thumbImageCover = Image::make($fileCover->getRealPath());
                $thumbImageCover->resize(398, 572, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $thumbImageCover->resizeCanvas(398, 572, 'center', false, '#FFFFFF');

                $dokumenCoverBuku = new Dokumen();
                $jenis = strtolower(str_replace(' ', '_', $request->jenis_id));
                $dokumenCoverBuku->filename = $jenis.'_'.$nama_fileCover;
                $dokumenCoverBuku->ori_filename = $ori_filenamecover;
                $dokumenCoverBuku->ekstensi = $extensionCover;
                $dokumenCoverBuku->type = 'cover_buku';
                $dokumenCoverBuku->jenis = 'upload';
                $dokumenCoverBuku->keterangan = null;
                $dokumenCoverBuku->file_path = 'buku/' . $jenis. '_' .$nama_fileCover;
                $dokumenCoverBuku->save();

                //Proses File Digital
                $fileDigital = $request->file('file_digital_id');
                $ori_filenamedigital = $fileDigital->getClientOriginalName();
                $extensiondigital = $fileDigital->getClientOriginalExtension();
                $judul = strtolower(str_replace(' ', '_', $request->judul));
                $nama_filedigital = $judul . '_' . date('d-m-y') . '.' . $extensiondigital;

                $dokumenDigital = new Dokumen();
                $dokumenDigital->filename = $jenis. '_' .$nama_filedigital;
                $dokumenDigital->ori_filename = $ori_filenamedigital;
                $dokumenDigital->ekstensi = $extensiondigital;
                $dokumenDigital->type = 'digital_buku';
                $dokumenDigital->jenis = 'upload';
                $dokumenDigital->keterangan = null;
                $dokumenDigital->file_path = 'digital/' . $jenis. '_' .$nama_filedigital;
                $dokumenDigital->save();

                // Proses Buku
                $year = date("Y", strtotime($request->tanggal_terbit));
                $delimiters = ['.', '!', '?', ' ', "'", '/', '|', '\\'];
                $str = strtolower($request->judul . ' ' . $request->jenis_id . ' ' . $year);
                $newStr = str_replace($delimiters, $delimiters[0], $str);
                $arr = explode($delimiters[0], $newStr);
                $ex = array_filter($arr);
                $slug = implode("-", $ex);
                $bukuDigital = new Buku();
                $bukuDigital->slug = $slug;
                $bukuDigital->judul = $request->judul;
                $bukuDigital->deskripsi = $request->deskripsi;
                $bukuDigital->sub_kategori_id = $request->sub_kategori_id;
                $bukuDigital->jenis = $request->jenis_id;
                $bukuDigital->isbn = $request->isbn;
                $bukuDigital->penerbit_id = $penerbit_id;
                $bukuDigital->tanggal_terbit = $request->tanggal_terbit;
                $bukuDigital->jumlah_halaman = $request->jumlah_halaman;
                $bukuDigital->cover_id = $dokumenCoverBuku->id;
                $bukuDigital->file_digital_id = $dokumenDigital->id;
                $bukuDigital->is_active = $request->is_active ? true : false;
                $bukuDigital->save();
                $bukuDigital->penulises()->attach($idPenulises);

                $thumbImageCover->save(public_path('storage/buku/'. $jenis . '_' . $nama_fileCover));
                $fileDigital->storeAs('digital', $jenis. '_' .$nama_filedigital, 'public');

                activity()->performedOn($bukuDigital)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($bukuDigital)])->log('Tambah Buku Digital');
                $message = 'Buku digital telah berhasil dibuat.';
            }
            //Proses Create Buku Audio
            else if ($request->jenis_id == 'Buku Audio') {
                //OPEN VALIDASI INPUTAN
                $validate = $request->validate(
                    [
                        'judul' => 'required|string',
                        'deskripsi' => 'required|string',
                        'kategori_id' => 'required',
                        'sub_kategori_id' => 'required',
                        'penerbit_id' => 'required',
                        'penulis_id' => 'required',
                        'narator' => 'required|string',
                        'tanggal_terbit' => 'required',
                        'cover_id' => 'required|mimes:jpeg,png,jpg|max:5000',
                        'file_audio_id' => 'required|mimes:mp3,wav',

                    ],
                    [
                        'judul.required' => 'Masukkan judul.',
                        'judul.string' => 'Masukkan format judul dengan benar.',
                        'deskripsi.required' => 'Masukkan deskripsi.',
                        'deskripsi.string' => 'Masukkan format deskripsi dengan benar.',
                        'kategori_id.required' => 'Masukkan kategori.',
                        'sub_kategori_id.required' => 'Masukkan kategori.',
                        'penerbit_id' => 'Masukkan penerbit.',
                        'penulis_id.required' => 'Masukkan penulis.',
                        'narator.required' => 'Masukkan narator.',
                        'narator.string' => 'Masukkan format narator dengan benar.',
                        'tanggal_terbit.required' => 'Masukkan tanggal terbit.',
                        'cover_id.image' => 'Pilih file cover.',
                        'cover_id.mimes' => 'Format cover tidak valid.',
                        'cover_id.max' => 'Ukuran cover melebihi :max MB.',
                        'file_audio_id.required' => 'Pilih file audio.',
                        'file_audio_id.mimes' => 'Format file audio tidak valid.',
                    ]
                );
                //CLOSE VALIDASI INPUTAN
                $penulises = $request->input('penulis_id', []);
                $idPenulises = [];
                foreach($penulises as $item){
                    if(is_numeric($item)){
                        $cek_penulis = Penulis::where('id',$item)->first();
                        if($cek_penulis == null){
                            $cek_penulis = new Penulis();
                            $cek_penulis->nama = $item;
                            $cek_penulis->is_active = true;
                            $cek_penulis->save();
                        }
                        $id = $cek_penulis->id;
                    }
                    else{
                        $cek_penulis = new Penulis();
                        $cek_penulis->nama = $item;
                        $cek_penulis->is_active = true;
                        $cek_penulis->save();
                        $id = $cek_penulis->id;
                    }
                    $idPenulises[] = $id;
                }

                // cek penerbit
                if(is_numeric($request->penerbit_id)){
                    $cek_penerbit = Penerbit::where('id',$request->penerbit_id)->first();
                    if($cek_penerbit == null){
                        $cek_penerbit = new Penerbit();
                        $cek_penerbit->namaPenerbit = $request->penerbit_id;
                        $cek_penerbit->is_active = true;
                        $cek_penerbit->save();
                    }
                    $penerbit_id = $cek_penerbit->id;
                }else{
                    $cek_penerbit = new Penerbit();
                    $cek_penerbit->namaPenerbit = $request->penerbit_id;
                    $cek_penerbit->is_active = true;
                    $cek_penerbit->save();
                    $penerbit_id = $cek_penerbit->id;
                }
                //Proses Foto Sampul
                $fileCover = $request->file('cover_id');
                $ori_filenamecover = $fileCover->getClientOriginalName();
                $extensionCover = $fileCover->getClientOriginalExtension();
                $judul = strtolower(str_replace(' ', '_', $request->judul));
                $nama_fileCover = $judul . '_' . date('d-m-y') . '.' . $extensionCover;

                $thumbImageCover = Image::make($fileCover->getRealPath());
                $thumbImageCover->resize(398, 572, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $thumbImageCover->resizeCanvas(398, 572, 'center', false, '#FFFFFF');

                $dokumenCoverBuku = new Dokumen();
                $jenis = strtolower(str_replace(' ', '_', $request->jenis_id));
                $dokumenCoverBuku->filename =  $jenis . '_' . $nama_fileCover;
                $dokumenCoverBuku->ori_filename = $ori_filenamecover;
                $dokumenCoverBuku->ekstensi = $extensionCover;
                $dokumenCoverBuku->type = 'cover_buku';
                $dokumenCoverBuku->jenis = 'upload';
                $dokumenCoverBuku->keterangan = null;
                $dokumenCoverBuku->file_path = 'buku/' . $jenis. '_' .$nama_fileCover;
                $dokumenCoverBuku->save();

                //Proses File Audio
                $fileAudio = $request->file('file_audio_id');
                $ori_filenameaudio = $fileAudio->getClientOriginalName();
                $extensionaudio = $fileAudio->getClientOriginalExtension();
                $judul = strtolower(str_replace(' ', '_', $request->judul));
                $nama_fileaudio = $judul . '_' . date('d-m-y') . '.' . $extensionaudio;

                $dokumenAudio = new Dokumen();
                $dokumenAudio->filename = $jenis. '_' .$nama_fileaudio;
                $dokumenAudio->ori_filename = $ori_filenameaudio;
                $dokumenAudio->ekstensi = $extensionaudio;
                $dokumenAudio->type = 'audio_buku';
                $dokumenAudio->jenis = 'upload';
                $dokumenAudio->keterangan = null;
                $dokumenAudio->file_path = 'audio/' . $jenis. '_' .$nama_fileaudio;
                $dokumenAudio->save();

                // Proses Buku
                $year = date("Y", strtotime($request->tanggal_terbit));
                $delimiters = ['.', '!', '?', ' ', "'", '/', '|', '\\'];
                $str = strtolower($request->judul . ' ' . $request->jenis_id . ' ' . $year);
                $newStr = str_replace($delimiters, $delimiters[0], $str);
                $arr = explode($delimiters[0], $newStr);
                $ex = array_filter($arr);
                $slug = implode("-", $ex);
                $bukuAudio = new Buku();
                $bukuAudio->slug = $slug;
                $bukuAudio->judul = $request->judul;
                $bukuAudio->deskripsi = $request->deskripsi;
                $bukuAudio->sub_kategori_id = $request->sub_kategori_id;
                $bukuAudio->jenis = $request->jenis_id;
                $bukuAudio->penerbit_id = $penerbit_id;
                $bukuAudio->narator = $request->narator;
                $bukuAudio->tanggal_terbit = $request->tanggal_terbit;
                $bukuAudio->cover_id = $dokumenCoverBuku->id;
                $bukuAudio->file_audio_id = $dokumenAudio->id;
                $bukuAudio->is_active = $request->is_active ? true : false;
                $bukuAudio->save();
                $bukuAudio->penulises()->attach($idPenulises);
                $thumbImageCover->save(public_path('storage/buku/'. $jenis . '_' . $nama_fileCover));
                $fileAudio->storeAs('audio', $jenis. '_' .$nama_fileaudio, 'public');
                activity()->performedOn($bukuAudio)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($bukuAudio)])->log('Tambah Buku Audio');
                $message = 'Buku Audio telah berhasil dibuat.';
            }

            \DB::commit();
            return response()->json([
                'title' => 'Sukses!',
                'message' => $message
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
                'message' => 'Proses pembuatan manajemen buku gagal proses, hubungi Penanggung Jawab Aplikasi' . $e->getMessage()
            ], 500);
        }
    }
    //Modal Edit Manajemen Buku
    public function edit($param)
    {
        $id = decrypt($param);
        $buku = Buku::find($id);

        $kategoris = Kategori::get_dataIsActvie(true)->get();
        $raks = Rak::get_dataIsActvie(true)->get();
        $penulises = Penulis::get_dataIsActvie(true)->get();
        $penerbits = Penerbit::get_dataIsActvie(true)->orderby('namaPenerbit')->get();
        $pengadaans = Pengadaan::get_dataIsActvie(true)->get();
        // $pengadaans = Pengadaan::get_dataIsActvie(true)->get();
        $subkategoris = SubKategori::where('kategori_id',$buku->subKategori->kategori_id)
                                ->where('is_active', true)
                                ->get();
        return view('pages.backend.manajemenbuku.modal.edit', compact('buku', 'kategoris', 'raks', 'penulises', 'penerbits', 'pengadaans','subkategoris'));
    }
    //Proses Edit Manajemen Buku
    public function update(Request $request)
    {
        try {
            \DB::beginTransaction();
            $id = decrypt($request->id);
            $buku = Buku::find($id);
            //Proses Update Buku
            if ($buku->jenis == 'Buku') {
                //OPEN VALIDASI INPUTAN
                    $validate = $request->validate(
                        [
                            'judul' => 'required|string',
                            'deskripsi' => 'required|string',
                            'kategori_id' => 'required',
                            'sub_kategori_id' => 'required',
                            'isbn' => 'required',
                            'penerbit_id' => 'required',
                            'penulis_id' => 'required',
                            'tahun_cetak' => 'nullable',
                            'tanggal_terbit' => 'nullable',
                            'jumlah_halaman' => 'required|integer',
                            'ukuran' => 'required',
                            'kode_buku' => 'required',
                            'kertas_isi' => 'required',
                            'cetak_cover' => 'required',
                            'cetak_isi' => 'required',
                            'rak_id' => 'required',
                            'cover_id' => 'nullable|mimes:jpeg,png,jpg|max:5000',
                        ],
                        [
                            'judul.required' => 'Masukkan judul.',
                            'judul.string' => 'Masukkan format judul dengan benar.',
                            'deskripsi.required' => 'Masukkan deskripsi.',
                            'deskripsi.string' => 'Masukkan format deskripsi dengan benar.',
                            'kategori_id.required' => 'Masukkan kategori.',
                            'sub_kategori_id.required' => 'Masukkan kategori.',
                            'isbn.required' => 'Masukkan ISBN.',
                            'penerbit_id' => 'Masukkan penerbit.',
                            'penulis_id.required' => 'Masukkan penulis.',
                            'tahun_cetak.required' => 'Masukkan tahun cetak.',
                            'tanggal_terbit.required' => 'Masukkan tanggal terbit.',
                            'jumlah_halaman.required' => 'Masukkan jumlah halaman.',
                            'jumlah_halaman.integer' => 'Masukkan jumlah halaman dengan benar.',
                            'ukuran.required' => 'Masukkan ukuran.',
                            'kode_buku.required' => 'Masukkan kode buku.',
                            'kertas_isi.required' => 'Masukkan kertas isi.',
                            'cetak_cover.required' => 'Masukkan cetak cover.',
                            'cetak_isi.required' => 'Masukkan cetak isi.',
                            'rak_id.required' => 'Masukkan rak.',
                            'cover_id.image' => 'Pilih file cover.',
                            'cover_id.mimes' => 'Format cover tidak valid.',
                            'cover_id.max' => 'Ukuran cover melebihi :max MB.',
                        ]
                    );
                //CLOSE VALIDASI INPUTAN
                $penulises = $request->input('penulis_id', []);
                $idPenulises = [];
                foreach($penulises as $item){
                    if(is_numeric($item)){
                        $cek_penulis = Penulis::where('id',$item)->first();
                        if($cek_penulis == null){
                            $cek_penulis = new Penulis();
                            $cek_penulis->nama = $item;
                            $cek_penulis->is_active = true;
                            $cek_penulis->save();
                        }
                        $id = $cek_penulis->id;
                    }
                    else{
                        $cek_penulis = new Penulis();
                        $cek_penulis->nama = $item;
                        $cek_penulis->is_active = true;
                        $cek_penulis->save();
                        $id = $cek_penulis->id;
                    }
                    $idPenulises[] = $id;
                }

                // cek penerbit
                if(is_numeric($request->penerbit_id)){
                    $cek_penerbit = Penerbit::where('id',$request->penerbit_id)->first();
                    if($cek_penerbit == null){
                        $cek_penerbit = new Penerbit();
                        $cek_penerbit->namaPenerbit = $request->penerbit_id;
                        $cek_penerbit->is_active = true;
                        $cek_penerbit->save();
                    }
                    $penerbit_id = $cek_penerbit->id;
                }else{
                    $cek_penerbit = new Penerbit();
                    $cek_penerbit->namaPenerbit = $request->penerbit_id;
                    $cek_penerbit->is_active = true;
                    $cek_penerbit->save();
                    $penerbit_id = $cek_penerbit->id;
                }

                // cek rak
                if(is_numeric($request->rak_id)){
                    $cek_rak = Rak::where('id',$request->rak_id)->first();
                    if($cek_rak == null){
                        $cek_rak = new Rak();
                        $cek_rak->kode = $request->rak_id;
                        $cek_rak->is_active = true;
                        $cek_rak->save();
                    }
                    $rak_id = $cek_rak->id;
                }else{
                    $cek_rak = new Rak();
                    $cek_rak->kode = $request->rak_id;
                    $cek_rak->is_active = true;
                    $cek_rak->save();
                    $rak_id = $cek_rak->id;
                }


                $idItemBuku = $request->input('idItemBuku', []);
                $kondisis = $request->input('kondisi', []);
                $hargas = $request->input('harga', []);
                $pengadaans = $request->input('pengadaan', []);
                $is_actives = $request->input('is_active_value', []);
                $keterangan_is_actives = $request->input('keterangan_is_active', []);
                //Proses Foto Sampul
                if ($request->hasFile('cover_id')) {
                    if (Storage::disk('public')->exists(isset($buku->cover->file_path))) {
                        Storage::disk('public')->delete(isset($buku->cover->file_path));
                    }
                    $fileCover = $request->file('cover_id');
                    $ori_filenamecover = $fileCover->getClientOriginalName();
                    $extensionCover = $fileCover->getClientOriginalExtension();
                    $judul = strtolower(str_replace(' ', '_', $request->judul));
                    $nama_fileCover = $judul . '_' . date('d-m-y') . '.' . $extensionCover;

                    $thumbImageCover = Image::make($fileCover->getRealPath());
                    $thumbImageCover->resize(398, 572, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $thumbImageCover->resizeCanvas(398, 572, 'center', false, '#FFFFFF');

                    $jenis = strtolower(str_replace(' ', '_', $buku->jenis));
                    if(isset($buku->cover->file_path)){
                        $buku->cover->update([
                            'filename' =>  $jenis . '_' . $nama_fileCover,
                            'ori_filename' => $ori_filenamecover,
                            'ekstensi' => $extensionCover,
                            'file_path' => 'buku/' . $jenis. '_' .$nama_fileCover,
                        ]);
                    }else{
                        $dokumenCoverBuku = new Dokumen();
                        $dokumenCoverBuku->filename =  $jenis . '_' . $nama_fileCover;
                        $dokumenCoverBuku->ori_filename = $ori_filenamecover;
                        $dokumenCoverBuku->ekstensi = $extensionCover;
                        $dokumenCoverBuku->type = 'cover_buku';
                        $dokumenCoverBuku->jenis = 'upload';
                        $dokumenCoverBuku->keterangan = null;
                        $dokumenCoverBuku->file_path = 'buku/' . $jenis. '_' .$nama_fileCover;
                        $dokumenCoverBuku->save();
                        $buku->cover_id = $dokumenCoverBuku->id;
                    }
                    $thumbImageCover->save(public_path('storage/buku/'. $jenis . '_' . $nama_fileCover));

                }

                // Proses Buku
                $year = date("Y", strtotime($request->tanggal_terbit));
                $delimiters = ['.', '!', '?', ' ', "'", '/', '|', '\\'];
                $str = strtolower($request->judul . ' ' . $buku->jenis . ' ' . $year);
                $newStr = str_replace($delimiters, $delimiters[0], $str);
                $arr = explode($delimiters[0], $newStr);
                $ex = array_filter($arr);
                $slug = implode("-", $ex);
                $jenis = strtolower(str_replace(' ', '_', $buku->jenis));
                $buku->slug = $slug;
                $buku->judul = $request->judul;
                $buku->deskripsi = $request->deskripsi;
                $buku->isbn = $request->isbn;
                $buku->penerbit_id = $penerbit_id;
                $buku->tahun_cetak = $request->tahun_cetak;
                $buku->tanggal_terbit = $request->tanggal_terbit;
                $buku->jumlah_halaman = $request->jumlah_halaman;
                $buku->ukuran = $request->ukuran;
                $buku->kode_buku = $request->kode_buku;
                $buku->kertas_isi = $request->kertas_isi;
                $buku->cetak_cover = $request->cetak_cover;
                $buku->cetak_isi = $request->cetak_isi;
                $buku->raks_id = $rak_id;
                $countTrue = isset(array_count_values($is_actives)['true']) ? array_count_values($is_actives)['true'] : 0;
                $buku->is_active = $countTrue > 0 ? true : false;
                $buku->save();

                $lastUrutan = ItemBuku::where('bukus_id', $buku->id)
                                        ->latest('kode_buku')
                                        ->pluck('kode_buku')
                                        ->first();
                $lastUrutan = substr(strrchr($lastUrutan, '-'), 1);
                $bukuAvailable = [];
                for ($no = 0; $no < count($idItemBuku); $no++) {

                    $data = [
                        'harga' => $hargas[$no],
                    ];
                    $rules = [
                        'harga' => 'required|numeric',
                    ];
                    $validator = Validator::make($data, $rules);
                    if ($validator->fails()) {
                        \DB::rollback();
                        return response()->json([
                            'title' => 'Gagal!',
                            'message' => 'Cek kembali harga item buku yang telah diubah.',
                            'messageValidate' => $validator->errors()->getMessageBag()
                        ], 422);
                    }

                    // cek pengadaan
                    if(is_numeric($pengadaans[$no])){
                        $cek_pengadaan = Pengadaan::where('id',$pengadaans[$no])->first();
                        if($cek_pengadaan == null){
                            $cek_pengadaan = new Pengadaan();
                            $cek_pengadaan->nama = $pengadaans[$no];
                            $cek_pengadaan->is_active = true;
                            $cek_pengadaan->save();
                        }
                        $pengadaan_id = $cek_pengadaan->id;
                    }else{
                        $cek_pengadaan = new Pengadaan();
                        $cek_pengadaan->nama = $pengadaans[$no];
                        $cek_pengadaan->is_active = true;
                        $cek_pengadaan->save();
                        $pengadaan_id = $cek_pengadaan->id;
                    }


                    if($idItemBuku[$no] != null){
                        $id = decrypt($idItemBuku[$no]);
                        $itemBuku = ItemBuku::find($id);
                        $itemBuku->kondisi = $kondisis[$no];
                        $itemBuku->tanggal_buku_rusak = $kondisis[$no] == 'Rusak' ? now() : null;
                        $itemBuku->harga = $hargas[$no];
                        $itemBuku->pengadaans_id = $pengadaan_id;
                        $is_active =  $is_actives[$no] == 'true' ? true : false;
                        if($itemBuku->is_tersedia == 0 && $is_active == false && $itemBuku->is_active == true){
                            \DB::rollback();
                            return response()->json([
                                'title' => 'Gagal!',
                                'message' => 'Item buku yang diubah sedang dipinjam!',
                                'messageValidate' => $validator->errors()->getMessageBag()
                            ], 422);
                        }
                        else{
                            if($itemBuku->is_active != $is_active){
                                $itemBuku->is_tersedia = $is_active;
                            }
                            $itemBuku->is_active = $is_active;
                            if($is_active == true)
                            {
                                $itemBuku->keterangan_is_active = null;
                            }
                            else if($is_active == false){
                                $itemBuku->keterangan_is_active = $keterangan_is_actives[$no];
                            }
                        }

                        $itemBuku->save();
                    }
                    else if($idItemBuku[$no] == null){
                        // $penulis = strtoupper(substr($buku->penulises[0]->nama, 0, 3));
                        // $penerbit = strtolower(substr($buku->penerbit->namaPenerbit, 0, 1));
                        $kode = $buku->kode_buku;
                        $urutankode = $lastUrutan+1;
                        $itemBuku = new ItemBuku();
                        $itemBuku->bukus_id = $buku->id;
                        $itemBuku->kode_buku = $kode.'-'.$urutankode;
                        $itemBuku->kondisi = $kondisis[$no];
                        $itemBuku->harga = $hargas[$no];
                        $itemBuku->pengadaans_id = $pengadaan_id;
                        $is_active =  $is_actives[$no] == 'true' ? true : false;

                        $itemBuku->is_active = $is_active;
                        $itemBuku->is_tersedia = $is_active;

                        if($is_active == false){
                            $itemBuku->keterangan_is_active = $keterangan_is_actives[$no];
                        }
                        $itemBuku->tanggal_pengadaan = now();
                        $itemBuku->save();
                    }
                    $bukuAvailable[] = $itemBuku->id;
                }

                $itemBukus = ItemBuku::where('bukus_id', $buku->id)->get()->pluck('id');
                $itemBukuDeletes = $itemBukus->diff(collect($bukuAvailable));
                foreach($itemBukuDeletes as $itemBukuDelete){
                    $delete = ItemBuku::find($itemBukuDelete);
                    $delete->delete();
                }

                $buku->penulises()->sync($idPenulises);
                if ($request->hasFile('cover_id')) {
                    $jenis = strtolower(str_replace(' ', '_', $buku->jenis));
                    $thumbImageCover->save(public_path('storage/buku/'. $jenis . '_' . $nama_fileCover));
                }
                activity()->performedOn($buku)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($buku)])->log('Ubah Buku');
                $message = 'Buku telah berhasil diubah.';
            }
            //Proses Update Buku Digital
            else if ($buku->jenis == 'Buku Digital') {
                //OPEN VALIDASI INPUTAN
                $validate = $request->validate(
                    [
                        'judul' => 'required|string',
                        'kategori_id' => 'required',
                        'isbn' => 'required',
                        'penerbit_id' => 'required',
                        'penulis_id' => 'required',
                        'tanggal_terbit' => 'required',
                        'jumlah_halaman' => 'required|integer',
                        'cover_id' => 'nullable|mimes:jpeg,png,jpg|max:5000',
                        'file_digital_id' => 'nullable|mimes:pdf|max:20000',
                    ],
                    [
                        'judul.required' => 'Masukkan judul.',
                        'judul.string' => 'Masukkan format judul dengan benar.',
                        'kategori_id.required' => 'Masukkan kategori.',
                        'isbn.required' => 'Masukkan ISBN.',
                        'penerbit_id' => 'Masukkan penerbit.',
                        'penulis_id.required' => 'Masukkan penulis.',
                        'tanggal_terbit.required' => 'Masukkan tanggal terbit.',
                        'jumlah_halaman.required' => 'Masukkan jumlah halaman.',
                        'jumlah_halaman.integer' => 'Masukkan jumlah halaman dengan benar.',
                        'cover_id.image' => 'Pilih file cover.',
                        'cover_id.mimes' => 'Format cover tidak valid.',
                        'cover_id.max' => 'Ukuran cover melebihi :max MB.',
                        'file_digital_id.image' => 'Pilih file digital.',
                        'file_digital_id.mimes' => 'Format file digital tidak valid.',
                        'file_digital_id.max' => 'Ukuran file digital melebihi :max MB.',
                    ]
                );
                //CLOSE VALIDASI INPUTAN
                $penulises = $request->input('penulis_id', []);
                $idPenulises = [];
                foreach($penulises as $item){
                    if(is_numeric($item)){
                        $cek_penulis = Penulis::where('id',$item)->first();
                        if($cek_penulis == null){
                            $cek_penulis = new Penulis();
                            $cek_penulis->nama = $item;
                            $cek_penulis->is_active = true;
                            $cek_penulis->save();
                        }
                        $id = $cek_penulis->id;
                    }
                    else{
                        $cek_penulis = new Penulis();
                        $cek_penulis->nama = $item;
                        $cek_penulis->is_active = true;
                        $cek_penulis->save();
                        $id = $cek_penulis->id;
                    }
                    $idPenulises[] = $id;
                }

                // cek penerbit
                if(is_numeric($request->penerbit_id)){
                    $cek_penerbit = Penerbit::where('id',$request->penerbit_id)->first();
                    if($cek_penerbit == null){
                        $cek_penerbit = new Penerbit();
                        $cek_penerbit->namaPenerbit = $request->penerbit_id;
                        $cek_penerbit->is_active = true;
                        $cek_penerbit->save();
                    }
                    $penerbit_id = $cek_penerbit->id;
                }else{
                    $cek_penerbit = new Penerbit();
                    $cek_penerbit->namaPenerbit = $request->penerbit_id;
                    $cek_penerbit->is_active = true;
                    $cek_penerbit->save();
                    $penerbit_id = $cek_penerbit->id;
                }


                //Proses Foto Sampul
                if ($request->hasFile('cover_id')) {
                    if (Storage::disk('public')->exists($buku->cover->file_path)) {
                        Storage::disk('public')->delete($buku->cover->file_path);
                    }
                    $fileCover = $request->file('cover_id');
                    $ori_filenamecover = $fileCover->getClientOriginalName();
                    $extensionCover = $fileCover->getClientOriginalExtension();
                    $judul = strtolower(str_replace(' ', '_', $request->judul));
                    $nama_fileCover = $judul . '_' . date('d-m-y') . '.' . $extensionCover;

                    $thumbImageCover = Image::make($fileCover->getRealPath());
                    $thumbImageCover->resize(398, 572, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $thumbImageCover->resizeCanvas(398, 572, 'center', false, '#FFFFFF');

                    $jenis = strtolower(str_replace(' ', '_', $buku->jenis));
                    $buku->cover->update([
                        'filename' =>  $jenis . '_' . $nama_fileCover,
                        'ori_filename' => $ori_filenamecover,
                        'ekstensi' => $extensionCover,
                        'file_path' => 'buku/' . $jenis. '_' .$nama_fileCover,
                    ]);
                }
                //Proses File Digital
                if ($request->hasFile('file_digital_id')) {
                    if (Storage::disk('public')->exists($buku->digital->file_path)) {
                        Storage::disk('public')->delete($buku->digital->file_path);
                    }
                    $fileDigital = $request->file('file_digital_id');
                    $ori_filenamedigital = $fileDigital->getClientOriginalName();
                    $extensiondigital = $fileDigital->getClientOriginalExtension();
                    $judul = strtolower(str_replace(' ', '_', $request->judul));
                    $nama_filedigital = $judul . '_' . date('d-m-y') . '.' . $extensiondigital;
                    $jenis = strtolower(str_replace(' ', '_', $buku->jenis));
                    $buku->digital->update([
                        'filename' => $jenis. '_' .$nama_filedigital,
                        'ori_filename' => $ori_filenamedigital,
                        'ekstensi' => $extensiondigital,
                        'file_path' => 'digital/' . $jenis. '_' .$nama_filedigital,
                    ]);
                }

                // Proses Buku
                $year = date("Y", strtotime($request->tanggal_terbit));
                $delimiters = ['.', '!', '?', ' ', "'", '/', '|', '\\'];
                $str = strtolower($request->judul . ' ' . $buku->jenis . ' ' . $year);
                $newStr = str_replace($delimiters, $delimiters[0], $str);
                $arr = explode($delimiters[0], $newStr);
                $ex = array_filter($arr);
                $slug = implode("-", $ex);
                $buku->slug = $slug;
                $buku->judul = $request->judul;
                $buku->deskripsi = $request->deskripsi;
                $buku->sub_kategori_id = $request->sub_kategori_id;
                $buku->isbn = $request->isbn;
                $buku->penerbit_id = $penerbit_id;
                $buku->tanggal_terbit = $request->tanggal_terbit;
                $buku->jumlah_halaman = $request->jumlah_halaman;
                $buku->is_active = $request->is_active_value == "true" ? 1 : 0;
                $buku->save();
                $buku->penulises()->sync($idPenulises);
                if ($request->hasFile('cover_id')) {
                    $jenis = strtolower(str_replace(' ', '_', $buku->jenis));
                    $thumbImageCover->save(public_path('storage/buku/'. $jenis . '_' . $nama_fileCover));
                }
                if ($request->hasFile('file_digital_id')) {
                    $jenis = strtolower(str_replace(' ', '_', $buku->jenis));
                    $fileDigital->storeAs('digital', $jenis. '_' .$nama_filedigital, 'public');
                }

                activity()->performedOn($buku)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($buku)])->log('Ubah Buku Digital');
                $message = 'Buku digital telah berhasil diubah.';
            }
            //Proses Update Buku Audio
            else if ($buku->jenis == 'Buku Audio') {
                //OPEN VALIDASI INPUTAN
                $validate = $request->validate(
                    [
                        'judul' => 'required|string',
                        'deskripsi' => 'required|string',
                        'kategori_id' => 'required',
                        'sub_kategori_id' => 'required',
                        'penerbit_id' => 'required',
                        'penulis_id' => 'required',
                        'narator' => 'required|string',
                        'tanggal_terbit' => 'required',
                        'cover_id' => 'nullable|mimes:jpeg,png,jpg|max:5000',
                        'file_audio_id' => 'nullable|mimes:mp3,wav',

                    ],
                    [
                        'judul.required' => 'Masukkan judul.',
                        'judul.string' => 'Masukkan format judul dengan benar.',
                        'deskripsi.required' => 'Masukkan deskripsi.',
                        'deskripsi.string' => 'Masukkan format deskripsi dengan benar.',
                        'kategori_id.required' => 'Masukkan kategori.',
                        'sub_kategori_id.required' => 'Masukkan sub kategori.',
                        'penerbit_id' => 'Masukkan penerbit.',
                        'penulis_id.required' => 'Masukkan penulis.',
                        'narator.required' => 'Masukkan narator.',
                        'narator.string' => 'Masukkan format narator dengan benar.',
                        'tanggal_terbit.required' => 'Masukkan tanggal terbit.',
                        'cover_id.image' => 'Pilih file cover.',
                        'cover_id.mimes' => 'Format cover tidak valid.',
                        'cover_id.max' => 'Ukuran cover melebihi :max MB.',
                        'file_audio_id.mimes' => 'Format file audio tidak valid.',
                    ]
                );
                //CLOSE VALIDASI INPUTAN
                $penulises = $request->input('penulis_id', []);
                $idPenulises = [];
                foreach($penulises as $item){
                    if(is_numeric($item)){
                        $cek_penulis = Penulis::where('id',$item)->first();
                        if($cek_penulis == null){
                            $cek_penulis = new Penulis();
                            $cek_penulis->nama = $item;
                            $cek_penulis->is_active = true;
                            $cek_penulis->save();
                        }
                        $id = $cek_penulis->id;
                    }
                    else{
                        $cek_penulis = new Penulis();
                        $cek_penulis->nama = $item;
                        $cek_penulis->is_active = true;
                        $cek_penulis->save();
                        $id = $cek_penulis->id;
                    }
                    $idPenulises[] = $id;
                }

                // cek penerbit
                if(is_numeric($request->penerbit_id)){
                    $cek_penerbit = Penerbit::where('id',$request->penerbit_id)->first();
                    if($cek_penerbit == null){
                        $cek_penerbit = new Penerbit();
                        $cek_penerbit->namaPenerbit = $request->penerbit_id;
                        $cek_penerbit->is_active = true;
                        $cek_penerbit->save();
                    }
                    $penerbit_id = $cek_penerbit->id;
                }else{
                    $cek_penerbit = new Penerbit();
                    $cek_penerbit->namaPenerbit = $request->penerbit_id;
                    $cek_penerbit->is_active = true;
                    $cek_penerbit->save();
                    $penerbit_id = $cek_penerbit->id;
                }


                //Proses Foto Sampul
                if ($request->hasFile('cover_id')) {
                    if (Storage::disk('public')->exists($buku->cover->file_path)) {
                        Storage::disk('public')->delete($buku->cover->file_path);
                    }
                    $fileCover = $request->file('cover_id');
                    $ori_filenamecover = $fileCover->getClientOriginalName();
                    $extensionCover = $fileCover->getClientOriginalExtension();
                    $judul = strtolower(str_replace(' ', '_', $request->judul));
                    $nama_fileCover = $judul . '_' . date('d-m-y') . '.' . $extensionCover;

                    $thumbImageCover = Image::make($fileCover->getRealPath());
                    $thumbImageCover->resize(398, 572, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $thumbImageCover->resizeCanvas(398, 572, 'center', false, '#FFFFFF');

                    $jenis = strtolower(str_replace(' ', '_', $buku->jenis));
                    $buku->cover->update([
                        'filename' =>  $jenis . '_' . $nama_fileCover,
                        'ori_filename' => $ori_filenamecover,
                        'ekstensi' => $extensionCover,
                        'file_path' => 'buku/' . $jenis. '_' .$nama_fileCover,
                    ]);
                }

                //Proses File Audio
                if ($request->hasFile('file_audio_id')) {
                    if (Storage::disk('public')->exists($buku->audio->file_path)) {
                        Storage::disk('public')->delete($buku->audio->file_path);
                    }
                    $fileAudio = $request->file('file_audio_id');
                    $ori_filenameaudio = $fileAudio->getClientOriginalName();
                    $extensionaudio = $fileAudio->getClientOriginalExtension();
                    $judul = strtolower(str_replace(' ', '_', $request->judul));
                    $nama_fileaudio = $judul . '_' . date('d-m-y') . '.' . $extensionaudio;
                    $jenis = strtolower(str_replace(' ', '_', $buku->jenis));
                    $buku->audio->update([
                        'filename' => $jenis. '_' .$nama_fileaudio,
                        'ori_filename' => $ori_filenameaudio,
                        'ekstensi' => $extensionaudio,
                        'file_path' => 'audio/' . $jenis. '_' .$nama_fileaudio,
                    ]);
                }

                // Proses Buku
                $year = date("Y", strtotime($request->tanggal_terbit));
                $delimiters = ['.', '!', '?', ' ', "'", '/', '|', '\\'];
                $str = strtolower($request->judul . ' ' . $buku->jenis . ' ' . $year);
                $newStr = str_replace($delimiters, $delimiters[0], $str);
                $arr = explode($delimiters[0], $newStr);
                $ex = array_filter($arr);
                $slug = implode("-", $ex);

                $buku->slug = $slug;
                $buku->judul = $request->judul;
                $buku->deskripsi = $request->deskripsi;
                $buku->sub_kategori_id = $request->sub_kategori_id;
                $buku->penerbit_id = $penerbit_id;
                $buku->narator = $request->narator;
                $buku->tanggal_terbit = $request->tanggal_terbit;
                $buku->is_active = $request->is_active_value == "true" ? 1 : 0;
                $buku->save();
                $buku->penulises()->sync($idPenulises);
                if ($request->hasFile('cover_id')) {
                    $jenis = strtolower(str_replace(' ', '_', $buku->jenis));
                    $thumbImageCover->save(public_path('storage/buku/'. $jenis . '_' . $nama_fileCover));
                }
                if ($request->hasFile('file_audio_id')) {
                    $jenis = strtolower(str_replace(' ', '_', $buku->jenis));
                    $fileAudio->storeAs('audio', $jenis. '_' .$nama_fileaudio, 'public');
                }
                activity()->performedOn($buku)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($buku)])->log('Ubah Buku Audio');
                $message = 'Buku Audio telah berhasil diubah.';
            }

            \DB::commit();
            return response()->json([
                'title' => 'Sukses!',
                'message' => $message
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
                'message' => 'Proses pembuatan manajemen buku gagal proses, hubungi Penanggung Jawab Aplikasi' . $e->getMessage()
            ], 500);
        }
    }

    public function show_buku($id)
    {
        $buku = Buku::with('digital')->find(decrypt($id));
        return view('pages.frontend.buku.index', compact('buku'));
    }
}
