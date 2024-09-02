<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProfilPetugas;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Rules\PasswordStrength;
use App\Models\User;
use App\Models\Role;

class PetugasAdminController extends Controller
{
    public function index()
    {
        $profilPetugases = ProfilPetugas::all();
        return view('pages.backend.petugasadmin.index', compact('profilPetugases'));
    }
    //Modal Create Petugas Admin
    public function create(){
        return view('pages.backend.petugasadmin.modal.create');
    }

    //Proses Create Petugas Admin
    public function store(Request $request){
        try {
            \DB::beginTransaction();
            //OPEN VALIDASI INPUTAN
                $validate = $request->validate([
                    'nip' => 'nullable|regex:/^\\d{5,30}$/',
                    'nama_lengkap' => 'required|string',
                    'nomor_telepon' => 'required|regex:/^\\d{5,15}$/',
                    'jadwal_shift_mulai' => 'required|date_format:H:i',
                    'jadwal_shift_selesai' => 'required|date_format:H:i|after:jadwal_shift_mulai',
                    'username' => 'required|string|min:3|max:100|unique:users',
                    'username' => 'required|string|min:3|max:100|unique:users',
                    'email' => 'nullable|email|unique:users',
                    'password' => [
                                        'required',
                                        'string',
                                        'min:8',
                                        new PasswordStrength,
                                    ],
                ],
                [
                    'nip.regex' => 'Masukkan NIP dengan benar',
                    'nama_lengkap.string' => 'Format nama lengkap harus string.',
                    'nomor_telepon.regex' => 'Masukkan nomor telepon dengan benar',
                    'jadwal_shift_mulai.date_format' => 'Format jadwal shift mulai tidak valid.',
                    'jadwal_shift_selesai.date_format' => 'Format jadwal shift selesai tidak valid.',
                    'jadwal_shift_selesai.after' => 'Jadwal shift selesai harus setelah jadwal shift mulai.',
                    'username.string' => 'Format username harus string.',
                    'username.min' => 'Username harus memiliki minimal :min karakter.',
                    'username.max' => 'Username harus memiliki maksimal :max karakter.',
                    'username.unique' => 'Username ini telah digunakan.',
                    'email.unique' => 'Alamat email ini telah digunakan.',
                ]);
            //CLOSE VALIDASI INPUTAN

            //OPEN CREATE USER
                $role = Role::get_dataByNama('petugas');
                $user = new User();
                $user->username = $validate['username'];
                $user->email = $validate['email'];
                $user->password = bcrypt($validate['password']);
                $user->role_id = $role->id;
                $user->is_active = $request->isActive?true:false;
                $user->save();
            //CLOSE CREATE USER
            //OPEN CREATE PROFIL PETUGAS
                $petugas = new ProfilPetugas();
                $petugas->nip = $request->nip;
                $petugas->nama_lengkap = $request->nama_lengkap;
                $petugas->nomor_telepon = $request->nomor_telepon;
                $petugas->user_id = $user->id;
                $petugas->jadwal_shift_mulai = $request->jadwal_shift_mulai;
                $petugas->jadwal_shift_selesai = $request->jadwal_shift_selesai;
                $petugas->save();
            //OPEN CREATE PROFIL PETUGAS
            activity()->performedOn($petugas)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($petugas)])->log('Tambah Petugas');

            \DB::commit();
            return response()->json([
                'title' => 'Sukses!',
                'message' => 'Petugas berhasil ditambah.'
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
                'message' => 'Proses pembuatan petugas gagal proses, hubungi Penanggung Jawab Aplikasi'
            ], 500);
        }
    }

    //Modal Edit Petugas Admin
    public function edit($param){
        $id = decrypt($param);
        $profilPetugas = ProfilPetugas::find($id);
        return view('pages.backend.petugasadmin.modal.edit', compact('profilPetugas'));
    }

    //Proses Edit Petugas Admin
    public function update(Request $request){
        try {
            \DB::beginTransaction();
            //OPEN VALIDASI INPUTAN
                $validate = $request->validate([
                    'nip' => 'nullable|regex:/^\\d{5,30}$/',
                    'nama_lengkap' => 'required|string',
                    'nomor_telepon' => 'required|regex:/^\\d{5,15}$/',
                    'jadwal_shift_mulai' => 'required|date_format:H:i',
                    'jadwal_shift_selesai' => 'required|date_format:H:i|after:jadwal_shift_mulai',
                    'password' => [
                                        'nullable',
                                        'string',
                                        'min:8',
                                        new PasswordStrength,
                                    ],
                ],
                [
                    'nip.regex' => 'Masukkan NIP dengan benar',
                    'nama_lengkap.string' => 'Format nama lengkap harus string.',
                    'nomor_telepon.regex' => 'Masukkan nomor telepon dengan benar',
                    'jadwal_shift_mulai.date_format' => 'Format jadwal shift mulai tidak valid.',
                    'jadwal_shift_selesai.date_format' => 'Format jadwal shift selesai tidak valid.',
                    'jadwal_shift_selesai.after' => 'Jadwal shift selesai harus setelah jadwal shift mulai.',
                ]);
            //CLOSE VALIDASI INPUTAN
            //OPEN CREATE PROFIL PETUGAS
                $id = decrypt($request->id);
                $petugas = ProfilPetugas::find($id);
                $petugas->nip = $request->nip;
                $petugas->nama_lengkap = $request->nama_lengkap;
                $petugas->nomor_telepon = $request->nomor_telepon;
                $petugas->jadwal_shift_mulai = $request->jadwal_shift_mulai;
                $petugas->jadwal_shift_selesai = $request->jadwal_shift_selesai;
                $petugas->save();
            //OPEN CREATE PROFIL PETUGAS
            //OPEN CREATE USER
                $user = User::find($petugas->user->id);
                $user->email = $request->email;
                if($validate['password'] != null){
                    $user->password = bcrypt($validate['password']);
                }
                $user->is_active = $request->isActive?true:false;
                $user->save();
            //CLOSE CREATE USER
            activity()->performedOn($petugas)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($petugas)])->log('Ubah Petugas');

            \DB::commit();
            return response()->json([
                'title' => 'Sukses!',
                'message' => 'Petugas berhasil diubah.'
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
                'message' => 'Proses perubahan petugas gagal proses, hubungi Penanggung Jawab Aplikasi'
            ], 500);
        }
    }

    //Proses Delete User
    public function delete($param)
    {
        try {
            \DB::beginTransaction();
            $id = decrypt($param);
            $userYangHapus = User::find(auth()->user()->id);
            $profilPetugas = ProfilPetugas::find($id);
            $userHapus = $profilPetugas->user;
            $profilPetugas->user->delete();
            $profilPetugas->delete();
            activity()->performedOn($userYangHapus)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($userHapus)])->log('Hapus User');

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
}
