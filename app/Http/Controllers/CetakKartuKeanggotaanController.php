<?php

namespace App\Http\Controllers;

use DataTables;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\ProfilAnggota;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

class CetakKartuKeanggotaanController extends Controller
{
    public function index()
    {

        return view('pages.backend.cetakkartukeanggotaan.index');
    }
    public function datatable(Request $request)
    {
        $isActive = $request->input('is_active');
        // $excludedIds = [1, 2, 3,4,5,6,7,8,9,10,11,12];

        $query = ProfilAnggota::get_dataIsVerified(1);
        if ($isActive != null) {
            $query->where('is_active', $isActive);
        }
        $anggotas = $query->get();
        return Datatables::of($anggotas)
            ->addColumn('ttl', function ($row) {
                $formattedDate = Carbon::parse($row->tanggal_lahir)->isoFormat('DD MMM YYYY');
                $ttl = $row->tempat . ', ' . $formattedDate;
                return $ttl;
            })
            ->addColumn('created_at', function ($row) {
                $formattedDate = Carbon::parse($row->created_at)->isoFormat('DD MMM YYYY');
                return $formattedDate;
            })
            ->addColumn('id', function ($row) {
                $id = encrypt($row->id);
                return $id;
            })
            ->make();
    }
    public function detail($param)
    {
        $id = decrypt($param);
        $profilAnggota = ProfilAnggota::with('user.foto')->find($id);
        return view('pages.backend.cetakkartukeanggotaan.modal.detail', compact('profilAnggota'));
    }

    public function download(ProfilAnggota $profilAnggota)
    {
        $profilAnggota->load('user.foto');
        $tgl_lahir = Carbon::parse($profilAnggota->tanggal_lahir)->locale('id')->isoFormat('D MMMM Y');
        $tgl_verified = $profilAnggota->tanggal_verified ? Carbon::parse($profilAnggota->tanggal_verified)->locale('id')->isoFormat('D MMMM Y') : '-';
        $tgl_berlaku = '-';
        $pdf = FacadePdf::loadview('pages.backend.cetakkartukeanggotaan.pdf', ['tgl_lahir' => $tgl_lahir, 'tgl_verified' => $tgl_verified, 'profilAnggota' => $profilAnggota, 'tgl_berlaku' => $tgl_berlaku]);
        return $pdf->download();
    }
}
