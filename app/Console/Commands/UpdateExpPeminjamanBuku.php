<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peminjaman;

class UpdateExpPeminjamanBuku extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-exp-peminjaman-buku';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status pengambilan buku yang telah melewati batas waktu peminjaman';

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
                                                ->where('tanggal_batas_pinjaman_perpanjangan', '<', now());
                                    })
                                    ->orWhere(function($query){
                                        $query->where('is_persetujuan_permohoman_perpanjangan', false)
                                                ->orWhere('is_persetujuan_permohoman_perpanjangan', null)
                                                ->where('tanggal_batas_pinjaman', '<', now());
                                    })
                                    ->where('status', 'Sedang Dipinjam')
                                    ->get();

        foreach ($peminjamans as $peminjaman) {
            $peminjaman->status = 'Belum Kembali';
            $peminjaman->save();
            activity()->performedOn($peminjaman)->withProperties(['ip' => 'admin', 'data' => json_encode($peminjaman)])->log('Lewat Batas Waktu Pinjaman');
        }

        $this->info('Status peminjaman berhasil diperbarui.');
    }
}
