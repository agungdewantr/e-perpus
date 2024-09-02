<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubKategoriIdBukus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bukus', function (Blueprint $table) {
            $table->unsignedBigInteger('sub_kategori_id');
            $table->foreign('sub_kategori_id')->references('id')->on('sub_kategoris');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bukus', function (Blueprint $table) {
            $table->dropForeign(['sub_kategori_id']);
            $table->dropColumn('sub_kategori_id');
        });
    }
}
