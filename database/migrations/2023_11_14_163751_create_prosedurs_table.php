<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProsedursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prosedurs', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->unsignedBigInteger('dokumens_id');
            $table->foreign('dokumens_id')->references('id')->on('dokumens');
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
        Schema::dropIfExists('prosedurs');
    }
}
