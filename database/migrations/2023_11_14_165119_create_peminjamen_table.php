<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeminjamenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peminjamen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_bukus_id');
            $table->foreign('item_bukus_id')->references('id')->on('item_bukus');
            $table->unsignedBigInteger('profil_anggota_id');
            $table->foreign('profil_anggota_id')->references('id')->on('profil_anggotas');
            $table->string('kode_nota_peminjaman');
            $table->tinyInteger('is_accepted');
            $table->date('tanggal_pengajuan_pinjaman');
            $table->date('tanggal_pengambilan_pinjaman')->nullable();
            $table->date('tanggal_batas_pinjaman')->nullable();
            $table->date('tanggal_pengembalian_pinjaman')->nullable();
            $table->date('is_permohoman_perpanjangan')->nullable();
            $table->date('tanggal_pengembalian_pinjaman_perpanjangan')->nullable();
            $table->unsignedBigInteger('petugas_pj_pengambilan_pinjaman_id')->nullable();
            $table->foreign('petugas_pj_pengambilan_pinjaman_id')->references('id')->on('profil_petugases');
            $table->unsignedBigInteger('petugas_pj_permohonan_perpanjangan_id')->nullable();
            $table->foreign('petugas_pj_permohonan_perpanjangan_id')->references('id')->on('profil_petugases');
            $table->unsignedBigInteger('petugas_pj_pengembalian_pinjaman_id')->nullable();
            $table->foreign('petugas_pj_pengembalian_pinjaman_id')->references('id')->on('profil_petugases');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('peminjamen');
    }
}
