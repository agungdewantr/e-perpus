<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeUkuranToStringBukus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bukus', function (Blueprint $table) {
            $table->string('ukuran')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // DB::statement('UPDATE bukus SET ukuran = null');
        // DB::statement('ALTER TABLE bukus ALTER COLUMN ukuran TYPE VARCHAR USING ukuran::integer');
        // Schema::table('bukus', function (Blueprint $table) {
        //     $table->integer('ukuran')->change();
        // });
    }
}
