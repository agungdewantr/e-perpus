<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('key_nama', 100)->nullable();
            $table->string('route', 100)->nullable();
            $table->string('url');
            $table->string('icon', 100);
            $table->integer('ordinal_number')->nullable();
            $table->boolean('is_active');
            $table->string('tipe_menu');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
