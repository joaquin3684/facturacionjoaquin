<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Schema::create('productos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->string('tipo_type');
            $table->integer('tipo_id');
            $table->text('descripcion')->nullable();
            $table->double('importe');
            $table->double('stock')->default(0);
            $table->double('impuesto')->default(0);
            $table->double('pto_reposicion')->default(0);
            $table->integer('id_empresa')->unsigned();
            $table->foreign('id_empresa')->references('id')->on('empresas');
            $table->softDeletes();
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

        Schema::dropIfExists('productos');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

    }
}
