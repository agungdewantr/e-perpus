<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::orderby('tipe_menu','asc')->orderby('ordinal_number','asc')->get();

        return view('pages.backend.menu.index',compact('menus'));
    }
    //Modal Create Menu
    public function create(){
        $tipeMenus = Menu::tipe_menu();
        return view('pages.backend.menu.modal.create', compact('tipeMenus'));
    }
    //Proses Create Menu
    public function store(Request $request){
        try {
            \DB::beginTransaction();
            //OPEN VALIDASI INPUTAN
                $validate = $request->validate([
                    'nama' => 'required|string|max:255',
                    'icon' => 'required|string|max:255',
                    'tipeMenu' => 'required|in:management,master data,other',
                    'ordinalNumber' => 'required|integer|min:0|max:999',
                ],
                [
                    'nama.string' => 'Format nama harus string.',
                    'nama.max' => 'nama harus memiliki maksimal :min karakter.',
                    'icon.string' => 'Format icon harus string.',
                    'icon.max' => 'icon harus memiliki maksimal :min karakter.',
                    'tipeMenu.in' => 'Tipe menu yang dipilih tidak valid.',
                    'ordinalNumber.integer' => 'Nomor urutan harus berupa angka.',
                    'ordinalNumber.min' => 'Nomor urutan harus lebih besar atau sama dengan 0.',
                    'ordinalNumber.max' => 'Nomor urutan harus kurang dari atau sama dengan 999.',

                ]);
            //CLOSE VALIDASI INPUTAN
            $key_nama = str_replace(' ', '_', strtolower($validate['nama']));
            $route = strtolower(str_replace(' ', '', $validate['nama'])) . '.index';
            $url = '/' . strtolower(str_replace(' ', '-', $validate['nama']));
            //OPEN CREATE MENU
                $menu = new Menu();
                $menu->nama = $validate['nama'];
                $menu->key_nama = $key_nama;
                $menu->route = $route;
                $menu->url = $url;
                $menu->icon = $validate['icon'];
                $menu->tipe_menu = $validate['tipeMenu'];
                $menu->ordinal_number = $validate['ordinalNumber'];
                $menu->is_active = $request->isActive?true:false;
                $menu->save();
            //CLOSE CREATE MENU
            //OPEN CREATE PERMISSIONS MENU
                $roles = Role::withTrashed()->get();
                foreach($roles as $role)
                {
                    $permission = new Permission();
                    $permission->role_id = $role->id;
                    $permission->menu_id = $menu->id;
                    $permission->can_read = false;
                    $permission->can_create = false;
                    $permission->can_update = false;
                    $permission->can_delete = false;
                    $permission->save();
                }
            //CLOSE CREATE PERMISSIONS MENU

            activity()->performedOn($menu)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($menu)])->log('Tambah Menu');

            \DB::commit();
            return response()->json([
                'title' => 'Sukses!',
                'message' => 'Menu berhasil ditambah.'
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
                'message' => 'Data gagal proses.'. $e->getMessage()
            ], 500);
        }
    }

    //Modal Edit Menu
    public function edit($param){
        $tipeMenus = Menu::tipe_menu();
        $menu = Menu::find($param);
        return view('pages.backend.menu.modal.edit', compact('tipeMenus', 'menu'));
    }
    //Proses Edit Menu
    public function update(Request $request){
        try {
            \DB::beginTransaction();

            $id = decrypt($request->id);
            //OPEN VALIDASI INPUTAN
                $validate = $request->validate([
                    'nama' => 'required|string|max:255',
                    'icon' => 'required|string|max:255',
                    'tipeMenu' => 'required|in:management,master data,other',
                    'ordinalNumber' => 'required|integer|min:0|max:999',
                ],
                [
                    'nama.string' => 'Format nama harus string.',
                    'nama.max' => 'nama harus memiliki maksimal :min karakter.',
                    'icon.string' => 'Format icon harus string.',
                    'icon.max' => 'icon harus memiliki maksimal :min karakter.',
                    'tipeMenu.in' => 'Tipe menu yang dipilih tidak valid.',
                    'ordinalNumber.integer' => 'Nomor urutan harus berupa angka.',
                    'ordinalNumber.min' => 'Nomor urutan harus lebih besar atau sama dengan 0.',
                    'ordinalNumber.max' => 'Nomor urutan harus kurang dari atau sama dengan 999.',

                ]);
            //CLOSE VALIDASI INPUTAN
            $key_nama = str_replace(' ', '_', strtolower($validate['nama']));
            $route = strtolower(str_replace(' ', '', $validate['nama'])) . '.index';
            $url = '/' . strtolower(str_replace(' ', '-', $validate['nama']));
            //OPEN EDIT MENU
                $menu = Menu::find($id);
                $menu->nama = $validate['nama'];
                $menu->key_nama = $key_nama;
                $menu->route = $route;
                $menu->url = $url;
                $menu->icon = $validate['icon'];
                $menu->tipe_menu = $validate['tipeMenu'];
                $menu->ordinal_number = $validate['ordinalNumber'];
                $menu->is_active = $request->isActive?true:false;
                $menu->save();
            //CLOSE EDIT MENU

            activity()->performedOn($menu)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($menu)])->log('Ubah Menu');

            \DB::commit();
            return response()->json([
                'title' => 'Sukses!',
                'message' => 'Menu berhasil diubah.'
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
                'message' => 'Menu gagal diubah.'. $e->getMessage()
            ], 500);
        }
    }
    //Proses Delete Menu
    public function delete($param)
    {
        try {
            \DB::beginTransaction();

            $menu = Menu::find($param);
            $menuHapus = $menu;
            $menu->permissions()->delete();
            $menu->delete();
            activity()->performedOn($menu)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($menuHapus)])->log('Hapus Menu');

            \DB::commit();
            return response()->json([
                                        'title'=> 'Sukses!' ,
                                        'message' => 'Menu telah dihapus.'
                                    ], 200);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                                        'title' => 'Gagal!',
                                        'message' => 'Menu gagal dihapus.'. $e->getMessage()
                                    ], 500);
        }
    }
}
