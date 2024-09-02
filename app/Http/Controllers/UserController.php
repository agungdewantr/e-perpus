<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Dokumen;
use App\Models\Favorit;
use App\Models\Keranjang;
use App\Models\ProfilAnggota;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Rules\PasswordStrength;
use Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('pages.backend.user.index',compact('users'));
    }
    //Modal Create User
    public function create(){
        $roles = Role::get_dataIsActvie(1)->get();

        return view('pages.backend.user.modal.create', compact('roles'));
    }
    //Proses Create User
    public function store(Request $request){
        try {
            \DB::beginTransaction();
            //OPEN VALIDASI INPUTAN
                $validate = $request->validate([
                    'username' => 'required|string|min:3|max:100|unique:users',
                    'email' => 'required|email|unique:users',
                    'password' => [
                                        'required',
                                        'string',
                                        'min:8',
                                        new PasswordStrength,
                                    ],
                    'role' => 'required|exists:roles,id',
                    'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:5000'
                ],
                [
                    'username.string' => 'Format username harus string.',
                    'username.min' => 'Username harus memiliki minimal :min karakter.',
                    'username.max' => 'Username harus memiliki maksimal :max karakter.',
                    'username.unique' => 'Username ini telah digunakan.',
                    'email.unique' => 'Alamat email ini telah digunakan.',
                    'role.required' => 'Masukkan role yang tersedia.',
                    'role.exists' => 'Masukkan role yang tersedia.',
                    'foto.image' => 'Masukkan file foto yang benar.',
                    'foto.mimes' => 'Format foto harus jpeg, png, jpg.',
                    'foto.max' => 'Ukuran foto melebihi :max MB.',
                ]);
            //CLOSE VALIDASI INPUTAN
            //OPEN CREATE FOTO
                $file = $request->file('foto');
                $foto_id = null;
                if($file){
                    $ori_filename = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $nama_file = $request->username . '_' . date('d-m-y') . '.' . $extension;
                    $ukuran_file = $file->getSize();

                    $thumbImage = Image::make($file->getRealPath());
                    $thumbImage->resize(1000, 1000, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $thumbPath = 'user/'. $nama_file;

                    $foto = new Dokumen();
                    $foto->filename = $nama_file;
                    $foto->ori_filename = $ori_filename;
                    $foto->ekstensi = $extension;
                    $foto->type = 'foto_profil';
                    $foto->jenis = 'upload';
                    $foto->keterangan = null;
                    $foto->file_path = 'foto_profil/' . $nama_file;
                    $foto->save();

                    $foto_id = $foto->id;
                }
            //CLOSE CREATE FOTO
            //OPEN CREATE USER
                $user = new User();
                $user->username = $validate['username'];
                $user->email = $validate['email'];
                $user->password = bcrypt($validate['password']);
                $user->role_id = $validate['role'];
                $user->foto_id = $foto_id;
                $user->is_active = $request->isActive?true:false;
                $user->save();
            //CLOSE CREATE USER
            //OPEN SIMPAN KE STORAGE
                if($file){
                    $file->storeAs('user', $nama_file, 'public');
                }
            //OPEN SIMPAN KE STORAGE
            activity()->performedOn($user)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($user)])->log('Tambah User');

            \DB::commit();
            return response()->json([
                'title' => 'Sukses!',
                'message' => 'User berhasil ditambah.'
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

    //Modal Edit User
    public function edit($param){
        $roles = Role::get_dataIsActvie(1)->get();
        $superadmin = Role::find(4);

        $user = User::find($param);
        return view('pages.backend.user.modal.edit', compact('roles', 'user','superadmin'));
    }
    //Proses Edit User
    public function update(Request $request){
        try {
            \DB::beginTransaction();

            $id = decrypt($request->id);
            $user = User::find($id);
            $fotoOld = $user->foto;
            $file = $request->file('foto');
            $foto_id = null;
            //OPEN VALIDASI INPUTAN
                //VALIDASI JIKA TIDAK DENGAN GANTI PASSWORD
                if($request->password == null){
                    $validate = $request->validate([
                        'role' => 'required|exists:roles,id',
                        'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:5000'
                    ],
                    [
                        'role.required' => 'Masukkan role yang tersedia.',
                        'role.exists' => 'Masukkan role yang tersedia.',
                        'foto.image' => 'Masukkan file foto yang benar.',
                        'foto.mimes' => 'Format foto harus jpeg, png, jpg.',
                        'foto.max' => 'Ukuran foto melebihi :max MB.',
                    ]);
                }
                //VALIDASI JIKA DENGAN GANTI PASSWORD
                else{
                    $validate = $request->validate([
                        'password' => [
                                        'string',
                                        'min:8',
                                        new PasswordStrength,
                                    ],
                        'role' => 'required|exists:roles,id',
                        'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:5000'
                    ],
                    [
                        'password.min' => 'Password minimal :min karakter.',
                        'role.required' => 'Masukkan role yang tersedia.',
                        'role.exists' => 'Masukkan role yang tersedia.',
                        'foto.image' => 'Masukkan file foto yang benar.',
                        'foto.mimes' => 'Format foto harus jpeg, png, jpg.',
                        'foto.max' => 'Ukuran foto melebihi :max MB.',
                    ]);
                }
            //CLOSE VALIDASI INPUTAN
            //OPEN CREATE FOTO (dokumen)
                if($file){
                    $ori_filename = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $nama_file = $user->username.'('. rand(1, 999).').'. $extension;
                    $ukuran_file = $file->getSize();

                    $thumbImage = Image::make($file->getRealPath());
                    $thumbImage->resize(1000, 1000, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $thumbPath = 'user/'. $nama_file;

                    $foto = new Dokumen();
                    $foto->filename = $nama_file;
                    $foto->ori_filename = $ori_filename;
                    $foto->ekstensi = $extension;
                    $foto->type = 'foto profil';
                    $foto->jenis = 'user';
                    $foto->keterangan = null;
                    $foto->file_path = $thumbPath;
                    $foto->save();

                    $foto_id = $foto->id;
                }
            //CLOSE CREATE FOTO (dokumen)
            //OPEN EDIT USER
                //JIKA GANTI PASSWORD
                if($request->password != null){
                    $user->password = bcrypt($validate['password']);
                }
                //jika GANTI FOTO
                if($file){
                    $user->foto_id = $foto_id;
                }
                $user->role_id = $validate['role'];
                $user->is_active = $request->isActive?true:false;
                $user->save();
            //CLOSE EDIT USER
            //OPEN JIKA GANTI FOTO
                if($file){
                    //DELETE FOTO OLD
                    if($fotoOld)
                    {
                        $dokumenOld = Dokumen::find($fotoOld->id);
                        $pathOld = $dokumenOld->file_path;
                        $dokumenOld->delete();
                        Storage::disk('public')->delete($pathOld);
                    }
                    //SIMPAN FOTO BARU KE STORAGE
                    $file->storeAs('user', $nama_file, 'public');
                }
            //CLOSE JIKA GANTI FOTO
            activity()->performedOn($user)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($user)])->log('Ubah User');

            \DB::commit();
            return response()->json([
                'title' => 'Sukses!',
                'message' => 'User berhasil diubah.'
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
                'message' => 'User gagal diubah.'. $e->getMessage()
            ], 500);
        }
    }
    //Proses Delete User
    public function delete($param)
    {
        try {
            \DB::beginTransaction();

            $user = User::find($param);
            $userHapus = $user;
            $user->delete();
            // Storage::disk('public')->delete($userHapus->foto->file_path);
            activity()->performedOn($user)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($user)])->log('Hapus User');

            \DB::commit();
            return response()->json([
                                        'title'=> 'Sukses!' ,
                                        'message' => 'User telah dihapus.'
                                    ], 200);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                                        'title' => 'Gagal!',
                                        'message' => 'User gagal dihapus.'. $e->getMessage()
                                    ], 500);
        }
    }

    //Detail Anggota
    public function detailanggota($param = null){
        if($param == null){
            $user = auth()->user();
            return view('pages.frontend.detailanggota.index',compact('user', 'param'));
        }
        else{
            $data ='';
            $profil_anggota = auth()->user()->profilAnggota;
            if($param == 'favorit'){
                $data =  $profil_anggota ?  Favorit::where('profil_anggota_id',$profil_anggota->id)->orderBy('id','desc')->get() : [];
            }elseif($param == 'keranjang'){
                $data = $profil_anggota ?  Keranjang::where('profil_anggota_id',$profil_anggota->id)->orderBy('id','desc')->get() : [];
            }elseif($param == 'riwayatpeminjaman'){
                $data = $profil_anggota ? Peminjaman::where('profil_anggota_id',$profil_anggota->id)->orderBy('id','desc')->get() : [];
            }
            $user = auth()->user();
            return view('pages.frontend.detailanggota.'.$param,compact('user', 'param','data'));
        }
    }
    //Profil
    public function profil(Request $request)
    {
        try {
            \DB::beginTransaction();
            //OPEN VALIDASI INPUTAN
                $validate = $request->validate([
                    'foto_kartu_identitas_id' => 'image|mimes:jpeg,png,jpg|max:5000',
                    'nomor_identitas' => 'nullable|string',
                    'email' => 'nullable|email',
                    'pekerjaan' => 'nullable|string',
                    'nomor_telepon' => 'nullable|numeric|min:10',
                ],
                [
                    'foto_kartu_identitas_id.image' => 'Masukkan file foto yang benar.',
                    'foto_kartu_identitas_id.mimes' => 'Format foto harus jpeg, png, jpg.',
                    'foto_kartu_identitas_id.max' => 'Ukuran foto melebihi :max KB.',
                    'nomor_identitas.string' => 'Masukkan nomor identitas yang benar.',
                    'email.email' => 'Masukkan email yang benar.',
                    'pekerjaan.string' => 'Masukkan pekerjaan yang benar.',
                    'nomor_telepon.numeric' => 'Masukkan nomor telepon yang benar.',
                    'nomor_telepon.min' => 'Masukkan nomor telepon minimal :min angka.',
                ]);
            //CLOSE VALIDASI INPUTAN
            if($request->id == null){
                // proses file kartu anggota
                $file = $request->foto_kartu_identitas_id;
                $ori_filename =  $file->getClientOriginalName();
                $ekstensi = $file->getClientOriginalExtension();
                $filename = auth()->user()->username.'_'.date('d-m-y').'.'.$ekstensi;
                $dokumen = new Dokumen();
                $dokumen->filename = $filename;
                $dokumen->ori_filename = $ori_filename;
                $dokumen->ekstensi = $ekstensi;
                $dokumen->type = 'kartu_identitas';
                $dokumen->jenis = 'upload';
                $dokumen->keterangan = 'Kartu identitas anggota perpustakaan.';
                $dokumen->file_path = 'kartu_identitas/'. $filename;
                $dokumen->save();
                activity()->performedOn($dokumen)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($dokumen)])->log('Menggunggah kartu identitas');

                //Proses Profil
                $profilAnggota = new ProfilAnggota();
                $profilAnggota->nama_lengkap = $request->nama_lengkap;
                $profilAnggota->alamat = $request->alamat;
                $profilAnggota->tempat = $request->tempat;
                $profilAnggota->tanggal_lahir = $request->tanggal_lahir;
                $profilAnggota->jenis_kelamin = $request->jenis_kelamin;
                $profilAnggota->nomor_identitas = $request->nomor_identitas;
                $profilAnggota->email = $request->email;
                $profilAnggota->nomor_telepon = $request->nomor_telepon;
                $profilAnggota->pekerjaan = $request->pekerjaan;
                $profilAnggota->foto_kartu_identitas_id = $dokumen->id;
                $profilAnggota->user_id = auth()->user()->id;
                $profilAnggota->is_active = true;
                $profilAnggota->save();

                $user = user::find(auth()->user()->id);
                $user->email = $request->email;
                $user->save();
                activity()->performedOn($profilAnggota)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($profilAnggota)])->log('Mengisi Profil Anggota');


                //simpan file
                $file->storeAs('kartu_identitas', $filename, 'public');
            }
            else{
                $id = decrypt($request->id);
                $profilAnggota = ProfilAnggota::find($id);
                $profilAnggota->nomor_telepon = $request->nomor_telepon;
                $profilAnggota->pekerjaan = $request->pekerjaan;
                $profilAnggota->save();

                $user = user::find(auth()->user()->id);
                $user->email = $request->email;
                $user->save();
                activity()->performedOn($profilAnggota)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($profilAnggota)])->log('Mengubah data pekerjaan dan nomor telepon');
            }
            \DB::commit();
            return response()->json([
                'status' => 'success',
                'title' => 'Sukses!',
                'message' => 'Profil berhasil disimpan.'
            ], 200);
        } catch (ValidationException $e) {
            \DB::rollback();
            return response()->json([
                'status' => 'error',
                'title' => 'Gagal!',
                'message' => 'Cek kembali data yang telah diinput.',
                'messageValidate' => $e->validator->getMessageBag()
            ], 422);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'status' => 'error',
                'title' => 'Gagal!',
                'message' => 'Data gagal proses.'. $e->getMessage()
            ], 500);
        }
    }
