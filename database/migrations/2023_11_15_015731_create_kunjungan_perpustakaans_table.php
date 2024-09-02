<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKunjunganPerpustakaansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kunjungan_perpustakaans', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_kunjungan');
            $table->unsignedBigInteger('profil_anggota_id');
            $table->foreign('profil_anggota_id')->references('id')->on('profil_anggotas');
            $table->unsignedBigInteger('petugas_pj_kunjungan_perpustakaan_id')->nullable();
            $table->foreign('petugas_pj_kunjungan_perpustakaan_id')->references('id')->on('profil_petugases');
            $table->string('keperluan')->nullable();
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
        Schema::dropIfExists('kunjungan_perpustakaans');
    }
}
