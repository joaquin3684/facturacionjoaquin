<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Schema::create('items_factura', function (Blueprint $table) {
            $table->increments('id');
            $table->double('cantidad');
            $table->double('impuesto');
            $table->double('importe');
            $table->integer('id_factura')->unsigned();
            $table->foreign('id_factura')->references('id')->on('facturas');
            $table->integer('id_producto')->unsigned()->nullable();
            $table->foreign('id_producto')->references('id')->on('productos');
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

        Schema::dropIfExists('items_factura');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

    }
}
