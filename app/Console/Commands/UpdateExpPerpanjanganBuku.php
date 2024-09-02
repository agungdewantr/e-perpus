<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peminjaman;

class UpdateExpPerpanjanganBuku extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-exp-perpanjangan-buku';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status perpanjangan buku yang telah melewati batas waktu peminjaman';

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
        $peminjamans = Peminjaman::where('is_permohonan_perpanjangan', true)
                                    ->where('is_persetujuan_permohoman_perpanjangan', null)
                                    ->where('tanggal_batas_pinjaman', '<', now())
                                    ->get();
        foreach ($peminjamans as $peminjaman) {
            $peminjaman->is_persetujuan_permohoman_perpanjangan = false;
            $peminjaman->save();
            activity()->performedOn($peminjaman)->withProperties(['ip' => 'admin', 'data' => json_encode($peminjaman)])->log('Perpanjangan tidak direspon dan telah melewati batas waktu peminjaman.');
        }
        return 0;
    }
}
