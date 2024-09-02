<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameIsPermohomanPerpanjanganColumnInPeminjamansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->renameColumn('is_permohoman_perpanjangan', 'is_permohonan_perpanjangan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->renameColumn('is_permohonan_perpanjangan', 'is_permohoman_perpanjangan');
        });
    }
}
