<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use App\Rules\PasswordStrength;
use Image;
use App\Models\Dokumen;


class ProfilBackEndController extends Controller
{
    public function index($param)
    {
        $param = decrypt($param);
        $user = User::find($param);
        $lastActivitys = Activity::with('causer')->where('causer_id',$param)->orderby('id', 'desc')->take(10)->get();

        return view('pages.backend.profil.index',compact('user','lastActivitys'));
    }
    //Modal edit password
    public function editpassword($param)
    {
        $user = User::find($param);

        return view('pages.backend.profil.modal.editpassword', compact('user'));
    }
    //Proses Edit password
    public function updatepassword(Request $request)
    {
        try {
            \DB::beginTransaction();

            $id = decrypt($request->id);
            $user = User::find($id);
            //OPEN VALIDASI INPUTAN
                $validate = $request->validate([
                    'password' => [
                                    'string',
                                    'min:8',
                                    new PasswordStrength,
                                ],
                ],
                [
                    'password.min' => 'Password minimal :min karakter.'
                ]);
            //CLOSE VALIDASI INPUTAN
            //OPEN EDIT USER
                //JIKA GANTI PASSWORD
                $user->password = bcrypt($validate['password']);
                $user->save();
            //CLOSE EDIT USER
            activity()->performedOn($user)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($user)])->log('Ubah Password');

            \DB::commit();
            return response()->json([
                'title' => 'Sukses!',
                'message' => 'Password berhasil diubah.'
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
                'message' => 'Password gagal diubah.'. $e->getMessage()
            ], 500);
        }
    }
    //Modal edit password
    public function editfoto($param)
    {
        $user = User::find($param);

        return view('pages.backend.profil.modal.editfoto', compact('user'));
    }
    //Proses Edit Foto
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
    //Proses Edit Profil
    public function updateprofil(Request $request){
        try {
            \DB::beginTransaction();
            //OPEN VALIDASI INPUTAN
                $validate = $request->validate([
                    'nip' => 'nullable|regex:/^\\d{5,15}$/',
                    'nama_lengkap' => 'required|string',
                    'nomor_telepon' => 'required|regex:/^\\d{5,15}$/',
                    'jadwal_shift_mulai' => 'nullable|date_format:H:i',
                    'jadwal_shift_selesai' => 'nullable|date_format:H:i|after:jadwal_shift_mulai',
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
                $user = User::find($id);
                $petugas = $user->profilPetugas;
                $petugas->nip = $request->nip;
                $petugas->nama_lengkap = $request->nama_lengkap;
                $petugas->nomor_telepon = $request->nomor_telepon;
                $petugas->jadwal_shift_mulai = $request->jadwal_shift_mulai;
                $petugas->jadwal_shift_selesai = $request->jadwal_shift_selesai;
                $petugas->save();

            //OPEN CREATE PROFIL PETUGAS
            activity()->performedOn($petugas)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($petugas)])->log('Ubah Profil');

            \DB::commit();
            return response()->json([
                'title' => 'Sukses!',
                'message' => 'Porfil berhasil diubah.'
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
                'message' => 'Proses perubahan profil gagal proses, hubungi Penanggung Jawab Aplikasi'. $e->getMessage()
            ], 500);
        }
    }
}
