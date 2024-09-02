<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Role;
use App\Models\Menu;
use App\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $roles = Role::get_dataIsActvie(1)->get();

        return view('pages.backend.permission.index',compact('roles'));
    }
    //Modal Detail Permission
    public function detail($param){
        $role = Role::find($param);
        $menus = Menu::get_dataIsActvie(1)->get();

        return view('pages.backend.permission.modal.detail', compact('role','menus'));
    }
    //Proses Edit Permission
    public function update(Request $request){
        try {
            \DB::beginTransaction();

            $id = decrypt($request->id);
            $role = Role::find($id);

            //OPEN EDIT PERMISSIONS
                foreach ($role->menus->sortby('nama') as $menu) {
                    $canRead = 'canRead_'.$role->id.'_'.$menu->id;
                    $canCreate = 'canCreate_'.$role->id.'_'.$menu->id;
                    $canUpdate = 'canUpdate_'.$role->id.'_'.$menu->id;
                    $canDelete = 'canDelete_'.$role->id.'_'.$menu->id;
                    $permission = Permission::update_dataPermission($menu->id, $role->id,$request->$canRead ? true : false, $request->$canCreate ? true : false, $request->$canUpdate ? true : false, $request->$canDelete ? true : false);
                }
            //CLOSE EDIT PERMISSIONS

            activity()->performedOn($role)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($role)])->log('Ubah Permission');

            \DB::commit();
            return response()->json([
                'title' => 'Sukses!',
                'message' => 'Permission berhasil diubah.'
            ], 200);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'title' => 'Gagal!',
                'message' => 'Permission gagal diubah.'. $e->getMessage()
            ], 500);
        }
    }
}
