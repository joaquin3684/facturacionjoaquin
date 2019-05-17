<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 16/05/19
 * Time: 09:46
 */

namespace App\services\ML;


class OrdenService
{
    private $meli;

    public function __construct(Meli $meli)
    {
        $this->meli = $meli;
    }

    public function ordenesRecientes()
    {

        //$params = array('access_token' => $this->meli->getToken(), 'seller' => 191913278);
        $params = array('access_token' => $this->meli->getToken());
        return $this->meli->get('users/me', $params);
    }
}