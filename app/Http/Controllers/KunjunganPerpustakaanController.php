<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KunjunganPerpustakaan;
use App\Models\ProfilAnggota;
use Carbon\Carbon;
use Illuminate\Http\Response;
use PhpOffice\PhpSpreadsheet\IOFactory;

class KunjunganPerpustakaanController extends Controller
{
    public function index(){

        return view('pages.backend.kunjunganperpustakaan.index');
    }
    //Datatable menampilkan kunjungan perpustakan
    public function datatable(Request $request){
        $filter_bulan = $request->input('filter_bulan');

        $query = KunjunganPerpustakaan::leftjoin('profil_anggotas', 'profil_anggotas.id','=','kunjungan_perpustakaans.profil_anggota_id')
                                                        ->orderby('kunjungan_perpustakaans.created_at', 'desc');
                                                        if ($filter_bulan != null) {
                                                            $date = Carbon::parse($filter_bulan);
                                                            $query->whereMonth('tanggal_kunjungan', $date->month)
                                                                    ->whereYear('tanggal_kunjungan', $date->year);
                                                        }
         $kunjunganPerpustakaans =  $query->select('profil_anggotas.nomor_anggota','kunjungan_perpustakaans.nama_lengkap as knama_lengkap','profil_anggotas.nama_lengkap as pnama_lengkap','kunjungan_perpustakaans.tanggal_kunjungan','kunjungan_perpustakaans.keperluan','kunjungan_perpustakaans.id','kunjungan_perpustakaans.jenis_kelamin')
                                                        ->get();
        return datatables()->of($kunjunganPerpustakaans)
                            ->addColumn('nama_lengkap', function ($row) {
                                if($row->nomor_anggota != null){
                                    $value= $row->pnama_lengkap;
                                }
                                else{
                                    $value= $row->knama_lengkap;
                                }
                                return $value;
                            })
                            ->addColumn('id', function ($row) {
                                $id = encrypt($row->id);
                                return $id;
                            })
                            ->make();
    }
    //Modal Create Kunjungan Perpustakan
    public function create(){
        $anggotas = ProfilAnggota::get_dataIsActvie(1)->get();

        return view('pages.backend.kunjunganperpustakaan.modal.create', compact('anggotas'));
    }
    //Proses Create Kunjungan Perpustakan
    public function store(Request $request){
        try {
            \DB::beginTransaction();
            //OPEN VALIDASI INPUTAN
                $validate = $request->validate([
                    'keperluan' => 'required|string',
                ],
                [
                    'keperluan.string' => 'Masukkan format keperluan dengan benar.'
                ]);
            //CLOSE VALIDASI INPUTAN

            //Proses Kunjungan Perpustakan
            $kunjunganPerpustakaan = new KunjunganPerpustakaan();
            $kunjunganPerpustakaan->tanggal_kunjungan = $request->tanggal_kunjungan;
            if($request->is_anggotaPerpustakaan == 'iya'){
                $kunjunganPerpustakaan->profil_anggota_id = $request->profil_anggota_id;
                $kunjunganPerpustakaan->jenis_kelamin = $request->jenis_kelaminAnggota;
            }
            else{
                $kunjunganPerpustakaan->nama_lengkap = $request->nama_lengkap;
                $kunjunganPerpustakaan->jenis_kelamin = $request->jenis_kelaminNonAnggota;
            }
            $kunjunganPerpustakaan->petugas_pj_kunjungan_perpustakaan_id = auth()->user()->profilPetugas->id;
            $kunjunganPerpustakaan->keperluan = $request->keperluan;
            $kunjunganPerpustakaan->save();

            activity()->performedOn($kunjunganPerpustakaan)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($kunjunganPerpustakaan)])->log('Tambah Kunjungan Perpustakaan');

