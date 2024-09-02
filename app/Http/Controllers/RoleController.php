<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();

        return view('pages.backend.role.index',compact('roles'));
    }
        //Modal Create User
        public function create(){
            $roles = Role::all();

            return view('pages.backend.role.modal.create', compact('roles'));
        }
        //Proses Create User
        public function store(Request $request){
            try {
                \DB::beginTransaction();
                //OPEN VALIDASI INPUTAN
                    $validate = $request->validate([
                        'role' => 'required|string|min:3|max:30',
                    ],
                    [
                        'role.string' => 'Format role harus string.',
                        'role.min' => 'Role harus memiliki minimal :min karakter.',
                        'role.max' => 'Role harus memiliki maksimal :max karakter.',
                    ]);
                //CLOSE VALIDASI INPUTAN
                //OPEN CREATE ROLE
                    $role = new Role();
                    $role->nama = $validate['role'];
                    $role->is_active = $request->isActive?true:false;
                    $role->save();
                //CLOSE CREATE ROLE

                activity()->performedOn($role)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($role)])->log('Tambah Role');

                \DB::commit();
                return response()->json([
                    'title' => 'Sukses!',
                    'message' => 'Role berhasil ditambah.'
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
        //Modal Edit Role
        public function edit($param){
            $role = Role::find($param);
            return view('pages.backend.role.modal.edit', compact('role'));
        }
        //Proses Edit Role
        public function update(Request $request){
            try {
                \DB::beginTransaction();
                //OPEN VALIDASI INPUTAN
                    $validate = $request->validate([
                        'role' => 'required|string|min:3|max:30',
                    ],
                    [
                        'role.string' => 'Format role harus string.',
                        'role.min' => 'Role harus memiliki minimal :min karakter',
                        'role.max' => 'Role harus memiliki maksimal :max karakter',
                    ]);
                //CLOSE VALIDASI INPUTAN
                $id = decrypt($request->id);
                //OPEN EDIT ROLE
                    $role = Role::find($id);
                    $role->nama = $validate['role'];
                    $role->is_active = $request->isActive?true:false;
                    $role->save();
                //CLOSE EDIT ROLE

                activity()->performedOn($role)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($role)])->log('Ubah Role');

                \DB::commit();
                return response()->json([
                    'title' => 'Sukses!',
                    'message' => 'Role berhasil diubah.'
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
                    'message' => 'Role gagal diubah.'. $e->getMessage()
                ], 500);
            }
        }
        //Proses Delete Role
        public function delete($param)
        {
            try {
                \DB::beginTransaction();

                $role = Role::find($param);
                $roleHapus = $role;
                $role->delete();
                activity()->performedOn($role)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($role)])->log('Hapus Role');

                \DB::commit();
                return response()->json([
                                            'title'=> 'Sukses!' ,
                                            'message' => 'Role telah dihapus.'
                                        ], 200);
            } catch (\Exception $e) {
                \DB::rollback();
                return response()->json([
                                            'title' => 'Gagal!',
                                            'message' => 'Role gagal dihapus.'. $e->getMessage()
                                        ], 500);
            }
        }
}
