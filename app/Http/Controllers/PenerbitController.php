<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penerbit;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class PenerbitController extends Controller
{
    public function index(){
        $penerbits = Penerbit::all();

        return view('pages.backend.penerbit.index', compact('penerbits'));
    }
    //Modal Create Penerbit
    public function create(){
        return view('pages.backend.penerbit.modal.create');
    }
    //Proses Create Penerbit
    public function store(Request $request){
        try {
            \DB::beginTransaction();
            //OPEN VALIDASI INPUTAN
                $validate = $request->validate([
                    'namaPenerbit' => 'required|string',
                ],
                [
                    'namaPenerbit.string' => 'Format nama penerbit harus string.',
                ]);
            //CLOSE VALIDASI INPUTAN
            //OPEN CREATE PENERBIT
                $penerbit = new Penerbit();
                $penerbit->namaPenerbit = $validate['namaPenerbit'];
                $penerbit->is_active = $request->isActive?true:false;
                $penerbit->save();
            //CLOSE CREATE PENERBIT

            activity()->performedOn($penerbit)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($penerbit)])->log('Tambah Penerbit');

            \DB::commit();
            return response()->json([
                'title' => 'Sukses!',
                'message' => 'Penerbit berhasil ditambah.'
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
    //Modal Edit Penerbit
    public function edit($param){
        $penerbit = Penerbit::find($param);
        return view('pages.backend.penerbit.modal.edit', compact('penerbit'));
    }
    //Proses Edit Penerbit
    public function update(Request $request){
        try {
            \DB::beginTransaction();
            //OPEN VALIDASI INPUTAN
                $validate = $request->validate([
                    'namaPenerbit' => 'required|string',
                ],
                [
                    'namaPenerbit.string' => 'Format nama penerbit harus string.',
                ]);
            //CLOSE VALIDASI INPUTAN
                $id = decrypt($request->id);
            //OPEN EDIT PENERBIT
                $namaPenerbit = Penerbit::find($id);
                $namaPenerbit->namaPenerbit = $validate['namaPenerbit'];
                $namaPenerbit->is_active = $request->isActive?true:false;
                $namaPenerbit->save();
            //CLOSE EDIT PENERBIT

            activity()->performedOn($namaPenerbit)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($namaPenerbit)])->log('Ubah Penerbit');

            \DB::commit();
            return response()->json([
                'title' => 'Sukses!',
                'message' => 'Penerbit berhasil diubah.'
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
                'message' => 'Penerbit gagal diubah.'. $e->getMessage()
            ], 500);
        }
    }
    //Proses Delete Penerbit
    public function delete($param)
    {
        try {
            \DB::beginTransaction();
            $id = decrypt($param);
            $penerbit = Penerbit::find($id);
            $penerbitHapus = $penerbit;
            $penerbit->delete();
            activity()->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($penerbitHapus)])->log('Hapus Penerbit');

            \DB::commit();
            return response()->json([
                                        'title'=> 'Sukses!' ,
                                        'message' => 'Penerbit telah dihapus.'
                                    ], 200);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                                        'title' => 'Gagal!',
                                        'message' => 'Penerbit gagal dihapus.'. $e->getMessage()
                                    ], 500);
        }
    }
}
