<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::orderby('kode', 'asc')->get();
        return view('pages.backend.kategori.index',compact('kategoris'));
    }
    //Modal Create kategori
    public function create(){
        return view('pages.backend.kategori.modal.create');
    }
    //Proses Create Kategori
    public function store(Request $request){
        try {
            \DB::beginTransaction();
            //OPEN VALIDASI INPUTAN
                $validate = $request->validate([
                    'nama' => 'required|string',
                    'kode' => 'nullable|string',
                ],
                [
                    'nama.string' => 'Format kategori harus string.',
                    'kode.string' => 'Format kode harus string.'
                ]);
            //CLOSE VALIDASI INPUTAN
            //OPEN CREATE KATEGORI
                $kategori = new Kategori();
                $kategori->nama = $validate['nama'];
                $kategori->kode = $validate['kode'];
                $kategori->is_active = $request->isActive?true:false;
                $kategori->save();
            //CLOSE CREATE KATEGORI

            activity()->performedOn($kategori)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($kategori)])->log('Tambah Kategori');

            \DB::commit();
            return response()->json([
                'title' => 'Sukses!',
                'message' => 'Kategori berhasil ditambah.'
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
    //Modal Edit Kategori
    public function edit($param){
        $kategori = Kategori::find($param);
        return view('pages.backend.kategori.modal.edit', compact('kategori'));
    }
    //Proses Edit Kategori
    public function update(Request $request){
        try {
            \DB::beginTransaction();
            //OPEN VALIDASI INPUTAN
                $validate = $request->validate([
                    'nama' => 'required|string',
                    'kode' => 'nullable|string',
                ],
                [
                    'nama.string' => 'Format kategori harus string.',
                    'kode.string' => 'Format kode harus string.',
                ]);
            //CLOSE VALIDASI INPUTAN
                $id = decrypt($request->id);
            //OPEN EDIT kategori
                $kategori = Kategori::find($id);
                $kategori->nama = $validate['nama'];
                $kategori->kode = $validate['kode'];
                $kategori->is_active = $request->isActive?true:false;
                $kategori->save();
            //CLOSE EDIT kategori

            activity()->performedOn($kategori)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($kategori)])->log('Ubah Rak');

            \DB::commit();
            return response()->json([
                'title' => 'Sukses!',
                'message' => 'Kategori berhasil diubah.'
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
                'message' => 'Kategori gagal diubah.'. $e->getMessage()
            ], 500);
        }
    }
    //Proses Delete Rak
    public function delete($param)
    {
        try {
            \DB::beginTransaction();
            $id = decrypt($param);
            $kategori = Kategori::find($id);
            $kategoriHapus = $kategori;
            $kategori->delete();
            activity()->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($kategoriHapus)])->log('Hapus Kategori');

            \DB::commit();
            return response()->json([
                                        'title'=> 'Sukses!' ,
                                        'message' => 'Kategori telah dihapus.'
                                    ], 200);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                                        'title' => 'Gagal!',
                                        'message' => 'Kategori gagal dihapus.'. $e->getMessage()
                                    ], 500);
        }
    }
}
