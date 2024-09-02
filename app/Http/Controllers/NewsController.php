<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Berita;
use App\Models\Dokumen;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;
use Image;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Berita::with('gambar')->get())->addIndexColumn()->addColumn('gambar', function ($row) {
                return '<img src="' . asset('storage/' . $row->gambar->file_path) . '" alt="' . $row->judul . '" style="max-width:100px"/>';
            })->addColumn('tanggal', function ($row) {
                return Carbon::parse($row->created_at)->locale('id')->isoFormat('DD MMMM YYYY');
            })->addColumn('deskripsi', function ($row) {
                return Str::words($row->deskripsi, 20, '...');
            })->addColumn('status', function ($row) {
                if ($row->is_active) {
                    return '<span class="badge badge-center rounded-pill bg-success mb-1">Aktif</span>';
                }
                return '<span class="badge badge-center rounded-pill bg-danger mb-1">Tidak Aktif</span>';
            })->addColumn('action', function ($row) {
                return '<button type="button" class="btn btn-sm btn-outline-warning waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modalCenterLarge" onclick="show_edit(\'Edit Berita\',' . $row->id . ')">
                            <i class="fa fa-edit"></i>
                        </button>';
            })->rawColumns(['gambar', 'action', 'status'])->make();
        }
        return view('pages.backend.berita.index');
    }

    public function create()
    {
        return view('pages.backend.berita.modal.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'judul' => 'required',
            'gambar' => 'required|image|max:4096',
            'tanggal' => 'required',
            'deskripsi' => 'required'
        ]);

        $file = $validated['gambar'];
        $ext = $file->getClientOriginalExtension();
        $ori = $file->getClientOriginalName();
        $fileName = 'berita_' . time() . '.' . $ext;

        $thumbImageCover = Image::make($file->getRealPath());
        $thumbImageCover->resize(770, 360, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $thumbImageCover->resizeCanvas(770, 360, 'center', false, '#8acde9');
        $thumbImageCover->save(public_path('storage/berita/'. $fileName));

        $gambar = Dokumen::create([
            'filename' => $fileName,
            'ori_filename' => $ori,
            'ekstensi' => $ext,
            'type' => 'berita',
            'jenis' => 'upload',
            'keterangan' => 'Gambar Berita',
            'file_path' => 'berita/' . $fileName,
        ]);

        $news = Berita::create([
            'judul' => $validated['judul'],
            'gambar_id' => $gambar->id,
            'user_id' => auth()->user()->id,
            'deskripsi' => $validated['deskripsi'],
            'slug' => Str::slug($validated['judul']),
            'is_active' => true
        ]);

        activity()->performedOn($news)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($news)])->log('Buat Berita');
        return response()->json([
            'success' => true,
            'title' => 'Sukses',
            'message' => 'Berita berhasil disimpan'
        ]);
    }

    public function edit(Berita $news)
    {
        $news->load('gambar');
        return view('pages.backend.berita.modal.edit', compact('news'));
    }

    public function update(Berita $news, Request $request)
    {
        $validated = $this->validate($request, [
            'judul' => 'required',
            'gambar' => 'nullable|image|max:4096',
            'deskripsi' => 'required',
            'is_active' => 'nullable'
        ]);

        if ($request->hasFile('gambar')) {
            if (Storage::disk('public')->exists($news->gambar->file_path)) {
                Storage::disk('public')->delete($news->gambar->file_path);
            }
            $file = $validated['gambar'];
            $ext = $file->getClientOriginalExtension();
            $fileName = 'berita_' . time() . '.' . $ext;
            $thumbImageCover = Image::make($file->getRealPath());
            $thumbImageCover->resize(770, 360, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $thumbImageCover->resizeCanvas(770, 360, 'center', false, '#8acde9');
            $thumbImageCover->save(public_path('storage/berita/'. $fileName));
            $ori = $file->getClientOriginalName();

            $news->gambar->update([
                'filename' => $fileName,
                'ori_filename' => $ori,
                'ekstensi' => $ext,
                'file_path' => 'berita/' . $fileName,
            ]);
        }

        $news->update([
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'is_active' => isset($validated['is_active']),
            'user_id' => auth()->user()->id,
            'slug' => Str::slug($validated['judul'])
        ]);
        activity()->performedOn($news)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($news)])->log('Ubah Berita');
        return response()->json([
            'success' => true,
            'title' => 'Sukses',
            'message' => 'Berita berhasil diubah'
        ]);
    }
}
