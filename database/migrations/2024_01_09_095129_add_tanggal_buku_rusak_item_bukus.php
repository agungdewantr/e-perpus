<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTanggalBukuRusakItemBukus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_bukus', function (Blueprint $table) {
            $table->date('tanggal_buku_rusak')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_bukus', function (Blueprint $table) {
            $table->dropColumn('tanggal_buku_rusak');
        });
    }
}