            \DB::commit();
            return response()->json([
                'title' => 'Sukses!',
                'message' => 'Kunjungan perpustakaan telah berhasil dibuat.'
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
                'message' => 'Proses menambahkan kunjungan perpustakaan gagal proses, hubungi Penanggung Jawab Aplikasi'. $e->getMessage()
            ], 500);
        }
    }
    //Modal Edit Kunjungan Perpustakaan
    public function edit($param){
        $id = decrypt($param);
        $kunjunganPerpustakaan = KunjunganPerpustakaan::find($id);

        return view('pages.backend.kunjunganperpustakaan.modal.edit', compact('kunjunganPerpustakaan'));
    }
    //Proses Update Kunjungan Perpustakan
    public function update(Request $request){
        try {
            \DB::beginTransaction();
            //OPEN VALIDASI INPUTAN
                $validate = $request->validate([
                    'keperluan' => 'required|string',
                ],
                [
                    'keperluan.string' => 'Masukkan format keperluan dengan benar.'
                ]);
            //CLOSE VALIDASI INPUTAN

            //Proses Kunjungan Perpustakan
            $id = decrypt($request->id);
            $kunjunganPerpustakaan = KunjunganPerpustakaan::find($id);
            $kunjunganPerpustakaan->jenis_kelamin = $request->jenis_kelamin;
            $kunjunganPerpustakaan->tanggal_kunjungan = $request->tanggal_kunjungan;
            $kunjunganPerpustakaan->keperluan = $request->keperluan;
            $kunjunganPerpustakaan->save();

            activity()->performedOn($kunjunganPerpustakaan)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($kunjunganPerpustakaan)])->log('Ubah Kunjungan Perpustakaan');

            \DB::commit();
            return response()->json([
                'title' => 'Sukses!',
                'message' => 'Kunjungan perpustakaan telah berhasil diubah.'
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
                'message' => 'Proses ubah kunjungan perpustakaan gagal proses, hubungi Penanggung Jawab Aplikasi'. $e->getMessage()
            ], 500);
        }
    }
    // Modal Import
    public function import(){
        return view('pages.backend.kunjunganperpustakaan.modal.import');
    }
    public function getjeniskelamin($param){
        $data = ProfilAnggota::find($param);

        return  $data;
    }
    //download
    public function downloadtemplate ()
    {
        $filePath = public_path('template/template_kunjungan_tamu.xlsx');

        if (file_exists($filePath)) {
            $contents = file_get_contents($filePath);
            return (new Response($contents, 200))
                ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                ->header('Content-Disposition', 'attachment; filename="template_kunjungan_tamu.xlsx"');
        } else {
            abort(404); // File not found
        }
    }
    // Proses import kunjungan perpustakaan
    public function importexcel(Request $request){
        try {
            \DB::beginTransaction();
            $file = $request->file('file');
            if (!$request->hasFile('file'))
            {
                return response()->json(['status' => 'error', 'messages' => 'Masukkan Berkas Terlebih Dahulu'], 500);
            }
            if ($file->getMimeType() != 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
                return response()->json(['status' => 'error', 'messages' => 'Format File Tidak Sesuai'], 500);
            }
            //Mencari file path Excel template
            $templateFile = public_path('template/template_kunjungan_tamu.xlsx');

            // template excel
            $templateSpreadsheet = IOFactory::load($templateFile);
            $templateWorksheet = $templateSpreadsheet->getActiveSheet();
            $rowIterator = $templateWorksheet->getRowIterator();
            $rowIterator->next();
            $templateRow = $rowIterator->current();
            $templateData = [];
            foreach ($templateRow->getCellIterator() as $cell) {
                $templateData[] = $cell->getValue();
            }
            //inputan excel
            $file = $request->file('file');
            $fileSpreadsheet = IOFactory::load($file);
            $fileWorksheet = $fileSpreadsheet->getActiveSheet();
            $fileHighestRow = $fileWorksheet->getHighestRow();
            $fileHighestColumn = $fileWorksheet->getHighestColumn();
            $colsName = array();
            for ($col = 'A'; $col <= $fileHighestColumn; $col++) {
                $colsName[] =  $fileWorksheet->getCell($col . 2)->getValue();
            }
            $dataAll = array();
            for ($row = 3; $row <= $fileHighestRow; $row++) {
                $dataRow = array();
                $i = 0;
                for ($col = 'A'; $col <= $fileHighestColumn; $col++) {
                    $dataRow += array($colsName[$i] => $fileWorksheet->getCell($col . $row)->getFormattedValue());
                    $i++;
                }
                $dataAll[] = $dataRow;
            }
            //Data JSON
            $fileDataJson = json_encode($dataAll);

            //Mengambil row atas sendiri
            $fileData = array_keys($dataAll[0]);
            $pengecekan = array_diff_key($templateData, $fileData);
            if ($pengecekan != NULL) {
                return response()->json(['status' => 'error', 'messages' => 'Format dataset berbeda'], 500);
            }
            foreach($dataAll as $d){
                if(strtolower($d['Jenis Kelamin (L/P)']) == 'l'){
                    $jenisKelamin = 'laki-laki';
                }
                else if(strtolower($d['Jenis Kelamin (L/P)']) == 'p'){
                    $jenisKelamin = 'perempuan';
                }else{
                    return response()->json(['status' => 'error', 'messages' => 'Cek kembali data jenis kelamin sesuai dengan format template.'], 500);
                }
                if($d['Nomor Anggota'] != '')
                {
                    $profilAnggota = ProfilAnggota::where('nomor_anggota', $d['Nomor Anggota'])->first();
                    //PENGECEKAN JIKA TIDAK DITEMUKAN NOMOR ANGGOTA
                    if($profilAnggota == NULL)//tidak ditemukan
                    {
                        $kunjunganPerpustakaan = new KunjunganPerpustakaan();
                        $kunjunganPerpustakaan->tanggal_kunjungan = Carbon::createFromFormat('d/m/Y', $d['Tanggal (d/m/yyyy)'])->toDateString();
                        $kunjunganPerpustakaan->petugas_pj_kunjungan_perpustakaan_id = auth()->user()->profilPetugas->id;
                        $kunjunganPerpustakaan->nama_lengkap = $d['Nama Lengkap'];
                        $kunjunganPerpustakaan->jenis_kelamin = $jenisKelamin;
                        $kunjunganPerpustakaan->keperluan = $d['Keperluan'];
                        $kunjunganPerpustakaan->save();
                    }
                    else//ditemukan
                    {
                        $kunjunganPerpustakaan = new KunjunganPerpustakaan();
                        $kunjunganPerpustakaan->tanggal_kunjungan = Carbon::createFromFormat('d/m/Y', $d['Tanggal (d/m/yyyy)'])->toDateString();
                        $kunjunganPerpustakaan->petugas_pj_kunjungan_perpustakaan_id = auth()->user()->profilPetugas->id;
                        $kunjunganPerpustakaan->profil_anggota_id = $profilAnggota->id;
                        $kunjunganPerpustakaan->jenis_kelamin = $jenisKelamin;
                        $kunjunganPerpustakaan->keperluan = $d['Keperluan'];
                        $kunjunganPerpustakaan->save();
                    }
                }
                else{
                    $kunjunganPerpustakaan = new KunjunganPerpustakaan();
                    $kunjunganPerpustakaan->tanggal_kunjungan = Carbon::createFromFormat('d/m/Y', $d['Tanggal (d/m/yyyy)'])->toDateString();
                    $kunjunganPerpustakaan->petugas_pj_kunjungan_perpustakaan_id = auth()->user()->profilPetugas->id;
                    $kunjunganPerpustakaan->nama_lengkap = $d['Nama Lengkap'];
                    $kunjunganPerpustakaan->jenis_kelamin = $jenisKelamin;
                    $kunjunganPerpustakaan->keperluan = $d['Keperluan'];
                    $kunjunganPerpustakaan->save();
                }
            }
            $status_data = 'Import Kunjungan Perpustakaan';
            $tentang = "Import Kunjungan Perpustakaan";
            $isi = auth()->user()->nama." telah mengimport import kunjungan perpustakaan";
            // $notifikasi = Notifikasi::inserNotifikasi(auth()->user()->id, 2, 0, $status_data, $tentang, $isi, null);

            activity()->withProperties(['ip' => $this->get_client_ip(), 'data' => 'Mengimport '.count($dataAll).' data kunjungan perpustakaan'])->log('Mengimport Kunjungan Perpustakaan');
            \DB::commit();
            return response()->json(['status' => 'success','title' => 'Berhasil!' ,'messages' => 'Berkas Kunjungan Perpustakaan Berhasil di Import'], 201);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['status' => 'error', 'messages' => 'Pastikan file berkas diisi sesuai dengan benar.'. $e->getMessage()], 500);
        }
    }
}
