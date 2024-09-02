<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsActiveAndIsVerifiedProfilAnggotas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profil_anggotas', function (Blueprint $table) {
            $table->boolean('is_active')->after('nomor_telepon');
            $table->boolean('is_verified')->after('is_active')->nullable();
            $table->unsignedBigInteger('petugas_pj_verified')->nullable();
            $table->foreign('petugas_pj_verified')->references('id')->on('profil_petugases');
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
            $table->dropColumn('is_active');
            $table->dropColumn('is_verified');
            $table->dropForeign(['petugas_pj_verified']);
            $table->dropColumn('petugas_pj_verified');
        });
    }
}
