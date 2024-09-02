<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Acara;
use App\Models\Dokumen;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;
use Image;

class AgendaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Acara::with('gambar')->get())->addIndexColumn()->addColumn('gambar', function ($row) {
                return '<img src="' . asset('storage/' . $row->gambar->file_path) . '" alt="' . $row->judul . '" style="max-width:100px"/>';
            })->addColumn('tanggal', function ($row) {
                return Carbon::parse($row->created_at)->locale('id')->isoFormat('DD MMMM YYYY');
            })->addColumn('waktu', function ($row) {
                if (!$row->waktu_selesai) {
                    return $row->waktu_mulai . ' WITA';
                }
                return $row->waktu_mulai . '-' . $row->waktu_selesai . ' WITA';
            })->addColumn('peserta', function ($row) {
                if (!$row->peserta) {
                    return '-';
                }
                return $row->peserta;
            })->addColumn('deskripsi', function ($row) {
                if (!$row->deskripsi) {
                    return '-';
                }
                return Str::words($row->deskripsi, 20, '...');
            })->addColumn('status', function ($row) {
                if ($row->is_active) {
                    return '<span class="badge badge-center rounded-pill bg-success mb-1">Aktif</span>';
                }
                return '<span class="badge badge-center rounded-pill bg-danger mb-1">Tidak Aktif</span>';
            })->addColumn('action', function ($row) {
                return '<button type="button" class="btn btn-sm btn-outline-warning waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modalCenterLarge" onclick="show_edit(\'Edit Acara\',' . $row->id . ')">
                            <i class="fa fa-edit"></i>
                        </button>';
            })->rawColumns(['gambar', 'action', 'status'])->make();
        }

        return view('pages.backend.acara.index');
    }

    public function create()
    {
        return view('pages.backend.acara.modal.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'judul' => 'required',
            'gambar' => 'required|image|max:4096',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'nullable',
            'lokasi' => 'required',
            'peserta' => 'nullable',
            'deskripsi' => 'nullable|max:100',
        ]);

        $file = $validated['gambar'];
        $ext = $file->getClientOriginalExtension();
        $ori = $file->getClientOriginalName();
        $fileName = 'acara_' . time() . '.' . $ext;
        $thumbImageCover = Image::make($file->getRealPath());
        $thumbImageCover->resize(500, 250, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $thumbImageCover->resizeCanvas(500, 250, 'center', false, '#8acde9');
        $thumbImageCover->save(public_path('storage/acara/'. $fileName));
        $gambar = Dokumen::create([
            'filename' => $fileName,
            'ori_filename' => $ori,
            'ekstensi' => $ext,
            'type' => 'acara',
            'jenis' => 'upload',
            'keterangan' => 'Gambar acara',
            'file_path' => 'acara/' . $fileName,
        ]);

        $agenda = Acara::create([
            'judul' => $validated['judul'],
            'gambar_id' => $gambar->id,
            'deskripsi' => $validated['deskripsi'],
            'is_active' => true,
            'waktu_mulai' => $validated['waktu_mulai'],
            'waktu_selesai' => $validated['waktu_selesai'],
            'lokasi' => $validated['lokasi'],
            // 'peserta' => $validated['peserta'],
            'user_id' => auth()->user()->id,
            'slug' => Str::slug($validated['judul'])
        ]);

        activity()->performedOn($agenda)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($agenda)])->log('Buat Acara');
        return response()->json([
            'success' => true,
            'title' => 'Sukses',
            'message' => 'Acara berhasil disimpan'
        ]);
    }

    public function edit(Acara $agenda)
    {
        $agenda->load('gambar');
        return view('pages.backend.acara.modal.edit', compact('agenda'));
    }

    public function update(Acara $agenda, Request $request)
    {
        $validated = $this->validate($request, [
            'judul' => 'required',
            'gambar' => 'nullable|image|max:4096',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'nullable',
            'lokasi' => 'required',
            'peserta' => 'nullable',
            'deskripsi' => 'nullable',
            'is_active' => 'nullable'
        ]);

        $path = $agenda->gambar;

        if ($request->hasFile('gambar')) {
            if (Storage::disk('public')->exists($agenda->gambar->file_path)) {
                Storage::disk('public')->delete($agenda->gambar->file_path);
            }
            $file = $validated['gambar'];
            $ext = $file->getClientOriginalExtension();
            $fileName = 'acara_' . time() . '.' . $ext;
            $thumbImageCover = Image::make($file->getRealPath());
            $thumbImageCover->resize(500, 250, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $thumbImageCover->resizeCanvas(500, 250, 'center', false, '#8acde9');
            $thumbImageCover->save(public_path('storage/acara/'. $fileName));
            $ori = $file->getClientOriginalName();

            $agenda->gambar->update([
                'filename' => $fileName,
                'ori_filename' => $ori,
                'ekstensi' => $ext,
                'file_path' => 'acara/' . $fileName,
            ]);
        }

        $agenda->update([
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'is_active' => isset($validated['is_active']),
            'waktu_mulai' => $validated['waktu_mulai'],
            'waktu_selesai' => $validated['waktu_selesai'],
            'lokasi' => $validated['lokasi'],
            // 'peserta' => $validated['peserta'],
            'slug' => Str::slug($validated['judul']),
            'user_id' => auth()->user()->id
        ]);

        activity()->performedOn($agenda)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($agenda)])->log('Ubah Acara');
        return response()->json([
            'success' => true,
            'title' => 'Sukses',
            'message' => 'Acara berhasil diubah'
        ]);
    }
}
