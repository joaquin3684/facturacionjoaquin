<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 17/05/19
 * Time: 12:11
 */

namespace App\services\ML\Dom;


class UserML
{
    public $id;
    public $nickname;
    public $nombre;
    public $apellido;
    public $email;

    public function __construct($nickname, $nombre, $apellido, $email, $id)
    {
       $this->nickname = $nickname;
       $this->nombre = $nombre;
       $this->apellido = $apellido;
       $this->email = $email;
       $this->id = $id;
    }


}