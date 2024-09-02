<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeBerita extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->dropColumn('profil_petugas_id');
        });
        Schema::table('beritas', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained()->on('users')->references('id')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
        Schema::table('beritas', function (Blueprint $table) {
            $table->unsignedBigInteger('profil_petugas_id');
            $table->foreign('profil_petugas_id')->references('id')->on('profil_petugases');
        });
    }
}
