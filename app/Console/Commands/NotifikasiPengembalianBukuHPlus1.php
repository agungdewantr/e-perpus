<?php

namespace App\Console\Commands;

use App\Models\Notifikasi;
use App\Models\Peminjaman;
use Illuminate\Console\Command;
use Pusher\Pusher;

class NotifikasiPengembalianBukuHPlus1 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifikasi-pengembalian-buku-h-plus-1';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push notification pengembalian buku lewat 1 hari .';

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
                                                ->where('tanggal_batas_pinjaman_perpanjangan', '=', now()->subDays(1));
                                    })
                                    ->orWhere(function($query){
                                        $query->where('is_persetujuan_permohoman_perpanjangan', false)
                                                ->orWhere('is_persetujuan_permohoman_perpanjangan', null)
                                                ->where('tanggal_batas_pinjaman', '=', now()->subDays(1));
                                    })
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
                'isi' => 'Peringatan batas pengembalian telah melewati tanggal yang telah ditentukan.',
                'route' => route('detailanggota.index', null)
            ]);
            $notifikasi = new Notifikasi();
            $notifikasi->user_id_from = $peminjaman->profilAnggota->user->id;
            $notifikasi->user_id_to = $peminjaman->profilAnggota->user->id;
            $notifikasi->tentang = 'Batas pengembalian';
            $notifikasi->route = route('detailanggota.index', null);
            $notifikasi->isi = 'Peringatan batas pengembalian telah melewati tanggal yang telah ditentukan.';
            $notifikasi->save();
        }
        $this->info('Notifikasi peringatan batas pengembalian telah melewati tanggal yang telah ditentukan berhasil dikirim.');
    }
}
