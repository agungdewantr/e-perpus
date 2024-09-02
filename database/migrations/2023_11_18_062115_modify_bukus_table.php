<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyBukusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bukus', function (Blueprint $table) {
            $table->integer('ukuran')->nullable()->change();
            $table->string('narator')->nullable();
            $table->date('tanggal_terbit')->nullable();
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
            // $table->string('ukuran')->change();
            $table->dropColumn('narator');
            $table->dropColumn('tanggal_terbit');
        });
    }
}
