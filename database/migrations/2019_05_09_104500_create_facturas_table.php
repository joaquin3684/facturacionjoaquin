<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Schema::create('facturas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cuit_emisor');
            $table->string('cuit_receptor');
            $table->string('tipo');
            $table->double('total_bruto');
            $table->double('total_impuestos');
            $table->double('total_neto');
            $table->integer('numero');
            $table->boolean('facturado');
            $table->boolean('ml');
            $table->date('fecha');
            $table->integer('id_empresa')->unsigned();
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

        Schema::dropIfExists('facturas');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

    }
}
