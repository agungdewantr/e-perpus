<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penulis;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class PenulisController extends Controller
{
    public function index()
    {
        $penulises = Penulis::all();
        return view('pages.backend.penulis.index',compact('penulises'));
    }
    //Modal Create Penulis
    public function create(){
        return view('pages.backend.penulis.modal.create');
    }
    //Modal Edit Penulis
    public function edit($param){
        $penulis = Penulis::find($param);
        return view('pages.backend.penulis.modal.edit', compact('penulis'));
    }
    //Proses Create Penulis
    public function store(Request $request){
        try {
            \DB::beginTransaction();
            //OPEN VALIDASI INPUTAN
                $validate = $request->validate([
                    'nama' => 'required|string',
                ],
                [
                    'nama.string' => 'Format nama penulis harus string.',
                ]);
            //CLOSE VALIDASI INPUTAN
            //OPEN CREATE PENULIS
                $penulis = new Penulis();
                // $penulis->id = Penulis::get_id()+1;
                $penulis->nama = $validate['nama'];
                $penulis->is_active = $request->isActive?true:false;
                $penulis->save();
            //CLOSE CREATE PENULIS

            activity()->performedOn($penulis)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($penulis)])->log('Tambah Penulis');

            \DB::commit();
            return response()->json([
                'title' => 'Sukses!',
                'message' => 'Penulis berhasil ditambah.'
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
    //Proses Edit Penulis
    public function update(Request $request){
        try {
            \DB::beginTransaction();
            //OPEN VALIDASI INPUTAN
                $validate = $request->validate([
                    'nama' => 'required|string',
                ],
                [
                    'nama.string' => 'Format nama harus string.',
                ]);
            //CLOSE VALIDASI INPUTAN
                $id = decrypt($request->id);
            //OPEN EDIT PENULIS
                $penulis = Penulis::find($id);
                $penulis->nama = $validate['nama'];
                $penulis->is_active = $request->isActive?true:false;
                $penulis->save();
            //CLOSE EDIT PENULIS

            activity()->performedOn($penulis)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($penulis)])->log('Ubah Penulis');

            \DB::commit();
            return response()->json([
                'title' => 'Sukses!',
                'message' => 'Penulis berhasil diubah.'
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
                'message' => 'Penulis gagal diubah.'. $e->getMessage()
            ], 500);
        }
    }
    //Proses Delete Penulis
    public function delete($param)
    {
        try {
            \DB::beginTransaction();
            $id = decrypt($param);
            $penulis = Penulis::find($id);
            $penulisHapus = $penulis;
            $penulis->delete();
            activity()->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($penulisHapus)])->log('Hapus Penulis');

            \DB::commit();
            return response()->json([
                                        'title'=> 'Sukses!' ,
                                        'message' => 'Penulis telah dihapus.'
                                    ], 200);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                                        'title' => 'Gagal!',
                                        'message' => 'Penulis gagal dihapus.'. $e->getMessage()
                                    ], 500);
        }
    }
}
