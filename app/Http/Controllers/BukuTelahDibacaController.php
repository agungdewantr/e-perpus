<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\BukuTelahDibaca;
use App\Models\Notifikasi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PhpOffice\PhpSpreadsheet\IOFactory;

class BukuTelahDibacaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.backend.bukutelahdibaca.index');
    }

    public function datatable(Request $request){
        $filter_bulan = $request->input('filter_bulan');

        $query = BukuTelahDibaca::leftjoin('bukus', 'bukus.id','=','buku_telah_dibacas.bukus_id')
                                                        ->orderby('buku_telah_dibacas.created_at', 'desc');
                                                        if ($filter_bulan != null) {
                                                            $date = Carbon::parse($filter_bulan);
                                                            $query->whereMonth('tanggal', $date->month)
                                                                    ->whereYear('tanggal', $date->year);
                                                        }
         $bukutelahdibacas =  $query->select('buku_telah_dibacas.*', 'bukus.judul')
                                                        ->get();
        return datatables()->of($bukutelahdibacas)
                            ->addColumn('buku', function ($row) {
                                $value= $row->judul;
                                return $value;
                            })
                            ->addColumn('id', function ($row) {
                                $id = encrypt($row->id);
                                return $id;
                            })
                            ->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $bukus = Buku::get_dataIsActive(1)->where('jenis','Buku')->get();
        return view('pages.backend.bukutelahdibaca.modal.create', compact('bukus'));
    }

    public function import(){
        return view('pages.backend.bukutelahdibaca.modal.import');
    }

    public function store(Request $request)
    {
        try {
            \DB::beginTransaction();
            foreach($request->bukus_id as $key => $value){
                $newBukuDibaca = new BukuTelahDibaca();
                $newBukuDibaca->bukus_id = $request->bukus_id[$key];
                $newBukuDibaca->jumlah = $request->jumlah[$key];
                $newBukuDibaca->tanggal = $request->tanggal;
                $newBukuDibaca->save();
                activity()->performedOn($newBukuDibaca)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($newBukuDibaca)])->log('Tambah buku telah dibaca');
            }
            \DB::commit();
            return response()->json([
                'title' => 'Sukses!',
                'message' => 'Buku telah dibaca telah berhasil dibuat.'
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
                'message' => 'Proses menambahkan buku telah dibaca gagal proses, hubungi Penanggung Jawab Aplikasi'. $e->getMessage()
            ], 500);
        }
    }

    public function cekJumlahBuku(Request $request)
    {
        $bukuId = $request->input('bukus_id');
        $jumlahInput = $request->input('jumlah');

        // Ambil data buku dari database berdasarkan bukus_id
        $buku = Buku::get_stok($bukuId);

        // Lakukan pengecekan
        if ($buku) {
            if ($jumlahInput > $buku) {
                return response()->json(['error' => 'Jumlah buku melebihi yang tersedia.']);
            } else {
                return response()->json(['success' => 'Jumlah buku valid.']);
            }
        } else {
            return response()->json(['error' => 'Buku tidak ditemukan.']);
        }
    }

    public function downloadtemplate ()
    {
        $filePath = public_path('template/template_buku_telah_dibaca.xlsx');

        if (file_exists($filePath)) {
            $contents = file_get_contents($filePath);
            return (new Response($contents, 200))
                ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                ->header('Content-Disposition', 'attachment; filename="template_buku_telah_dibaca.xlsx"');
        } else {
            abort(404); // File not found
        }
    }


    public function edit($param){
        $id = decrypt($param);
        $bukutelahdibaca = BukuTelahDibaca::find($id);
        $bukus = Buku::get_dataIsActive(1)->where('jenis','Buku')->get();

        return view('pages.backend.bukutelahdibaca.modal.edit', compact('bukutelahdibaca','bukus'));
    }


    public function update(Request $request)
    {
        try {
            \DB::beginTransaction();
            //OPEN VALIDASI INPUTAN
                $validate = $request->validate([
                    'jumlah' => 'required|numeric',
                    'bukus_id' => 'required|numeric',
                    'tanggal' => 'required|date',
                ],
                [
                    'jumlah.numeric' => 'Jumlah harus sesuai format.',
                    'tanggal.date' => 'Tanggal harus sesuai format.'
                ]);
            //CLOSE VALIDASI INPUTAN

            //Proses Kunjungan Perpustakan
            $id = decrypt($request->id);
            $bukutelahdibaca = BukuTelahDibaca::find($id);
            $bukutelahdibaca->tanggal = $request->tanggal;
            $bukutelahdibaca->bukus_id = $request->bukus_id;
            $bukutelahdibaca->jumlah = $request->jumlah;
            $bukutelahdibaca->save();

            activity()->performedOn($bukutelahdibaca)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($bukutelahdibaca)])->log('Ubah Buku Telah Dibaca');

            \DB::commit();
            return response()->json([
                'title' => 'Sukses!',
                'message' => 'Buku telah dibaca telah berhasil diubah.'
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
                'message' => 'Proses ubah buku telah dibaca gagal proses, hubungi Penanggung Jawab Aplikasi'. $e->getMessage()
            ], 500);
        }
    }

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
            $templateFile = public_path('template/template_buku_telah_dibaca.xlsx');

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
            // dd($dataAll);
            foreach($dataAll as $d){
                // ambil data buku berdasarkan kode buku
                $buku = Buku::where('kode_buku', $d['Kode Buku (100.1/RED/p)'])->first();
                if($buku){
                    $bukus_id = $buku->id;
                }else{
                    return response()->json(['status' => 'error', 'messages' => 'Buku dengan kode buku : '.$d['Kode Buku (100.1/RED/p)']. ' tidak ada di database'], 500);
                }

                $bukutelahdibaca = new BukuTelahDibaca();
                $bukutelahdibaca->tanggal =  Carbon::createFromFormat('d/m/Y', $d['Tanggal (d/m/yyyy)'])->toDateString();
                $bukutelahdibaca->jumlah = $d['Jumlah'];
                $bukutelahdibaca->bukus_id = $bukus_id;
                $bukutelahdibaca->save();
            }

            $status_data = 'Import Buku Telah Dibaca';
            $tentang = "Import Buku Telah Dibaca";
            $isi = auth()->user()->nama." telah mengimport buku telah dibaca";
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
