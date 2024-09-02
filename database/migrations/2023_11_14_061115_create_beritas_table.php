<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeritasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('beritas', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->unsignedBigInteger('profil_petugas_id');
            $table->foreign('profil_petugas_id')->references('id')->on('profil_petugases');
            $table->unsignedBigInteger('gambar_id');
            $table->foreign('gambar_id')->references('id')->on('dokumens');
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
        Schema::dropIfExists('beritas');
    }
}