//ganti profil
    public function gantiPassword(Request $request)
    {
        try {
            \DB::beginTransaction();
            $validate = $request->validate([
                'password_lama' => [
                    'required',
                    'min:8',
                    new PasswordStrength,
                    function ($attribute, $value, $fail) {
                        if (!Hash::check($value, auth()->user()->password)) {
                            $fail('Password lama tidak sesuai dengan password saat ini.');
                        }
                    },
                ],
                'password_baru' => [
                    'required',
                    'string',
                    'min:8',
                    new PasswordStrength,
                ],
                'konfirmasi_password_baru' => [
                    'required',
                    'string',
                    'min:8',
                    'same:password_baru',
                    new PasswordStrength,
                ],
            ],
            [
                'password_lama.required' => 'Password lama wajib diisi',
                'password_baru.required' => 'Password baru wajib diisi',
                'konfirmasi_password_baru.required' => 'Konfirmasi Password baru wajib diisi',
                'konfirmasi_password_baru.same' => 'Konfirmasi Password baru harus sama dengan Password baru',
                'password_lama' => 'Password lama tidak sesuai dengan password saat ini',
            ]);

            $user = auth()->user();
            $user->password = Hash::make($request->password_baru);
            $user->save();
            activity()->performedOn($user)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($user)])->log('Mengubah Password');
            \DB::commit();
            return response()->json([
                'status' => 'success',
                'title' => 'Sukses!',
                'message' => 'Profil berhasil disimpan.'
            ], 200);
        }
        catch (ValidationException $e) {
            \DB::rollback();
            return response()->json([
                'status' => 'error',
                'title' => 'Gagal!',
                'message' => 'Cek kembali data yang telah diinput.',
                'messageValidate' => $e->validator->getMessageBag()
            ], 422);
        }
         catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'status' => 'error',
                'title' => 'Gagal!',
                'message' => 'Data gagal proses.'. $e->getMessage()
            ], 500);
        }
