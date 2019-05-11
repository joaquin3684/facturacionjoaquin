<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 10/05/19
 * Time: 16:46
 */
$factory->define(App\ItemFactura::class, function (Faker\Generator $faker) {

    return [
        'cantidad' => $faker->randomNumber(1),
        'impuesto' => $faker->randomNumber(2),
        'importe' =>  $faker->randomNumber(3),
        'id_factura' => 1,
        'id_producto' => 1
    ];

});