<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 09/05/19
 * Time: 11:43
 */
$factory->define(App\Empresa::class, function (Faker\Generator $faker) {

    return [
        'nombre' => $faker->name,
        'cuit' => $faker->numberBetween(11111111111, 99999999999),
    ];
});