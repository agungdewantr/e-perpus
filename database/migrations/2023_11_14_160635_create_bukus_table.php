<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBukusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bukus', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('isbn')->nullable();
            $table->string('judul');
            $table->string('jenis');
            $table->unsignedBigInteger('penerbit_id')->nullable();
            $table->foreign('penerbit_id')->references('id')->on('penerbits');
            $table->year('tahun_cetak')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('bahasa')->nullable();
            $table->integer('ukuran');
            $table->integer('jumlah_halaman')->nullable();
            $table->string('cetak_cover')->nullable();
            $table->unsignedBigInteger('raks_id')->nullable();
            $table->foreign('raks_id')->references('id')->on('raks');
            $table->string('cetak_isi')->nullable();
            $table->string('kertas_isi')->nullable();
            $table->unsignedBigInteger('cover_id');
            $table->foreign('cover_id')->references('id')->on('dokumens');
            $table->unsignedBigInteger('file_digital_id')->nullable();
            $table->foreign('file_digital_id')->references('id')->on('dokumens');
            $table->unsignedBigInteger('file_audio_id')->nullable();
            $table->foreign('file_audio_id')->references('id')->on('dokumens');
            $table->date('deleted_at')->nullable();
            $table->tinyInteger('is_active');
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
        Schema::dropIfExists('bukus');
    }
}
