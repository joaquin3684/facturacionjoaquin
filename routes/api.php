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
    Route::get('usuario/paraCreacion', 'UsuarioController@paraCreacion');
    Route::get('usuario/paraModificacion', 'UsuarioController@basicos');
    Route::get('usuario/paraRecuperarVenta', 'UsuarioController@basicos');
    Route::get('usuario/subordinables', 'UsuarioController@subordinables');
    Route::get('usuario/get/{id}', 'UsuarioController@find');
    Route::get('usuario/paraLogistica/{idPerfil}', 'UsuarioController@porPerfil');

    // PERFIL

    Route::get('usuario/perfiles/', 'PerfilController@all');


    // VENTA

    Route::post('venta/create', 'VentaController@crear');
    Route::post('venta/all', 'VentaController@all');
    Route::post('venta/editar/{id}', 'VentaController@editar');
    Route::post('venta/find/{id}', 'VentaController@find');
    Route::post('venta/existenciaDni', 'VentaController@existenciaDni');
    Route::post('venta/allFromSubordinados', 'VentaController@allFromSubordinados');

    // VALIDACION

    Route::post('validacion/validar', 'ValidacionController@validar');
    Route::get('validacion/all', 'ValidacionController@all');
    Route::get('validacion/ventasAValidar', 'ValidacionController@ventasAValidar');

    //MODIFICAR VENTA

    Route::put('modificar/updateVenta/{id}', 'ValidacionController@modificarVenta');
    Route::post('modificar/ventasAModificar', 'ValidacionController@ventasAModificar');

    //BORRAR

    Route::post('borrar/borrarVenta', 'ValidacionController@borrarVenta');


    //AUDITORIA

    Route::get('auditoria/all', 'AuditoriaController@all');
    Route::get('auditoria/ventasParaAuditar', 'AuditoriaController@ventasParaAuditar');
    Route::post('auditoria/auditar', 'AuditoriaController@auditar');


    // ADMINISTRACION DE VENTA

    Route::get('administracionVenta/ventasIncompletas', 'AdministracionController@ventasIncompletas');
    Route::get('administracionVenta/ventasPresentables', 'AdministracionController@ventasPresentables');
    Route::get('administracionVenta/ventasPresentadas', 'AdministracionController@ventasPresentadas');
    Route::get('administracionVenta/ventasRechazables', 'AdministracionController@ventasRechazables');
    Route::post('administracionVenta/completarVenta', 'AdministracionController@completarVenta');
    Route::post('administracionVenta/presentarVentas', 'AdministracionController@presentarVentas');
    Route::post('administracionVenta/analizarPresentacion', 'AdministracionController@analizarPresentacion');
    Route::post('administracionVenta/rechazar', 'AdministracionController@rechazar');


    // LOGISTICA

    Route::post('logisticaOper/generarVisita', 'VisitaController@generarVisita');
    Route::post('logistica/confirmarVisita', 'VisitaController@confirmarVisita');
    Route::post('logistica/repactarVisita', 'VisitaController@repactarVisita');
    Route::post('logistica/rechazar', 'VisitaController@rechazar');
    Route::get('logisticaOper/ventasSinVisita', 'VisitaController@ventasSinVisita');
    Route::get('logistica/get/{id}', 'VisitaController@getVisita');
    Route::get('logistica/ventasATrabajar', 'VisitaController@ventasATrabajar');
    Route::get('logistica/all', 'VisitaController@all');
    Route::post('logistica/asignarUsuario', 'VisitaController@asignarUsuario');
    Route::post('logistica/enviarAlCall', 'VisitaController@enviarAlCall');


    // RECUPERACION DE VENTA

    Route::get('recuperarVenta/all', 'RecuperarVentaController@ventasRecuperables');
    Route::post('recuperarVenta/recuperar', 'RecuperarVentaController@recuperar');
    Route::post('recuperarVenta/rechazo', 'RecuperarVentaController@rechazar');
    Route::get('recuperarVenta/ventasParaPoderRecuperar', 'RecuperarVentaController@ventasParaPoderRecuperar');
    Route::post('recuperarVenta/marcarParaRecuperar', 'RecuperarVentaController@marcarParaRecuperar');



    # ESTADISTICAS

    Route::post('estadistica/general', 'EstadisticaController@general');
    Route::get('estadistica/estados', 'EstadisticaController@estados');
    Route::post('estadistica/visitas', 'EstadisticaController@general');
    Route::post('estadistica/archivos', 'EstadisticaController@general');
    Route::post('estadistica/rechazos', 'EstadisticaController@general');
    Route::post('estadistica/vendedoras', 'EstadisticaController@vendedoras');
    Route::post('estadistica/promotoras', 'EstadisticaController@promotoras');
    Route::post('estadistica/promotoras/cantVisitas', 'EstadisticaController@promotorasCantVisitas');
    Route::post('estadistica/externos', 'EstadisticaController@externos');
    Route::post('estadistica/call', 'EstadisticaController@callCenter');
    Route::post('estadistica/empresa', 'EstadisticaController@general');
    Route::post('estadistica/localidadesEmpresa', 'EstadisticaController@general');
    Route::get('estadistica/zonas', 'EstadisticaController@zonas');
    Route::post('estadistica/ventasPorLocalidad', 'EstadisticaController@ventasPorLocalidad');
    Route::post('estadistica/ventasPorZona', 'EstadisticaController@ventasPorZona');
    Route::post('estadistica/ventasTotalesPorDia', 'EstadisticaController@ventasTotalesPorDia');
    Route::post('estadistica/ventasTotalesPorMes', 'EstadisticaController@ventasTotalesPorMes');
    Route::post('estadistica/ventasTotalesPorSemana', 'EstadisticaController@ventasTotalesPorSemana');
    Route::post('estadistica/ventasTotalesPorAnio', 'EstadisticaController@ventasTotalesPorAnio');
    Route::post('estadistica/eficienciaCall', 'EstadisticaController@eficienciaCall');
    Route::post('estadistica/eficienciaVendedora', 'EstadisticaController@eficienciaVendedora');
    Route::post('estadistica/eficienciaPromotora', 'EstadisticaController@eficienciaPromotora');
    Route::post('estadistica/eficienciaExterno', 'EstadisticaController@eficienciaExterno');
    Route::post('estadistica/cantidadVentasPorObraSocial', 'EstadisticaController@cantidadVentasPorObraSocial');
    Route::get('estadistica/indicadorVentasPresentadasDelMes', 'EstadisticaController@indicadorDelMes');


    //CAJA

    Route::post('caja/ingreso', 'CajaController@ingreso');
    Route::post('caja/egreso', 'CajaController@egreso');
    Route::post('caja/movimientos', 'CajaController@movimientos');


    //MEMBRESIA

    Route::get('membresia/all', 'MembresiaController@membresias');
    Route::get('membresia/allConTodo', 'MembresiaController@membresiasConTodo');
    Route::post('membresia/crear', 'MembresiaController@store');
    Route::put('membresia/editar/{id}', 'MembresiaController@update');
    Route::get('membresia/find/{id}', 'MembresiaController@find');
    Route::post('membresia/borrar', 'MembresiaController@delete');

    //VENTAS

    Route::post('ventas/all', 'VentaController@ventas');
    Route::post('ventas/crear', 'VentaController@crear');

    Route::post('ventas/borrar', 'VentaController@delete');
    Route::get('ventas/historialCompra/{idSocio}', 'VentaController@historialCompra');

    //CUOTAS

    Route::post('cuotas/cancelarPago', 'CuotaController@cancelarPago');

     // ACCESOS

    Route::post('accesos/all', 'AccesosController@accesos');

    // SERVICIOS

    Route::get('servicio/all', 'ServicioController@servicios');
    Route::post('servicio/crear', 'ServicioController@store');
    Route::put('servicio/editar/{id}', 'ServicioController@update');
    Route::get('servicio/find/{id}', 'ServicioController@find');
    Route::post('servicio/borrar', 'ServicioController@delete');

    Route::post('servicio/registrarEntradas', 'ServicioController@registrarEntradas');
    Route::post('servicio/devolverEntradas', 'ServicioController@devolverEntradas');

  // DESCUENTOS

    Route::get('descuento/all', 'DescuentoController@descuentos');
    Route::post('descuento/crear', 'DescuentoController@store');
    Route::put('descuento/editar/{id}', 'DescuentoController@update');
    Route::get('descuento/find/{id}', 'DescuentoController@find');
    Route::post('descuento/borrar', 'DescuentoController@delete');


    // CLASES

    Route::put('clase/editar/{id}', 'ClaseController@update');
    Route::post('clase/crear', 'ClaseController@create');
    Route::get('clase/enTranscurso', 'ClaseController@clasesEnTranscurso');
    Route::get('clase/delDia', 'ClaseController@clasesDelDia');
    Route::post('clase/all', 'ClaseController@all');
    Route::get('clase/futuras', 'ClaseController@clasesFuturas');
    Route::post('clase/registrarAlumnos', 'ClaseController@registrarAlumnos');
    Route::post('clase/sacarAlumnos', 'ClaseController@sacarAlumnos');
    Route::post('clase/registrarEntrada', 'ClaseController@registrarEntrada');
    Route::post('clase/devolverEntrada', 'ClaseController@devolverEntrada');

    // PROFESOR

    Route::put('profesor/editar/{id}', 'ProfesorController@update');
    Route::get('profesor/all', 'ProfesorController@all');
    Route::get('profesor/find/{id}', 'ProfesorController@find');
    Route::post('profesor/borrar', 'ProfesorController@delete');
    Route::post('profesor/crear', 'ProfesorController@store');

    // PRODUCTOS

    Route::put('producto/editar/{id}', 'ProductoController@update');
    Route::get('producto/all', 'ProductoController@all');
    Route::get('producto/find/{id}', 'ProductoController@find');
    Route::post('producto/borrar', 'ProductoController@delete');
    Route::post('producto/crear', 'ProductoController@store');

    Route::post('producto/comprar', 'ProductoController@comprar');
    Route::post('producto/vender', 'ProductoController@vender');
    Route::post('producto/registrosDeStock', 'ProductoController@registrosDeStock');
});