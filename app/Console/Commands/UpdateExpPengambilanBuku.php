<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peminjaman;
class UpdateExpPengambilanBuku extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-exp-pengambilan-buku';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status pengambilan buku yang telah melewati batas waktu pengambilan';

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
        $peminjamans = Peminjaman::where('tanggal_pengajuan_pinjaman', '<', now()->subDays(2))->where('status', 'Belum Diambil')->get();

        foreach ($peminjamans as $peminjaman) {
            $peminjaman->status = 'Lewat Batas Waktu Pengambilan';
            $peminjaman->save();
            activity()->performedOn($peminjaman)->withProperties(['ip' => 'admin', 'data' => json_encode($peminjaman)])->log('Lewat Batas Waktu Pinjaman');
        }

        $this->info('Status peminjaman berhasil diperbarui.');
    }
}
