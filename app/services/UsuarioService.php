<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 08/03/19
 * Time: 00:10
 */

namespace App\services;


use App\Enums\Perfiles;
use App\User;
use Illuminate\Support\Facades\Hash;

class UsuarioService
{
    public function crear($nombre, $user, $password, $email, $perfiles, $idEmpresa)
    {
        $usuario = User::create([
            'nombre' => $nombre,
            'user' => $user,
            'password' => Hash::make($password),
            'email' => $email,
            'id_empresa' => $idEmpresa
        ]);

        $usuario->perfiles()->attach($perfiles);
        return $usuario;
    }

    public function update($nombre, $email, $perfiles, $idEmpresa, $token, $refresh_token, $expires, $id)
    {
        $user = User::find($id);
        $user->fill([
            'nombre' => $nombre,
            'user' => $user,
            'email' => $email,
            'id_empresa' => $idEmpresa,
            'token' => $token,
            'refresh_token' => $refresh_token,
            'expires_in' => $expires,
        ]);
        $user->save();
        $user->perfiles()->sync($perfiles);
        return $user;
    }


    public function all()
    {
        return User::withTrashed()->get();
    }

    public function getPerfiles($id)
    {
        return User::with('perfiles')->find($id)->perfiles;
    }

    public function delete($id)
    {
        User::destroy($id);
    }

    public function habilitar($id)
    {
        $user = $this->find($id);
        $user->restore();
    }

    public function find($id)
    {
        return User::with( 'perfiles')->find($id);
    }

    public function cambiarPassword($password, $id)
    {
        $user = $this->find($id);
        $user->password = Hash::make($password);
        $user->save();
        return $user;
    }


}