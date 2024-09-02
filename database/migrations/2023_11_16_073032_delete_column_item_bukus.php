<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteColumnItemBukus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_bukus', function (Blueprint $table) {
            $table->dropColumn('is_kelayakan');
            $table->dropColumn('tanggal_is_active');
            $table->dropColumn('is_active');

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
            $table->tinyInteger('is_kelayakan')->default(true);
            $table->tinyInteger('tanggal_is_active')->default(true);
            $table->tinyInteger('is_active')->default(true);
        });
    }
}
