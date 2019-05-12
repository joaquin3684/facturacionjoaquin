<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 11/05/19
 * Time: 21:24
 */

namespace App\services\ML;


use Meli;

class AuthMLService
{
    public function auth()
    {
        $meli = new Meli('2382179841161472', 'X6U4B4gZKELFsY739dANIwJ1qFuD5Bo4');
        $redirectUrl = $meli->getAuthUrl("https://facturacionjoaquin.herokuapp.com",Meli::$AUTH_URL['MLA']); //  Don't forget to change the $AUTH_URL value to match your user's Site Id.

    }
}