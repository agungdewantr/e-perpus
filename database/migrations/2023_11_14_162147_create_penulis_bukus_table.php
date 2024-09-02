<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenulisBukusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penulis_bukus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bukus_id');
            $table->foreign('bukus_id')->references('id')->on('bukus');
            $table->unsignedBigInteger('penulies');
            $table->foreign('penulies')->references('id')->on('penulis');
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
        Schema::dropIfExists('penulis_bukus');
    }
}
