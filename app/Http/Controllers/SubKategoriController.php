<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubKategori;
use App\Models\Kategori;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class SubKategoriController extends Controller
{
    public function index()
    {
        return view('pages.backend.subkategori.index');
    }
    //Modal Create sub kategori
    public function create()
    {
        $kategories = Kategori::get_dataIsActvie(true)->get();
        return view('pages.backend.subkategori.modal.create', compact('kategories'));
    }
    //Proses Create Sub Kategori
    public function store(Request $request)
    {
        try {
            \DB::beginTransaction();
            //OPEN VALIDASI INPUTAN
            $validate = $request->validate(
                [
                    'kategori_id' => 'required',
                    'nama' => 'required|string',
                    'kode' => 'nullable|string',
                ],
                [
                    'kategori_id.required' => 'Masukkan Kategori',
                    'nama.string' => 'Format kategori harus string.',
                    'kode.string' => 'Format kode harus string.'
                ]
            );
            //CLOSE VALIDASI INPUTAN
            //OPEN CREATE SUB KATEGORI
            $subkategori = new SubKategori();
            $subkategori->kategori_id = $validate['kategori_id'];
            $subkategori->nama = $validate['nama'];
            $subkategori->kode = $validate['kode'];
            $subkategori->is_active = $request->status ? true : false;
            $subkategori->save();
            //CLOSE CREATE SUB KATEGORI

            activity()->performedOn($subkategori)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($subkategori)])->log('Tambah Sub Kategori');

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
                'message' => 'Data gagal proses, ' . $e->getMessage()
            ], 500);
        }
    }
    //Modal Edit Sub Kategori
    public function edit($param){
        $id = decrypt($param);
        $subKategori = SubKategori::find($id);
        $kategories = Kategori::get_dataIsActvie(true)->get();

        return view('pages.backend.subkategori.modal.edit', compact('subKategori', 'kategories'));
    }
    //Proses Edit SubKategori
    public function update(Request $request){
        try {
            \DB::beginTransaction();
            //OPEN VALIDASI INPUTAN
                $validate = $request->validate([
                    'nama' => 'required|string',
                    'kode' => 'nullable|string',
                    'kategori_id' => 'required',
                ],
                [
                    'nama.string' => 'Format Sub kategori harus string.',
                    'kode.string' => 'Format kode harus string.',
                    'kategori_id.required' => 'Pilih kategori.',
                ]);
            //CLOSE VALIDASI INPUTAN
                $id = decrypt($request->id);
            //OPEN EDIT kategori
                $subKategori = SubKategori::find($id);
                $subKategori->nama = $validate['nama'];
                $subKategori->kode = $validate['kode'];
                $subKategori->kategori_id = $validate['kategori_id'];
                $subKategori->is_active = $request->status?true:false;
                $subKategori->save();
            //CLOSE EDIT kategori

            activity()->performedOn($subKategori)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($subKategori)])->log('Ubah Sub Kategori');

            \DB::commit();
            return response()->json([
                'title' => 'Sukses!',
                'message' => 'Sub Kategori berhasil diubah.'
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
                'message' => 'Sub Kategori gagal diubah.'. $e->getMessage()
            ], 500);
        }
    }
    public function datatable(){
        $subKategories = SubKategori::with('kategori')->orderby('kode', 'asc')->get();
        return datatables()->of($subKategories)
        ->addColumn('id', function ($row) {
            $id = encrypt($row->id);
            return $id;
        })
        ->addColumn('kategori', function ($row) {
            $data = $row->kategori->kode.' - '.$row->kategori->nama;
            return $data;
        })
        ->make();
    }
    public function delete($param)
    {
        try {
            \DB::beginTransaction();
            $id = decrypt($param);
            $subkategori = SubKategori::find($id);
            $subkategoriHapus = $subkategori;
            $subkategori->delete();
            activity()->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($subkategoriHapus)])->log('Hapus Sub Kategori');

            \DB::commit();
            return response()->json([
                                        'title'=> 'Sukses!' ,
                                        'message' => 'Sub Kategori telah dihapus.'
                                    ], 200);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                                        'title' => 'Gagal!',
                                        'message' => 'Sub Kategori gagal dihapus.'. $e->getMessage()
                                    ], 500);
        }
    }
}
