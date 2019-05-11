<?php

namespace App\Http\Controllers;

use App\services\UsuarioService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    private $service;

    public function __construct()
    {
        $this->service = new UsuarioService();
    }

    public function create(Request $request)
    {
        Db::transaction(function () use ($request) {
            $perfiles = $request['perfiles'];
            $obrasSociales = $request['obrasSociales'];
            $password = $request['password'];
            $user = $request['user'];
            $nombre = $request['nombre'];
            $email = $request['email'];

            $subordinados = User::whereIn('id', $request['subordinados'])->get();


            $this->service->crear($nombre, $user, $password, $email, $perfiles, $obrasSociales, $subordinados);
        });
    }

    public function update(Request $request, $id)
    {
        Db::transaction(function () use ($request, $id) {
            $user = User::find($id);
            $perfiles = $request['perfiles'];
            $obrasSociales = $request['obrasSociales'];
            $nombre = $request['nombre'];
            $email = $request['email'];
            $subordinados = $request['subordinados'];


            $this->service->update($nombre, $email, $perfiles, $obrasSociales, $subordinados, $user);
        });
    }

    public function delete($id)
    {
        Db::transaction(function () use ($id) {
            $user = User::find($id);
            $this->service->delete($user);
        });
    }

    public function habilitar(Request $request)
    {
        Db::transaction(function () use ($request) {
            $user = User::withTrashed()->find($request['id']);
            $this->service->habilitar($user);
        });
    }

    public function all()
    {
        return $this->service->all();
    }

    public function paraCreacion()
    {
        return $this->service->paraCreacion();
    }

    public function basicos()
    {
        return $this->service->basicos();
    }

    public function subordinables()
    {
        return $this->service->subordinables();
    }

    public function find($id)
    {
        return $this->service->find($id);
    }

    public function porPerfil($id)
    {
        return $this->service->porPerfil($id);
    }

    public function cambiarPasswordPropia(Request $request)
    {

        Db::transaction(function () use ($request) {

            $user = User::find($request['userId']);
            $this->service->cambiarPassword($request['password'], $user);
        });
    }

    public function cambiarPassword(Request $request)
    {
        Db::transaction(function () use ($request) {
            $user = User::find($request['id']);
            $this->service->cambiarPassword($request['password'], $user);
        });
    }
}
