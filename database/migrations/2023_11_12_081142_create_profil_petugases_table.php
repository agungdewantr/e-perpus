<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilPetugasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profil_petugases', function (Blueprint $table) {
            $table->id();
            $table->string('nip')->nullable();
            $table->string('nama_lengkap');
            $table->string('nomor_telepon');
            $table->time('jadwal_shift');
            $table->boolean('is_active');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('profil_petugases');
    }
}