// dd($request->all());
    }

    public function editfoto($param)
    {
        $user = User::find($param);

        return view('pages.frontend.detailanggota.modal.editfoto', compact('user'));
    }

    public function updatefoto(Request $request)
    {
        try {
            \DB::beginTransaction();

            $id = decrypt($request->id);
            $user = User::find($id);
            //OPEN VALIDASI INPUTAN
                $validate = $request->validate([
                    'foto_id' => 'required|mimes:jpeg,png,jpg|max:5000',
                ],
                [
                    'foto_id.image' => 'Pilih file foto.',
                    'foto_id.mimes' => 'Format foto tidak valid.',
                    'foto_id.max' => 'Ukuran foto melebihi :max MB.',
                ]);
            //CLOSE VALIDASI INPUTAN
            $id = decrypt($request->id);
            $user = User::find($id);

            if ($request->hasFile('foto_id')) {
                $fotoProfil = $user->foto;
                $file = $request->file('foto_id');
                $ori_filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $nama_file = $user->username . '_' . date('d-m-y') . '.' . $extension;

                $thumbImageCover = Image::make($file->getRealPath());
                $thumbImageCover->resize(300, 400, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                if($fotoProfil != null){
                    if (Storage::disk('public')->exists($fotoProfil->file_path)) {
                        Storage::disk('public')->delete($fotoProfil->file_path);
                    }
                    $fotoProfil->update([
                        'filename' =>   $nama_file,
                        'ori_filename' => $ori_filename,
                        'ekstensi' => $extension,
                        'file_path' => 'foto_profil/' . $nama_file,
                    ]);
                }else{
                    $dokumenFotoProfil = new Dokumen();
                    $dokumenFotoProfil->filename = $nama_file;
                    $dokumenFotoProfil->ori_filename = $ori_filename;
                    $dokumenFotoProfil->ekstensi = $extension;
                    $dokumenFotoProfil->type = 'foto_profil';
                    $dokumenFotoProfil->jenis = 'upload';
                    $dokumenFotoProfil->keterangan = 'Foto Profil petugas perpustakaan.';
                    $dokumenFotoProfil->file_path = 'foto_profil/' . $nama_file;
                    $dokumenFotoProfil->save();
                    activity()->performedOn($dokumenFotoProfil)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($dokumenFotoProfil)])->log('Mengunggah foto profil');
                    $user->foto_id = $dokumenFotoProfil->id;
                    $user->save();
                }
                $file->storeAs('foto_profil', $nama_file, 'public');
            }
            activity()->performedOn($user)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($user)])->log('Ubah Foto Profil');

            \DB::commit();
            return response()->json([
                'title' => 'Sukses!',
                'message' => 'Foto Profil berhasil diubah.'
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
                'message' => 'Foto Profil gagal diubah.'. $e->getMessage()
            ], 500);
        }
    }
}
