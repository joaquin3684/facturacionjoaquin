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

    public function __construct()
    {
        $this->meli = new Meli('2382179841161472', 'X6U4B4gZKELFsY739dANIwJ1qFuD5Bo4');
    }

    public function ordenesRecientes($access_token)
    {

        $params = array('access_token' => $access_token, 'seller' => 191913278);
        return $this->meli->get('orders/search/recent', $params);
    }
}