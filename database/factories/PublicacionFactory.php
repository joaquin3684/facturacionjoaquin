<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 27/05/19
 * Time: 19:45
 */
$factory->define(App\Publicacion::class, function (Faker\Generator $faker) {

    return [
        'id_ml' => 1,
        'titulo' => 'samsung 5',
        'subtitulo' => ' es una verga',
        'precio' => 100,
        'precio_base' => 100,
        'precio_original' => 100,
        'moneda' => 'ARG',
        'stock_disponible' => 10,
        'cantidad_vendida' => 55,
        'tipo_lista' => 're piola',
        'fecha_cierre' => \Carbon\Carbon::today()->addMonth()->toDateString(),
        'fecha_inicio' => \Carbon\Carbon::today()->toDateString(),
        'link_ml' => 'https://chupamelapija.com',
        'foto' => 'http://fotodepija.jpg',
        'envio' => 'piola',
        'estado' => 'activa',
        'sku' => '32',
        'id_producto' => 1,
        'id_empresa' => 1
    ];
});