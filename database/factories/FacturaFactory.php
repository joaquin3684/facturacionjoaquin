<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 10/05/19
 * Time: 16:46
 */
$factory->define(App\Factura::class, function (Faker\Generator $faker) {

    return [
        'cuit_emisor' => $faker->numberBetween(11111111, 99999999),
        'cuit_receptor' => $faker->numberBetween(1111111, 9999999),
        'total_bruto' => $faker->randomNumber(4),
        'total_impuestos' => $faker->randomNumber(2),
        'total_neto' => $faker->randomNumber(3),
        'tipo' => 'A',
        'facturado' => true,
        'ml' => false,
        'numero' => $faker->randomNumber(6),
        'fecha' => \Carbon\Carbon::today()->toDateString(),
        'id_empresa' => 1,
        'entregado' => false
    ];

});