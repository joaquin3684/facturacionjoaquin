<?php

use App\Clase;
use App\ServicioProfesorDia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


Route::get('a', function(){
return DB::transaction(function(){

    $users = DB::connection('falopa')
        ->select("
            select * from usuarios
        ");

    $obrasSociales  = DB::connection('falopa')
        ->select("
            select * from obras_sociales
        ");

    $perfiles = DB::connection('falopa')
        ->select("
            select * from perfiles
        ");

    $pantallas = DB::connection('falopa')
        ->select("
            select * from pantallas
        ");

    $usObs = DB::connection('falopa')
        ->select("
            select * from usuario_obra_social
        ");

    $pp = DB::connection('falopa')
        ->select("
            select * from perfil_pantalla
        ");


    $up = DB::connection('falopa')
        ->select("
            select * from usuario_perfil
        ");

    $ventas = DB::connection('falopa')
        ->select("
            select * from ventas
        ");

    foreach($users as $user)
    {
        \App\User::create(['nombre' => $user->nombre, 'email' => $user->email, 'password' => $user->password, 'user' => $user->user]);
    }

    foreach($perfiles as $perfil)
    {
        \App\Perfil::create(['nombre' => $perfil->nombre]);
    }

    foreach ($pantallas as $pantalla)
    {
        \App\Pantalla::create(['nombre' => $pantalla->nombre]);
    }

    foreach ($obrasSociales as $o)
    {
        \App\ObraSocial::create(['nombre' => $o->nombre]);
    }

    foreach ($usObs as $uo)
    {
        $us = \App\User::where('user', $uo->id_usuario)->first();
        $ob = \App\ObraSocial::where('nombre', $uo->id_obra_social)->first();
        $us->obrasSociales()->attach($ob->id);
    }

    foreach ($pp as $p)
    {
        $per = \App\Perfil::where('nombre', $p->id_perfil)->first();
        $pan = \App\Pantalla::where('nombre', $p->id_pantalla)->first();
        $per->pantallas()->attach($pan->id);
    }

    foreach ($up as $p)
    {
        $per = \App\Perfil::where('nombre', $p->perfil)->first();
        $us = \App\User::where('user', $p->user)->first();
        $us->perfiles()->attach($per->id);
    }

    foreach ($ventas as $v)
    {
        $ob = \App\ObraSocial::where('nombre', $v->id_obra_social)->first();

        $ven = \App\Venta::create([
            'dni' => $v->dni,
            'nombre' => $v->nombre,
            'nacionalidad' => $v->nacionalidad,
            'domicilio' => $v->domicilio,
            'localidad' => $v->localidad,
            'telefono' => $v->telefono,
            'cuil' => $v->cuil,
            'estadoCivil' => $v->estadoCivil,
            'edad' => $v->edad,
            'id_obra_social' => $ob->id,
            'fecha_nacimiento' => $v->fecha_nacimiento,
            'zona' => $v->zona,
            'codigo_postal' => $v->codigo_postal,
            'hora_contacto_tel' => $v->hora_contacto_tel,
            'piso' => $v->piso,
            'departamento' => $v->departamento,
            'celular' => $v->celular,
            'hora_contacto_cel' => $v->hora_contacto_tel,
            'base' => $v->base,
            'empresa' => $v->empresa,
            'cuit' => $v->cuit,
            'tres_porciento' => $v->tres_porciento,
            'capitas' => $v->capitas,
            'pendiente_documentacion' => $v->pendiente_documentacion
        ]);

        $vali = DB::connection('falopa')
            ->select("
            select * from validaciones where id_venta = '$v->id'
        ");

        foreach($vali as $va)
        {
            \App\Validacion::create(['codem' => $va->codem, 'afip' => $va->afip, 'super' => $va->super, 'id_venta' => $ven->id]);
        }

        $audi = DB::connection('falopa')
            ->select("
            select * from auditorias where id_venta = '$v->id'
        ");

        foreach($audi as $au)
        {
            \App\Auditoria::create(['audio' => $au->audio, 'audio2' => $au->audio2, 'audio3' => $au->audio3, 'observacion' => $au->obsevacion, 'adherentes' => $au->adherentes, 'id_venta' => $ven->id]);
        }

        $logis = DB::connection('falopa')
            ->select("
            select * from visitas where id_venta = '$v->id'
        ");

        foreach($logis as $vis)
        {
                $u = \App\User::where('user', $vis->id_user)->first();

            \App\Visita::create(['id_venta' => $ven->id, 'lugar' => $vis->lugar, 'direccion' => $vis->direccion, 'entre_calles' => $vis->entre_calles, 'localidad' => $vis->localidad, 'observacion' => $vis->observacion, 'fecha' => $vis->fecha, 'hora' => $vis->hora, 'estado' => $vis->estado, 'id_user' => is_null($u) ? null : $u->id, 'observacion2' => $vis->observacion2]);
        }

        $datosEmpresa = DB::connection('falopa')
            ->select("
            select * from datos_empresa where id_venta = '$v->id'
        ");

        foreach($datosEmpresa as $da)
        {
            \App\DatosEmpresa::create(['empresa' => $da->empresa, 'id_venta' => $ven->id, 'hora_entrada' => $da->hora_entrada, 'hora_salida' => $da->hora_salida, 'cantidad_empleados' => $da->cantidad_empleados, 'direccion' => $da->direccion, 'localidad' => $da->localidad]);
        }

        $estados = DB::connection('falopa')
            ->select("
            select * from estados where id_venta = '$v->id'
        ");

        foreach ($estados as $es)
        {
            $u = \App\User::where('user', $es->user)->firstOrFail();
            \App\Estado::create([
                'id_usuario' => $u->id,
                'id_venta' => $ven->id,
                'estado' => $es->estado,
                'fecha' => $es->fecha,
                'recuperable' => $es->recuperable,
                'observacion' => $es->observacion]);
        }
    }
});

    return 1;

});

Route::get('prueba', function(){
    $spd = ServicioProfesorDia::with('servicio', 'profesor', 'dia')->get();
    $serv = $spd->map(function($s){
        $ser = $s->servicio;
        return $ser;
    })->unique(function($serv){
        return $serv->id;
    });

    $serv = $serv->map(function($ser) use ($spd){
        $filtroPorElServicioActual = $spd->filter(function($s) use ($ser){return $s->id_servicio == $ser->id;});
        $ser->dias = $filtroPorElServicioActual->map(function($s) use ($spd){


            $s->profesores = $filtroPorDiaYHoraYservicio = $spd->filter(function($s2) use ($s){
                return $s2->id_servicio == $s->id_servicio && $s2->id_dia == $s->id_dia && $s2->desde == $s->desde && $s2->hasta == $s->hasta && $s2->entrada_desde == $s->entrada_desde && $s2->entrada_hasta == $s->entrada_hasta;
            })->map(function($s){ return $s->profesor;});

            return $s;
        })->unique(function($s){ $s->id_servicio.$s->id_dia.$s->desde.$s->hasta.$s->entrada_desde.$s->entrada_hasta;});

        return $ser;
    });


    $serv->each(function($servicio){
        $servicio->dias->each(function($dia) use ($servicio){
            Clase::create(['fecha' => Carbon::today()->toDateString(), 'dia' => $dia->id_dia, 'id_servicio' => $servicio->id, 'estado' => 1, 'desde' => $dia->desde, 'hasta' => $dia->hasta, 'entrada_desde' => $dia->entrada_desde, 'entrada_hasta' => $dia->entrada_hasta, 'id_dia' => $dia->id_dia]);

        });

    });

    return 1;
});

Route::post('login', 'LoginController@login');


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