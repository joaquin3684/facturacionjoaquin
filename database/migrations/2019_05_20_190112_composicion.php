<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Composicion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Schema::create('composicion', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_producto')->unsigned()->nullable();
            $table->foreign('id_producto')->references('id')->on('productos');
            $table->integer('id_componente')->unsigned()->nullable();
            $table->foreign('id_componente')->references('id')->on('productos');
            $table->integer('cantidad');
            $table->timestamps();
        });
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Schema::dropIfExists('composicion');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
