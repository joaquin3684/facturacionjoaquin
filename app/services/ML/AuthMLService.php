<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 11/05/19
 * Time: 21:24
 */

namespace App\services\ML;



class AuthMLService
{
    public function auth()
    {
        $meli = new Meli('2382179841161472', 'X6U4B4gZKELFsY739dANIwJ1qFuD5Bo4');
        $redirectUrl = $meli->getAuthUrl("https://facturacionjoaquin.herokuapp.com/autenticar",Meli::$AUTH_URL['MLA']); //  Don't forget to change the $AUTH_URL value to match your user's Site Id.
        return $redirectUrl;
    }

    public function autenticar($codigo)
    {
        $meli = new Meli('2382179841161472', 'X6U4B4gZKELFsY739dANIwJ1qFuD5Bo4');

        $user = $meli->authorize($codigo, "https://facturacionjoaquin.herokuapp.com/autenticar");

        return array('token' => $user['body']->access_token, 'refresh' => $user['body']->refresh_token, 'expires' => $user['body']->expires_in);


    }
}