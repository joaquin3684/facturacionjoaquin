<?php

use App\Clase;
use App\ServicioProfesorDia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


Route::get('a', function(){

    return "la concha de tu madres";
});

Route::post('login', 'LoginController@login');

Route::group(['middleware' => ['permisos', 'jwt.auth', 'meli']], function () {

    Route::get('ml/loginML', 'LoginController@ml');
    Route::get('ml/autenticar', 'LoginController@authorizar');
    Route::get('publicacion/all', 'ML\PublicacionController@all');
    Route::post('publicacion/linkear', 'ML\PublicacionController@linkear');
    Route::get('venta', 'ML\OrdenController@ordenes');
    Route::get('ml/token', function(Request $request){
        return $request['meli']->getToken();
    });

});

Route::group(['middleware' => ['permisos', 'jwt.auth']], function() {


    // USUARIO
    Route::post('usuario', 'UsuarioController@store');
    Route::post('usuario/cambiarPasswordPropia', 'UsuarioController@cambiarPasswordPropia');
    Route::post('usuario/cambiarPassword', 'UsuarioController@cambiarPassword');
    Route::post('usuario/habilitar', 'UsuarioController@habilitar');
    Route::get('usuario/all', 'UsuarioController@all');
    Route::get('usuario/{id}', 'UsuarioController@find');
    Route::put('usuario/{id}', 'UsuarioController@update');
    Route::delete('usuario/{id}', 'UsuarioController@delete');

    // PERFIL

    Route::get('perfil/all', 'PerfilController@all');


    // PRODUCTOS

    Route::post('producto', 'ProductoController@store');
    Route::put('producto/{id}', 'ProductoController@update');
    Route::delete('producto/{id}', 'ProductoController@delete');
    Route::get('producto/all', 'ProductoController@all');
    Route::get('producto/{id}', 'ProductoController@find');

    // Venta

    Route::post('venta', 'VentaController@store');
    Route::get('venta/all/{fechaDesde}/{fechaHasta}', 'VentaController@all');
    Route::get('venta/{id}', 'VentaController@find');

    // Compra

    Route::post('compra', 'CompraController@store');
    Route::put('compra/{id}', 'CompraController@update');
    Route::get('compra/all/{fechaDesde}/{fechaHasta}', 'CompraController@all');
    Route::delete('compra/{id}', 'CompraController@delete');


    // Publicacion

    Route::get('publicacion/all', 'PublicacionController@all');
    Route::put('publicacion/linkear/:idPublicacion', 'PublicacionController@linkear');
    Route::put('publicacion/deslinkear/:idPublicacion', 'PublicacionController@deslinkear');

});