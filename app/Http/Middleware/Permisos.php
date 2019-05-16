<?php

namespace App\Http\Middleware;

use App\Exceptions\NoTienePermisoARutaException;
use App\Pantalla;
use App\Ruta;
use App\User;
use Closure;
use Mockery\Exception;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Tymon\JWTAuth\Facades\JWTAuth;

class Permisos
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = JWTAuth::getToken();
        $permisos = JWTAuth::decode($token)['permisos'];
        $fullPath = $request->getPathInfo();
        $path = explode("/", $fullPath);
        $pantalla = $path[1];
        $userId = JWTAuth::decode($token)['user_id'];
        $idEmpresa = JWTAuth::decode($token)['id_empresa'];

        $request->request->add(['userId' => 1]);
        $request->request->add(['idEmpresa' => $idEmpresa]);

        foreach($permisos as $permiso)
        {
            if($permiso == $pantalla)
            {
                return $next($request);
            }
        }
        $user = User::with('perfiles.pantallas.rutas')->find($userId);
        $userRoute = $user->perfiles->first(function($perfil) use ($fullPath) {
            return $perfil->pantallas->first(function ($pantalla) use ($fullPath) {
                return $pantalla->rutas->first(function ($ruta) use ($fullPath) {
                    return $ruta->ruta == $fullPath;
                });
            });
        });

        if($userRoute == null)
        {
            throw new \RuntimeException('acceso denegado');
        } else {
            return $next($request);
        }
    }
}
