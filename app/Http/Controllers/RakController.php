<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rak;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class RakController extends Controller
{
    public function index()
    {
        $raks = Rak::all();
        return view('pages.backend.rak.index',compact('raks'));
    }
    //Modal Create Rak
    public function create(){
        return view('pages.backend.rak.modal.create');
    }
    //Proses Create Rak
    public function store(Request $request){
        try {
            \DB::beginTransaction();
            //OPEN VALIDASI INPUTAN
                $validate = $request->validate([
                    'kode' => 'required|string',
                ],
                [
                    'kode.string' => 'Format kode harus string.',
                ]);
            //CLOSE VALIDASI INPUTAN
            //OPEN CREATE RAK
                $rak = new Rak();
                $rak->kode = $validate['kode'];
                $rak->is_active = $request->isActive?true:false;
                $rak->save();
            //CLOSE CREATE RAK

            activity()->performedOn($rak)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($rak)])->log('Tambah Rak');

            \DB::commit();
            return response()->json([
                'title' => 'Sukses!',
                'message' => 'Rak berhasil ditambah.'
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
    //Modal Edit Rak
    public function edit($param){
        $rak = Rak::find($param);
        return view('pages.backend.rak.modal.edit', compact('rak'));
    }
    //Proses Edit Rak
    public function update(Request $request){
        try {
            \DB::beginTransaction();
            //OPEN VALIDASI INPUTAN
                $validate = $request->validate([
                    'kode' => 'required|string',
                ],
                [
                    'kode.string' => 'Format kode harus string.',
                ]);
            //CLOSE VALIDASI INPUTAN
                $id = decrypt($request->id);
            //OPEN EDIT RAK
                $rak = Rak::find($id);
                $rak->kode = $validate['kode'];
                $rak->is_active = $request->isActive?true:false;
                $rak->save();
            //CLOSE EDIT RAK

            activity()->performedOn($rak)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($rak)])->log('Ubah Rak');

            \DB::commit();
            return response()->json([
                'title' => 'Sukses!',
                'message' => 'Rak berhasil diubah.'
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
                'message' => 'Rak gagal diubah.'. $e->getMessage()
            ], 500);
        }
    }

    //Proses Delete Rak
    public function delete($param)
    {
        try {
            \DB::beginTransaction();
            $id = decrypt($param);
            $rak = Rak::find($id);
            $rakHapus = $rak;
            $rak->delete();
            activity()->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($rakHapus)])->log('Hapus Rak');

            \DB::commit();
            return response()->json([
                                        'title'=> 'Sukses!' ,
                                        'message' => 'Rak telah dihapus.'
                                    ], 200);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                                        'title' => 'Gagal!',
                                        'message' => 'Rak gagal dihapus.'. $e->getMessage()
                                    ], 500);
        }
    }
}
