<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeJadwalShiftProfilPetugases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profil_petugases', function (Blueprint $table) {
            $table->dropColumn('jadwal_shift');
            $table->time('jadwal_shift_mulai')->nullable();
            $table->time('jadwal_shift_selesai')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profil_petugases', function (Blueprint $table) {
            $table->date('jadwal_shift')->default(now());
            $table->dropColumn('jadwal_shift_mulai');
            $table->dropColumn('jadwal_shift_selesai');
        });
    }
}
