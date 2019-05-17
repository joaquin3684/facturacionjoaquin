<?php

namespace App\Http\Controllers;

use App\services\ML\AuthMLService;
use App\services\UsuarioService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{

    public function login(Request $request)
    {

        $credentials = $request->only('user', 'password');
        $user = User::where('user', $credentials['user'])->with('perfiles.pantallas')->firstOrFail();
        if(!Hash::check($credentials['password'], $user->password))
            throw new \RuntimeException('fasldf');
        $permisos = $user->perfiles->flatMap(function($perfil) {
            return $perfil->pantallas->map(function ($pantalla) {
                return $pantalla->nombre;
            });
        })->unique()->values();
        $perfiles = $user->perfiles->map(function ($p){ return $p->nombre;});
        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::customClaims(['permisos' => $permisos, 'perfiles' => $perfiles, 'user_id' => $user->id, 'nombre' => $user->nombre, 'id_empresa' => $user->id_empresa])->fromUser($user)) {
                return response()->json(['success' => false, 'error' => 'We cant find an account with this credentials. Please make sure you entered the right information and you have verified your email address.'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['success' => false, 'error' => 'Failed to login, please try again.'], 500);
        }
        // all good so return the token
        return response()->json(['success' => true, 'data'=> [ 'token' => $token ]]);
    }

    public function logout(Request $request)
    {
        $token = JWTAuth::getToken();
        return JWTAuth::decode($token);

    }

    public function ml(Request $request)
    {
        $serv = new AuthMLService($request['meli']);
        return $serv->auth();

    }

    public function authorizar(Request $request)
    {
        $serv = new AuthMLService($request['meli']);

        $arr = $serv->autenticar($request->code);
        $srv = new UsuarioService();
        $user = $request['meli']->getUser();
        return $user;
        $srv->update($user->nombre, $user->email, $user->perfiles->map(function($p){return $p->id;})->toArray(), $user->id_empresa, $arr['token'], $arr['refresh_token'], $arr['expires'], $request['userId']);

    }


}
