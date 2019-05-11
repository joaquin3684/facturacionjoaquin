<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 08/03/19
 * Time: 00:27
 */

namespace App\services;


use App\Perfil;

class PerfilService
{
    public function all()
    {
        return Perfil::all();
    }
}