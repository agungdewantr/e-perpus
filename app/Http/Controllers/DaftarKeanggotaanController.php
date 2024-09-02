<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProfilAnggota;
use DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use App\Rules\PasswordStrength;
use App\Models\Dokumen;
use App\Models\Role;
use App\Models\User;
use Image;

class DaftarKeanggotaanController extends Controller
{
    public function index()
    {
        return view('pages.backend.keanggotaan.index');
    }
    //Datatable menampilkan daftar anggota
    public function datatable()
    {
        // $excludedIds = [1, 2, 3,4,5,6,7,8,9,10,11,12];

        $anggotas = ProfilAnggota::get_dataIsVerified(true)->get();
        return datatables()->of($anggotas)
            ->addColumn('id', function ($row) {
                $id = encrypt($row->id);
                return $id;
            })
            ->addColumn('ttl', function ($row) {
                $formattedDate = Carbon::parse($row->tanggal_lahir)->isoFormat('DD MMMM YYYY');
                $ttl = $row->tempat . ', ' . $formattedDate;
                return $ttl;
            })
            ->addColumn('created_at', function ($row) {
                $formattedDate = Carbon::parse($row->created_at)->isoFormat('DD MMMM YYYY');
                return $formattedDate;
            })
            ->make(true);
    }
    //Modal Create Daftar Anggota
    public function create()
    {
        return view('pages.backend.keanggotaan.modal.create');
    }
    //Proses Create Aggota
    public function store(Request $request)
    {
        try {
            \DB::beginTransaction();
            //OPEN VALIDASI INPUTAN
            $validate = $request->validate(
                [
                    'nomor_telepon' => 'required|regex:/^\\d{10,15}$/',
                    'foto_id' => 'nullable|image|mimes:jpeg,png,jpg|max:5000',
                    'foto_kartu_identitas_id' => 'nullable|image|mimes:jpeg,png,jpg|max:5000',
                    'username' => 'required | unique:users',
                    'email' => 'nullable | unique:users',
                    'password' => [
                        'required',
                        'string',
                        'min:8',
                        new PasswordStrength,
                    ]
                ],
                [
                    'nomor_telepon.regex' => 'Masukkan Nomor Telepon dengan benar',
                    'username.unique' => 'Username telah digunakan.',
                    'email.unique' => 'Email telah digunakan.',
                    'password.string' => 'Masukkan password dengan bener.',
                    'password.min' => 'Masukkan password minimal :min karakter.',
                    'foto_id.image' => 'Masukkan file foto dengan benar.',
                    'foto_id.mimes' => 'Format foto harus jpeg, png, jpg.',
                    'foto_id.max' => 'Ukuran foto melebihi :max MB.',
                    'foto_kartu_identitas_id.image' => 'Masukkan file foto dengan benar.',
                    'foto_kartu_identitas_id.mimes' => 'Format foto harus jpeg, png, jpg.',
                    'foto_kartu_identitas_id.max' => 'Ukuran foto melebihi :max MB.'
                ]
            );
            //CLOSE VALIDASI INPUTAN
            // proses fileProfil
            $fileFotoProfil = $request->foto_id;
            $ori_filenameFotoProfil =  $fileFotoProfil->getClientOriginalName();
            $ekstensiFotoProfil = $fileFotoProfil->getClientOriginalExtension();
            $filenameFotoProfil = $request->username . '_' . date('d-m-y') . '.' . $ekstensiFotoProfil;
            $thumbImageCover = Image::make($fileFotoProfil->getRealPath());
            $thumbImageCover->resize(300, 400, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $dokumenFotoProfil = new Dokumen();
            $dokumenFotoProfil->filename = $filenameFotoProfil;
            $dokumenFotoProfil->ori_filename = $ori_filenameFotoProfil;
            $dokumenFotoProfil->ekstensi = $ekstensiFotoProfil;
            $dokumenFotoProfil->type = 'foto_profil';
            $dokumenFotoProfil->jenis = 'upload';
            $dokumenFotoProfil->keterangan = 'Foto Profil anggota perpustakaan.';
            $dokumenFotoProfil->file_path = 'foto_profil/' . $filenameFotoProfil;
            $dokumenFotoProfil->save();

            // proses file kartu anggota
            $file = $request->foto_kartu_identitas_id;
            $ori_filename =  $file->getClientOriginalName();
            $ekstensi = $file->getClientOriginalExtension();
            $filename = $request->username . '_' . date('d-m-y') . '.' . $ekstensi;
            $dokumen = new Dokumen();
            $dokumen->filename = $filename;
            $dokumen->ori_filename = $ori_filename;
            $dokumen->ekstensi = $ekstensi;
            $dokumen->type = 'kartu_identitas';
            $dokumen->jenis = 'upload';
            $dokumen->keterangan = 'Kartu identitas anggota perpustakaan.';
            $dokumen->file_path = 'kartu_identitas/' . $filename;
            $dokumen->save();

            //Proses User
            $role = Role::get_dataByNama('anggota');
            $user = new User();
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password =  Hash::make($request->password);
            $user->role_id = $role->id;
            $user->is_active = true;
            $user->foto_id = $dokumenFotoProfil->id;
            $user->save();

            //Get Urutan Profil
            $nomorAnggota = ProfilAnggota::generateNomorAnggota(date("Y"));

            //Proses Profil
            $profilAnggota = new ProfilAnggota();
            $profilAnggota->nama_lengkap = $request->nama_lengkap;
            $profilAnggota->alamat = $request->alamat;
            $profilAnggota->tempat = $request->tempat;
            $profilAnggota->tanggal_lahir = $request->tanggal_lahir;
            $profilAnggota->jenis_kelamin = $request->jenis_kelamin;
            $profilAnggota->nomor_identitas = $request->nomor_identitas;
            $profilAnggota->email = $request->email;
            $profilAnggota->pekerjaan = $request->pekerjaan;
            $profilAnggota->nomor_telepon = $request->nomor_telepon;
            $profilAnggota->foto_kartu_identitas_id = $dokumen->id;
            $profilAnggota->user_id = $user->id;
            $profilAnggota->is_active = $request->isActive ?? false;
            $profilAnggota->is_verified = true;
            $profilAnggota->petugas_pj_verified = auth()->user()->profilPetugas->id;
            $profilAnggota->tanggal_verified = now();
            $profilAnggota->nomor_anggota = $nomorAnggota;
            $profilAnggota->save();

            //simpan file
            $fileFotoProfil->storeAs('foto_profil', $filenameFotoProfil, 'public');
            //simpan file
            $file->storeAs('kartu_identitas', $filename, 'public');

            activity()->performedOn($profilAnggota)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($profilAnggota)])->log('Daftar Akun dan Verifikasi');

            \DB::commit();
            return response()->json([
                'title' => 'Sukses!',
                'message' => 'Anggota telah berhasil dibuat.'
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
                'message' => 'Proses pembuatan anggota gagal proses, hubungi Penanggung Jawab Aplikasi' . $e->getMessage()
            ], 500);
        }
    }
    //Modal Edit ANGGOTA
    public function edit($param)
    {
        $id = decrypt($param);
        $anggota = ProfilAnggota::find($id);

        return view('pages.backend.keanggotaan.modal.edit', compact('anggota'));
    }

    //Proses Update Aggota
    public function update(Request $request)
    {
        try {
            \DB::beginTransaction();
            //OPEN VALIDASI INPUTAN
            $validate = $request->validate(
                [
                    'foto_id' => 'nullable|image|mimes:jpeg,png,jpg|max:5000',
                    'password' => [
                        'nullable',
                        'string',
                        'min:8',
                        new PasswordStrength,
                    ]
                ],
                [
                    'password.string' => 'Masukkan password dengan bener.',
                    'password.min' => 'Masukkan password minimal :min karakter.',
                    'foto_id.image' => 'Masukkan file foto dengan benar.',
                    'foto_id.mimes' => 'Format foto harus jpeg, png, jpg.',
                    'foto_id.max' => 'Ukuran foto melebihi :max MB.',
                ]
            );
            //CLOSE VALIDASI INPUTAN
            $id = decrypt($request->id);
            //Proses Profil
            $profilAnggota = ProfilAnggota::find($id);
            $profilAnggota->pekerjaan = $request->pekerjaan;
            $profilAnggota->is_active = $request->isActive ?? false;
            $profilAnggota->save();

            // proses fileProfil
            if ($request->foto_id != null) {
                //get foto profil old
                $fotoProfilOld = $profilAnggota->user->foto;
                if($fotoProfilOld != null){
                    Storage::disk('public')->delete($fotoProfilOld->file_path);
                }

                $fileFotoProfil = $request->foto_id;
                $ori_filenameFotoProfil =  $fileFotoProfil->getClientOriginalName();
                $ekstensiFotoProfil = $fileFotoProfil->getClientOriginalExtension();
                $filenameFotoProfil = $request->username . '_' . date('d-m-y') . '.' . $ekstensiFotoProfil;
                $thumbImageCover = Image::make($fileFotoProfil->getRealPath());
                $thumbImageCover->resize(300, 400, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $dokumenFotoProfil = new Dokumen();
                $dokumenFotoProfil->filename = $filenameFotoProfil;
                $dokumenFotoProfil->ori_filename = $ori_filenameFotoProfil;
                $dokumenFotoProfil->ekstensi = $ekstensiFotoProfil;
                $dokumenFotoProfil->type = 'foto_profil';
                $dokumenFotoProfil->jenis = 'upload';
                $dokumenFotoProfil->keterangan = 'Foto Profil anggota perpustakaan.';
                $dokumenFotoProfil->file_path = 'foto_profil/' . $filenameFotoProfil;
                $dokumenFotoProfil->save();
            }
            //Proses User
            $user = User::find($profilAnggota->user->id);
            if ($request->password != null) {
                $user->password =  Hash::make($request->password);
            }
            if ($request->foto_id != null) {
                $user->foto_id = $dokumenFotoProfil->id;
            }
            $user->save();
            if ($request->foto_id != null) {
                if($fotoProfilOld != null){
                    //hapus foto profil old
                    $fotoProfilOld->delete();
                }
                //simpan file
                $fileFotoProfil->storeAs('foto_profil', $filenameFotoProfil, 'public');
            }
            activity()->performedOn($profilAnggota)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($profilAnggota)])->log('Ubah Daftar Akun');

            \DB::commit();
            return response()->json([
                'title' => 'Sukses!',
                'message' => 'Anggota telah berhasil diubah.'
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
                'message' => 'Proses pembuatan anggota gagal proses, hubungi Penanggung Jawab Aplikasi.' . $e->getMessage()
            ], 500);
        }
    }
}
