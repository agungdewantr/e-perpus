<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\BukuTelahDibaca;
use App\Models\ItemBuku;
use App\Models\KunjunganPerpustakaan;
use App\Models\Peminjaman;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
// use PDF;
use Yajra\DataTables\DataTables;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if ($request->exists('jenis_laporan')) {
                if ($request->get('jenis_laporan') == 'Laporan Buku') {
                    $buku = Buku::with('penulises', 'kategories', 'penerbit', 'rak', 'subKategori', 'itemBukus')->get();

                    return DataTables::of($buku)->addIndexColumn()->addColumn('kode', function ($row) {
                        // $subKategori = $row->subKategori;
                        // $penulis = strtoupper(substr($row->penulises[0]->nama, 0, 3));
                        // $penerbit = strtolower(substr($row->penerbit->namaPenerbit, 0, 1));
                        // $value = $subKategori->kode . ' ' . $penulis . ' ' . $penerbit;
                        $value = $row->kode_buku;
                        return $value;
                    })->addColumn('rak', function ($row) {
                        if ($row->rak) {
                            return $row->rak->kode;
                        }
                        return '-';
                    })->addColumn('penulis', function ($row) {
                        return $row->penulises->pluck('nama')->join(', ');
                    })->addColumn('penerbit', function ($row) {
                        return $row->penerbit->namaPenerbit;
                    })->addColumn('tgl_terbit', function ($row) {
                        return Carbon::parse($row->created_at)->isoFormat('DD MMMM YYYY');
                    })->addColumn('jumlah', function ($row) {
                        if (count($row->itemBukus) > 0) {
                            $jumlahTersedia = count($row->itemBukus);
                            return $jumlahTersedia;
                        } else {
                            return '1';
                        }
                    })->make();
                }
                if ($request->get('jenis_laporan') == 'Laporan Peminjaman') {
                    $peminjaman = Peminjaman::with('profilAnggota', 'itemBuku.buku.subKategori', 'itemBuku.buku.penulises', 'itemBuku.buku.penerbit')->when($request->get('bulan') != null, function ($query) use ($request) {
                        $query->where('tanggal_pengambilan_pinjaman', 'like', '%-' . $request->get('bulan') . '-%');
                    })->when($request->get('tahun') != null, function ($query) use ($request) {
                        $query->where('tanggal_pengambilan_pinjaman', 'like', '%' . $request->get('tahun') . '-%');
                    })->get();

                    return DataTables::of($peminjaman)->addIndexColumn()->addColumn('nomor_anggota', function ($row) {
                        return $row->profilAnggota->nomor_anggota;
                    })->addColumn('nama', function ($row) {
                        return $row->profilAnggota->nama_lengkap;
                    })->addColumn('kode_buku', function ($row) {
                        $buku = $row->itemBuku->buku;
                        $penulis = strtoupper(substr($buku->penulises[0]->nama, 0, 3));
                        $penerbit = strtolower(substr($buku->penerbit->namaPenerbit, 0, 1));
                        $value = $buku->subKategori->kode . ' ' . $penulis . ' ' . $penerbit;
                        return $value;
                    })->addColumn('judul', function ($row) {
                        return $row->itemBuku->buku->judul;
                    })->addColumn('tgl_peminjaman', function ($row) {
                        if ($row->tanggal_pengambilan_pinjaman) {
                            return Carbon::parse($row->tanggal_pengambilan_pinjaman)->isoFormat('DD MMMM YYYY');
                        }
                        return "-";
                    })->addColumn('tgl_batas_kembali', function ($row) {
                        if ($row->tanggal_batas_pinjaman) {
                            if ($row->is_persetujuan_permohoman_perpanjangan == true) {
                                return Carbon::parse($row->tanggal_batas_pinjaman_perpanjangan)->isoFormat('DD MMMM YYYY');
                            }
                            return Carbon::parse($row->tanggal_batas_pinjaman)->isoFormat('DD MMMM YYYY');
                        }
                        return "-";
                    })->addColumn('tgl_pengembalian', function ($row) {
                        if ($row->tanggal_pengembalian_pinjaman) {
                            return Carbon::parse($row->tanggal_pengembalian_pinjaman)->isoFormat('DD MMMM YYYY');
                        }
                        return '-';
                    })->make();
                }
                if ($request->get('jenis_laporan') == 'Laporan Pengunjung') {
                    $pengunjung = KunjunganPerpustakaan::with('profilAnggota')->when($request->get('bulan') != null, function ($query) use ($request) {
                        $query->where('tanggal_kunjungan', 'like', '%-' . $request->get('bulan') . '-%');
                    })->when($request->get('tahun') != null, function ($query) use ($request) {
                        $query->where('tanggal_kunjungan', 'like', '%' . $request->get('tahun') . '-%');
                    })->get();

                    return DataTables::of($pengunjung)->addIndexColumn()->addColumn('nomor_anggota', function ($row) {
                        return $row->profilAnggota->nomor_anggota ?? "-";
                    })->addColumn('nama', function ($row) {
                        return $row->profilAnggota->nama_lengkap ?? $row->nama_lengkap;
                    })->make();
                }
                if ($request->get('jenis_laporan') == 'Laporan Buku Belum Kembali') {
                    $peminjaman = Peminjaman::with(['profilAnggota', 'itemBuku.buku.subKategori', 'itemBuku.buku.penerbit', 'itemBuku.buku.penulises'])->where('status', '!=', 'Lewat Batas Waktu Pengambilan')->when($request->get('bulan') != null, function ($query) use ($request) {
                        $query->where('tanggal_batas_pinjaman', 'like', '%-' . $request->get('bulan') . '-%');
                    })->when($request->get('tahun') != null, function ($query) use ($request) {
                        $query->where('tanggal_batas_pinjaman', 'like', '%' . $request->get('tahun') . '-%');
                    })->get();

                    return DataTables::of($peminjaman)->addIndexColumn()->addColumn('nomor_anggota', function ($row) {
                        return $row->profilAnggota->nomor_anggota;
                    })->addColumn('nama', function ($row) {
                        return $row->profilAnggota->nama_lengkap;
                    })->addColumn('kode_buku', function ($row) {
                        $buku = $row->itemBuku->buku;
                        $penulis = strtoupper(substr($buku->penulises[0]->nama, 0, 3));
                        $penerbit = strtolower(substr($buku->penerbit->namaPenerbit, 0, 1));
                        $value = $buku->subKategori->kode . ' ' . $penulis . ' ' . $penerbit;
                        return $value;
                    })->addColumn('judul', function ($row) {
                        return $row->itemBuku->buku->judul;
                    })->make();
                }
                if ($request->get('jenis_laporan') == 'Laporan Buku Rusak') {
                    $buku = Buku::with('penulises', 'penerbit')
                    ->leftJoin('item_bukus', 'bukus.id', '=', 'item_bukus.bukus_id')
                    ->select('bukus.*', \DB::raw('COUNT(item_bukus.bukus_id) as jumlah'))
                    ->where('item_bukus.kondisi', '=', 'Rusak')
                    ->when($request->get('tahun') != null, function ($query) use ($request) {
                        $query->whereYear('item_bukus.tanggal_buku_rusak', $request->get('tahun'))
                              ->when($request->get('bulan') != null, function ($innerQuery) use ($request) {
                                  $innerQuery->whereMonth('item_bukus.tanggal_buku_rusak', $request->get('bulan'));
                              });
                    })
                    ->groupBy('bukus.id')
                    ->get();



                    // $buku = Buku::with('penulises', 'kategories', 'penerbit', 'rak', 'subKategori', 'itemBukus')
                    // ->whereHas('itemBukus', function ($query) {
                    //     $query->where('kondisi', 'Rusak');
                    // })->when($request->get('tahun') != null, function ($query) use ($request) {
                    //     $query->where('tanggal_pengambilan_pinjaman', 'like', '%' . $request->get('tahun') . '-%');
                    // })->get();


                    return DataTables::of($buku)->addIndexColumn()->addColumn('kode', function ($row) {
                        // $subKategori = $row->subKategori;
                        // $penulis = strtoupper(substr($row->penulises[0]->nama, 0, 3));
                        // $penerbit = strtolower(substr($row->penerbit->namaPenerbit, 0, 1));
                        // $value = $subKategori->kode . ' ' . $penulis . ' ' . $penerbit;
                        $value = $row->kode_buku;
                        return $value;
                    })->addColumn('rak', function ($row) {
                        if ($row->rak) {
                            return $row->rak->kode;
                        }
                        return '-';
                    })->addColumn('penulis', function ($row) {
                        return $row->penulises->pluck('nama')->join(', ');
                    })->addColumn('penerbit', function ($row) {
                        return $row->penerbit->namaPenerbit;
                    })->addColumn('tgl_terbit', function ($row) {
                        return Carbon::parse($row->created_at)->isoFormat('DD MMMM YYYY');
                    })->addColumn('jumlah', function ($row) {
                        return $row->jumlah;
                    })->make();
                }
                if ($request->get('jenis_laporan') == 'Laporan Buku Telah Dibaca') {
                    $buku = BukuTelahDibaca::with('buku')
                            ->when($request->get('tahun') != null, function ($query) use ($request) {
                        $query->whereYear('buku_telah_dibacas.tanggal', $request->get('tahun'))
                              ->when($request->get('bulan') != null, function ($innerQuery) use ($request) {
                                  $innerQuery->whereMonth('buku_telah_dibacas.tanggal', $request->get('bulan'));
                              });
                    })->get();

                    return DataTables::of($buku)->addIndexColumn()->addColumn('buku', function ($row) {
                        $value = $row->buku->judul;
                        return $value;
                    })->addColumn('tanggal', function ($row) {
                        return Carbon::parse($row->tanggal)->isoFormat('DD MMMM YYYY');
                    })->make();
                }
            }
        }
        return view('pages.backend.laporan.index');
    }

    public function export(Request $request)
    {
        if ($request->exists('laporan')) {
            if ($request->get('laporan') == 'Laporan Buku') {
                $buku = Buku::with('penulises', 'kategories', 'itemBukus', 'penerbit', 'rak', 'subKategori')->get();
                foreach ($buku as $b) {
                    // $subKategori = $b->subKategori;
                    // $penulis = strtoupper(substr($b->penulises[0]->nama, 0, 3));
                    // $penerbit = strtolower(substr($b->penerbit->namaPenerbit, 0, 1));
                    // $value = $subKategori->kode . ' ' . $penulis . ' ' . $penerbit;
                    // $b->setAttribute('kode_buku', $value);
                    if (count($b->itemBukus) > 0) {
                        $jumlahTersedia = count($b->itemBukus);
                        $b->setAttribute('jumlah', $jumlahTersedia);
                    } else {
                        $b->setAttribute('jumlah', "1");;
                    }
                }

                $pdf = PDF::loadView('pages.backend.laporan.pdf', ['data' => $buku, 'laporan' => $request->get('laporan')]);
                $pdf->setPaper('A4', 'landscape');
                return $pdf->download('laporan-buku.pdf');
            }
            if ($request->get('laporan') == 'Laporan Peminjaman') {
                $peminjaman = Peminjaman::with(['profilAnggota', 'itemBuku.buku.subKategori', 'itemBuku.buku.penerbit', 'itemBuku.buku.penulises'])->where('status', '!=', 'Lewat Batas Waktu Pengambilan')->when($request->get('bulan') != null, function ($query) use ($request) {
                    $query->where('tanggal_pengambilan_pinjaman', 'like', '%-' . $request->get('bulan') . '-%');
                })->when($request->get('tahun') != null, function ($query) use ($request) {
                    $query->where('tanggal_pengambilan_pinjaman', 'like', '%' . $request->get('tahun') . '-%');
                })->get();

                foreach ($peminjaman as $p) {
                    $buku = $p->itemBuku->buku;
                    $penulis = strtoupper(substr($buku->penulises[0]->nama, 0, 3));
                    $penerbit = strtolower(substr($buku->penerbit->namaPenerbit, 0, 1));
                    $value = $buku->subKategori->kode . ' ' . $penulis . ' ' . $penerbit;
                    $p->setAttribute('kode_buku', $value);
                }

                $pdf = Pdf::loadView('pages.backend.laporan.pdf', ['data' => $peminjaman, 'laporan' => $request->get('laporan')]);
                $pdf->setPaper('A4', 'landscape');
                $fileName = 'laporan-peminjaman';
                if ($request->exists('bulan')) {
                    $fileName .= '-' . date("F", mktime(0, 0, 0, intval($request->get('bulan')), 1));
                }
                if ($request->exists('tahun')) {
                    $fileName .= '-' . $request->get('tahun');
                }
                return $pdf->download($fileName . '.pdf');
            }
            if ($request->get('laporan') == 'Laporan Pengunjung') {
                $pengunjung = KunjunganPerpustakaan::with('profilAnggota')->when($request->get('bulan') != null, function ($query) use ($request) {
                    $query->where('tanggal_kunjungan', 'like', '%-' . $request->get('bulan') . '-%');
                })->when($request->get('tahun') != null, function ($query) use ($request) {
                    $query->where('tanggal_kunjungan', 'like', '%' . $request->get('tahun') . '-%');
                })->get();

                $pdf = PDF::loadView('pages.backend.laporan.pdf', ['data' => $pengunjung, 'laporan' => $request->get('laporan')]);
                $pdf->setPaper('A4', 'landscape');
                $fileName = 'laporan-pengunjung';
                if ($request->exists('bulan')) {
                    $fileName .= '-' . date("F", mktime(0, 0, 0, intval($request->get('bulan')), 1));
                }
                if ($request->exists('tahun')) {
                    $fileName .= '-' . $request->get('tahun');
                }
                return $pdf->download($fileName . '.pdf');
            }
            if ($request->get('laporan') == 'Laporan Buku Belum Kembali') {
                $peminjaman = Peminjaman::with(['profilAnggota', 'itemBuku.buku.subKategori', 'itemBuku.buku.penerbit', 'itemBuku.buku.penulises'])->where('status', '!=', 'Lewat Batas Waktu Pengambilan')->when($request->get('bulan') != null, function ($query) use ($request) {
                    $query->where('tanggal_batas_pinjaman', 'like', '%' . $request->get('bulan') . '%');
                })->when($request->get('tahun') != null, function ($query) use ($request) {
                    $query->where('tanggal_batas_pinjaman', 'like', '%' . $request->get('tahun') . '%');
                })->get();

                foreach ($peminjaman as $p) {
                    $buku = $p->itemBuku->buku;
                    $penulis = strtoupper(substr($buku->penulises[0]->nama, 0, 3));
                    $penerbit = strtolower(substr($buku->penerbit->namaPenerbit, 0, 1));
                    $value = $buku->subKategori->kode . ' ' . $penulis . ' ' . $penerbit;
                    $p->setAttribute('kode_buku', $value);
                }

                $pdf = PDF::loadView('pages.backend.laporan.pdf', ['data' => $peminjaman, 'laporan' => $request->get('laporan')]);
                $pdf->setPaper('A4', 'landscape');
                $fileName = 'laporan-buku-belum-kembali';
                if ($request->exists('bulan')) {
                    $fileName .= '-' . date("F", mktime(0, 0, 0, intval($request->get('bulan')), 1));
                }
                if ($request->exists('tahun')) {
                    $fileName .= '-' . $request->get('tahun');
                }
                return $pdf->download($fileName . '.pdf');
            }
            if ($request->get('laporan') == 'Laporan Buku Rusak') {
                $buku = Buku::with('penulises', 'penerbit')
                ->leftJoin('item_bukus', 'bukus.id', '=', 'item_bukus.bukus_id')
                ->select('bukus.*', \DB::raw('COUNT(item_bukus.bukus_id) as jumlah'))
                ->where('item_bukus.kondisi', '=', 'Rusak')
                ->when($request->get('tahun') != null, function ($query) use ($request) {
                    $query->whereYear('item_bukus.tanggal_buku_rusak', $request->get('tahun'))
                          ->when($request->get('bulan') != null, function ($innerQuery) use ($request) {
                              $innerQuery->whereMonth('item_bukus.tanggal_buku_rusak', $request->get('bulan'));
                          });
                })
                ->groupBy('bukus.id')
                ->get();
                if ($buku->isEmpty()) {
                    // Response dengan alert
                    return redirect()->back();
                }
                foreach ($buku as $b) {
                    $b->setAttribute('jumlah', $b->jumlah);
                }

                $pdf = PDF::loadView('pages.backend.laporan.pdf', ['data' => $buku, 'laporan' => $request->get('laporan')]);
                $pdf->setPaper('A4', 'landscape');
                return $pdf->download('laporan-buku-rusak.pdf');
            }
            if ($request->get('laporan') == 'Laporan Buku Telah Dibaca') {
                // $laporan = BukuTelahDibaca::with('buku')
                $buku = BukuTelahDibaca::with('buku')
                ->when($request->get('tahun') != null, function ($query) use ($request) {
                    $query->whereYear('buku_telah_dibacas.tanggal', $request->get('tahun'))
                        ->when($request->get('bulan') != null, function ($innerQuery) use ($request) {
                            $innerQuery->whereMonth('buku_telah_dibacas.tanggal', $request->get('bulan'));
                        });
                })->get();
                if ($buku->isEmpty()) {
                    // Response dengan alert
                    return redirect()->back();
                }

                $pdf = PDF::loadView('pages.backend.laporan.pdf', ['data' => $buku, 'laporan' => $request->get('laporan')]);
                $pdf->setPaper('A4', 'landscape');
                return $pdf->download('laporan-buku-telah-dibaca.pdf');
            }
        }

        return redirect()->back();
    }
}
