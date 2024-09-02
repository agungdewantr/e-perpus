<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemBukusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_bukus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bukus_id');
            $table->foreign('bukus_id')->references('id')->on('bukus');
            $table->string('kondisi');
            $table->string('kode_buku');
            $table->tinyInteger('is_kelayakan');
            $table->tinyInteger('is_tersedia');
            $table->tinyInteger('is_active');
            $table->text('keterangan_is_active')->nullable();
            $table->date('tanggal_is_active')->nullable();
            $table->date('tanggal_is_non_active')->nullable();
            $table->unsignedBigInteger('pengadaans_id')->nullable();
            $table->foreign('pengadaans_id')->references('id')->on('pengadaans');
            $table->date('tanggal_pengadaan');
            $table->double('harga')->nullable();
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
        Schema::dropIfExists('item_bukus');
    }
}
