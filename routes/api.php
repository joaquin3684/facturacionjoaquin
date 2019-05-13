<?php

use App\Clase;
use App\ServicioProfesorDia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


Route::get('prueba', function(){

    return "la concha de tu madres";
});

Route::post('login', 'LoginController@login');
Route::get('loginML', 'LoginController@ml');
Route::get('autenticar', 'LoginController@authorizar');

Route::group(['middleware' => ['permisos', 'jwt.auth']], function() {


    // USUARIO
    Route::post('usuario/create', 'UsuarioController@create');
    Route::post('usuario/cambiarPasswordPropia', 'UsuarioController@cambiarPasswordPropia');
    Route::post('usuario/cambiarPassword', 'UsuarioController@cambiarPassword');
    Route::delete('usuario/delete/{id}', 'UsuarioController@delete');
    Route::post('usuario/habilitar', 'UsuarioController@habilitar');
    Route::put('usuario/update/{id}', 'UsuarioController@update');
    Route::get('usuario/all', 'UsuarioController@all');
    Route::get('usuario/get/{id}', 'UsuarioController@find');

    // PERFIL

    Route::get('usuario/perfiles', 'PerfilController@all');


    // PRODUCTOS

    Route::post('producto', 'ProductoController@store');
    Route::put('producto/{id}', 'ProductoController@update');
    Route::delete('producto/{id}', 'ProductoController@delete');
    Route::get('producto/{id}', 'ProductoController@find');
    Route::get('producto/all', 'ProductoController@all');

    // FACTURA

});