<?php

namespace App\Console\Commands;

use App\Models\Notifikasi;
use App\Models\Peminjaman;
use Illuminate\Console\Command;
use Pusher\Pusher;

class NotifikasiPengembalianBukuHMin2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifikasi-pengembalian-buku-h-min-2';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push notification pengembalian buku kurang 2 hari lagi.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $peminjamans = Peminjaman::where(function($query){
                                        $query->where('is_persetujuan_permohoman_perpanjangan', true)
                                                ->where('tanggal_batas_pinjaman_perpanjangan', '=', now()->addDays(2));
                                    })
                                    ->orWhere(function($query){
                                        $query->where('is_persetujuan_permohoman_perpanjangan', false)
                                                ->orWhere('is_persetujuan_permohoman_perpanjangan', null)
                                                ->where('tanggal_batas_pinjaman', '=', now()->addDays(2));
                                    })
                                    ->where('status', 'Sedang Dipinjam')
                                    ->get();
        foreach($peminjamans as $peminjaman){
            $options = [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => true
            ];

            $pusher = new Pusher(
                env('PUSHER_APP_KEY'),
                env('PUSHER_APP_SECRET'),
                env('PUSHER_APP_ID'),
                $options
            );
            $pusher->trigger($peminjaman->profilAnggota->user->id . '-notification', 'notify', [
                'message' => 'Batas Pengembalian',
                'isi' => 'Peringatan batas pengembalian 2 hari lagi.',
                'route' => route('detailanggota.index', null)
            ]);
            $notifikasi = new Notifikasi();
            $notifikasi->user_id_from = $peminjaman->profilAnggota->user->id;
            $notifikasi->user_id_to = $peminjaman->profilAnggota->user->id;
            $notifikasi->tentang = 'Batas pengembalian';
            $notifikasi->route = route('detailanggota.index', null);
            $notifikasi->isi = 'Peringatan batas pengembalian 2 hari lagi.';
            $notifikasi->save();
        }
        $this->info('Notifikasi peringatan batas pengembalian 2 hari lagi berhasil dikirim.');

    }
}
