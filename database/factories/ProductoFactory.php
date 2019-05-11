<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 09/05/19
 * Time: 11:19
 */
$factory->define(App\Producto::class, function (Faker\Generator $faker) {

    return [
        'nombre' => $faker->name,
        'descripcion' => $faker->name,
        'pto_reposicion' => 10,
        'importe' => 100,
        'id_ml' => null,
        'id_empresa' => 1,
    ];
});