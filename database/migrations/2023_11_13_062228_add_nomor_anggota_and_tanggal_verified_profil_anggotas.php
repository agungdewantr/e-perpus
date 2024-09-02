<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNomorAnggotaAndTanggalVerifiedProfilAnggotas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profil_anggotas', function (Blueprint $table) {
            $table->string('nomor_anggota')->nullable();
            $table->date('tanggal_verified')->nullable()->after('petugas_pj_verified_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profil_anggotas', function (Blueprint $table) {
            $table->dropColumn('nomor_anggota');
            $table->dropColumn('tanggal_verified');
        });
    }
}
