<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\Prosedur;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;

class ProsedurController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Prosedur::with('dokumen')->get())->addIndexColumn()->addColumn('dokumen', function ($row) {
                return '<a href="' . asset('storage/' . $row->dokumen->file_path) . '" class="btn btn-sm btn-primary" target="_blank" noopener noreferer><i class="fas fa-eye"></i></a>';
            })->addColumn('status', function ($row) {
                if ($row->is_active) {
                    return '<span class="badge badge-center rounded-pill bg-success mb-1">Aktif</span>';
                }
                return '<span class="badge badge-center rounded-pill bg-danger mb-1">Tidak Aktif</span>';
            })->addColumn('action', function ($row) {
                return '<button type="button" class="btn btn-sm btn-outline-warning waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#modalCenterLarge" onclick="show_edit(\'Edit Acara\',' . $row->id . ')">
                            <i class="fa fa-edit"></i>
                        </button>';
            })->rawColumns(['dokumen', 'action', 'status'])->make();
        }

        return view('pages.backend.prosedur.index');
    }

    public function create()
    {
        return view('pages.backend.prosedur.modal.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'judul' => 'required',
            'dokumen' => 'required|mimes:pdf|max:4096',
        ]);

        $file = $validated['dokumen'];
        $ext = $file->getClientOriginalExtension();
        $ori = $file->getClientOriginalName();
        $fileName = 'prosedur_' . time() . '.' . $ext;
        $file->storeAs('prosedur', $fileName, 'public');

        $dokumen = Dokumen::create([
            'filename' => $fileName,
            'ori_filename' => $ori,
            'ekstensi' => $ext,
            'type' => 'prosedur',
            'jenis' => 'upload',
            'keterangan' => 'Prosedur',
            'file_path' => 'prosedur/' . $fileName,
        ]);

        $prosedur = Prosedur::create([
            'judul' => $validated['judul'],
            'dokumens_id' => $dokumen->id,
        ]);

        activity()->performedOn($prosedur)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($prosedur)])->log('Buat Prosedur');
        return response()->json([
            'success' => true,
            'title' => 'Sukses',
            'message' => 'Prosedur berhasil disimpan'
        ]);
    }

    public function edit(Prosedur $prosedur)
    {
        $prosedur->load('dokumen');
        return view('pages.backend.prosedur.modal.edit', compact('prosedur'));
    }

    public function update(Prosedur $prosedur, Request $request)
    {
        $validated = $this->validate($request, [
            'judul' => 'required',
            'dokumen' => 'nullable|mimes:pdf|max:4096',
            'is_active' => 'nullable'
        ]);

        $prosedur->load('dokumen');
        if ($request->hasFile('dokumen')) {
            if (Storage::disk('public')->exists($prosedur->dokumen->file_path)) {
                Storage::disk('public')->delete($prosedur->dokumen->file_path);
            }
            $file = $validated['dokumen'];
            $ext = $file->getClientOriginalExtension();
            $fileName = 'prosedur_' . time() . '.' . $ext;
            $file->storeAs('prosedur', $fileName, 'public');
            $ori = $file->getClientOriginalName();

            $prosedur->dokumen->update([
                'filename' => $fileName,
                'ori_filename' => $ori,
                'ekstensi' => $ext,
                'file_path' => 'prosedur/' . $fileName,
            ]);
        }

        $prosedur->update([
            'judul' => $validated['judul'],
            'is_active' => isset($validated['is_active'])
        ]);

        activity()->performedOn($prosedur)->withProperties(['ip' => $this->get_client_ip(), 'data' => json_encode($prosedur)])->log('Ubah Prosedur');
        return response()->json([
            'success' => true,
            'title' => 'Sukses',
            'message' => 'Prosedur berhasil diubah'
        ]);
    }
}
