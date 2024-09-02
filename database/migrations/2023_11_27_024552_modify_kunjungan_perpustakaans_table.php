<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyKunjunganPerpustakaansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kunjungan_perpustakaans', function (Blueprint $table) {
            $table->unsignedBigInteger('profil_anggota_id')->nullable()->change();
            $table->string('nama_lengkap')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kunjungan_perpustakaans', function (Blueprint $table) {
            $table->unsignedBigInteger('profil_anggota_id')->change();
            $table->dropColumn('nama_lengkap');
        });
    }
}
