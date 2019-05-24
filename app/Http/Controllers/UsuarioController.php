<?php

namespace App\Http\Controllers;

use App\services\UsuarioService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{

    public function store(Request $request)
    {
        Db::transaction(function () use ($request) {

            (new User())->create([
                'nombre' => $request['nombre'],
                'user' => $request['user'],
                'password' => $request['password'],
                'email' => $request['email'],
                'id_empresa' => $request['idEmpresa'],
                'perfiles' => $request['perfiles']
            ]);

        });
    }

    public function update(Request $request, $id)
    {
        Db::transaction(function () use ($request, $id) {
            $user = User::find($id);
            $user->fill([
                'nombre' => $request['nombre'],
                'user' => $request['user'],
                'email' => $request['email'],
                'id_empresa' => $request['idEmpresa'],
            ]);
            $user->save();
            $user->perfiles()->sync($request['perfiles']);

        });
    }

    public function delete($id)
    {
        Db::transaction(function () use ($id) {
            User::destroy($id);
        });
    }

    public function habilitar(Request $request)
    {
        Db::transaction(function () use ($request) {
            $user = User::withTrashed()->find($request['id']);
            $user->restore();
        });
    }

    public function all()
    {
        return User::withTrashed()->get();
    }

    public function find($id)
    {
        return User::with( 'perfiles')->find($id);
    }

    public function cambiarPasswordPropia(Request $request)
    {

        Db::transaction(function () use ($request) {

            $user = User::find($request['userId']);
            $user->cambiarPassword($request['password']);
        });
    }

    public function cambiarPassword(Request $request)
    {
        Db::transaction(function () use ($request) {
            $user = User::find($request['id']);
            $user->cambiarPassword($request['password']);

        });
    }
}
