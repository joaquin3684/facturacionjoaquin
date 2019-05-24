<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Schema::create('publicaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo');
            $table->string('id_ml');
            $table->string('subtitulo');
            $table->double('precio');
            $table->double('precio_base');
            $table->double('precio_original');
            $table->string('moneda');
            $table->string('stock_disponible');
            $table->string('cantidad_vendida');
            $table->string('tipo_lista');
            $table->dateTime('fecha_cierre');
            $table->dateTime('fecha_inicio');
            $table->string('link_ml');
            $table->string('foto');
            $table->string('envio');
            $table->string('estado');
            $table->string('sku');
            $table->integer('id_producto')->unsigned()->nullable();
            $table->foreign('id_producto')->references('id')->on('productos');
            $table->integer('id_empresa')->unsigned()->nullable();
            $table->foreign('id_empresa')->references('id')->on('empresas');
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

        Schema::dropIfExists('publicaciones');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

    }
}
