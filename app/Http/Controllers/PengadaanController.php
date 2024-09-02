<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengadaan;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class PengadaanController extends Controller
{
    public function index(){
        $pengadaans = Pengadaan::all();
        return view('pages.backend.pengadaan.index', compact('pengadaans'));
    }
    //Modal Create Pengadaan
    public function create(){
        return view('pages.backend.pengadaan.modal.create');
    }
    //Proses Create Pengadaan
    public function store(Request $request){
        try {
            \DB::beginTransaction();
            //OPEN VALIDASI INPUTAN
                $validate = $request->validate([
                    'nama' => 'required|string',
                    'keterangan' => 'required|string',
                ],
                [
                    'nama.string' => 'Format pengadaan harus string.',
                    'keterangan.string' => 'Format keterangan harus string.',
                ]);
            //CLOSE VALIDASI INPUTAN
            //OPEN CREATE RAK
                $pengadaan = new Pengadaan();
                $pengadaan->nama = $validate['nama'];
                $pengadaan->keterangan = $validate['keterangan'];
                $pengadaan->is_active = $request->isActive?true:false;
                $pengadaan->save();
            //CLOSE CREATE RAK

            activity()->performedOn($pengadaan)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($pengadaan)])->log('Tambah Pengadaan');

            \DB::commit();
            return response()->json([
                'title' => 'Sukses!',
                'message' => 'Pengadaan berhasil ditambah.'
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
                'message' => 'Data gagal proses, '. $e->getMessage()
            ], 500);
        }
    }
    //Modal Edit Pengadaan
    public function edit($param){
        $pengadaan = Pengadaan::find($param);
        return view('pages.backend.pengadaan.modal.edit', compact('pengadaan'));
    }
    //Proses Edit Pengadaan
    public function update(Request $request){
        try {
            \DB::beginTransaction();
            //OPEN VALIDASI INPUTAN
                $validate = $request->validate([
                    'nama' => 'required|string',
                    'keterangan' => 'required|string',
                ],
                [
                    'nama.string' => 'Format pengadaan harus string.',
                    'keterangan.string' => 'Format keterangan harus string.',
                ]);
            //CLOSE VALIDASI INPUTAN
                $id = decrypt($request->id);
            //OPEN EDIT RAK
                $pengadaan = Pengadaan::find($id);
                $pengadaan->nama = $validate['nama'];
                $pengadaan->keterangan = $validate['keterangan'];
                $pengadaan->is_active = $request->isActive?true:false;
                $pengadaan->save();
            //CLOSE EDIT RAK

            activity()->performedOn($pengadaan)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($pengadaan)])->log('Ubah Pengadaan');

            \DB::commit();
            return response()->json([
                'title' => 'Sukses!',
                'message' => 'Pengadaan berhasil diubah.'
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
                'message' => 'Pengadaan gagal diubah.'. $e->getMessage()
            ], 500);
        }
    }
    //Proses Delete Pengadaan
    public function delete($param)
    {
        try {
            \DB::beginTransaction();
            $id = decrypt($param);
            $pengadaan = Pengadaan::find($id);
            $pengadaanHapus = $pengadaan;
            $pengadaan->delete();
            activity()->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($pengadaanHapus)])->log('Hapus Pengadaan');

            \DB::commit();
            return response()->json([
                                        'title'=> 'Sukses!' ,
                                        'message' => 'Pengadaan telah dihapus.'
                                    ], 200);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                                        'title' => 'Gagal!',
                                        'message' => 'Pengadaan gagal dihapus.'. $e->getMessage()
                                    ], 500);
        }
    }
}
